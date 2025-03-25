<?php

session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$nurse_id = $_SESSION['user_id'];
$nurse = [];
// Fetch nurse details
$sql = "SELECT user_id, first_name, last_name, email, gender, phone, dob, salary, duty_hour FROM Nurse WHERE user_id = ?";
$stmt = $con->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $nurse_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $nurse = $result->fetch_assoc();
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_nurse'])) {
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $dutyhour = $_POST['duty_hour'];

    // Ensure user ID is set
    if (!empty($nurse_id)) {

        // Start a transaction (for atomicity)
        $con->begin_transaction();

        // Update Users Table (email only)
        $update_users_sql = "UPDATE Users SET email = ? WHERE user_id = ?";
        $stmt_users = $con->prepare($update_users_sql);

        if ($stmt_users) {
            $stmt_users->bind_param("ss", $email, $nurse_id);

            if ($stmt_users->execute()) {
                // Users table updated successfully, now update Staff table
                $update_staff_sql = "UPDATE Staff SET email = ?, gender = ?, phone = ?, dob = ? WHERE user_id = ?";
                $stmt_staff = $con->prepare($update_staff_sql);

                if ($stmt_staff) {
                    $stmt_staff->bind_param("sssss", $email, $gender, $phone, $dob, $nurse_id);

                    if ($stmt_staff->execute()) {
                        // Staff table updated successfully, now update Nurse table
                        $update_nurse_sql = "UPDATE Nurse SET email = ?, gender = ?, phone = ?, dob = ?, duty_hour = ? WHERE user_id = ?";
                        $stmt_nurse = $con->prepare($update_nurse_sql);

                        if ($stmt_nurse) {
                            $stmt_nurse->bind_param("ssssss", $email, $gender, $phone, $dob, $dutyhour, $nurse_id);

                            if ($stmt_nurse->execute()) {
                                // All updates successful, commit the transaction
                                $con->commit();
                                echo "<script>alert('Profile updated successfully!'); window.location.href='nurse_dashboard.php';</script>";
                            } else {
                                // Nurse table update failed, rollback transaction
                                $con->rollback();
                                echo "<script>alert('Error updating Nurse profile. Please try again.');</script>";
                            }
                            $stmt_nurse->close();
                        } else {
                            // Nurse prepare failed, rollback transaction
                            $con->rollback();
                            echo "<script>alert('Database error updating Nurse. Please try again.');</script>";
                        }
                    } else {
                        // Staff table update failed, rollback transaction
                        $con->rollback();
                        echo "<script>alert('Error updating Staff profile. Please try again.');</script>";
                    }
                    $stmt_staff->close();
                } else {
                    // Staff prepare failed, rollback transaction
                    $con->rollback();
                    echo "<script>alert('Database error updating Staff. Please try again.');</script>";
                }
            } else {
                // Users table update failed, rollback transaction
                $con->rollback();
                echo "<script>alert('Error updating Users profile. Please try again.');</script>";
            }
            $stmt_users->close();
        } else {
            // User prepare failed, rollback transaction
            $con->rollback();
            echo "<script>alert('Database error updating Users. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('User ID missing. Cannot update profile.');</script>";
    }
}

// Fetch Patient Information
$patientInfo = [];
$sql = "SELECT p.user_id, p.first_name, p.last_name, p.gender, p.dob, p.blood_group, mh.allergies, mh.pre_conditions
        FROM Patient p
        LEFT JOIN MedicalHistory mh ON p.user_id = mh.patient_user_id";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patientInfo[] = $row;
    }
}

// Fetch treatment plans data with doctor and patient names
$sql = "SELECT tp.trtplan_id, tp.prescribe_date, tp.dosage, tp.suggestion,
               p.first_name AS patient_first_name, p.last_name AS patient_last_name, p.user_id AS patient_user_id,
               d.first_name AS doctor_first_name, d.last_name AS doctor_last_name
        FROM TreatmentPlan tp
        JOIN Patient p ON tp.patient_user_id = p.user_id
        LEFT JOIN Doctor d ON tp.doctor_user_id = d.user_id
        ORDER BY p.first_name, p.last_name, d.first_name, d.last_name"; // Order by patient, doctor

$result = $con->query($sql);

if ($result === false) {
    echo "SQL Error: " . $con->error;
    die();
}
$sql = "SELECT tp.trtplan_id, tp.prescribe_date, tp.dosage, tp.suggestion,
               p.first_name AS patient_first_name, p.last_name AS patient_last_name, p.user_id AS patient_user_id,
               d.first_name AS doctor_first_name, d.last_name AS doctor_last_name
        FROM TreatmentPlan tp
        JOIN Patients p ON tp.patient_user_id = p.user_id
        LEFT JOIN Doctors d ON tp.doctor_user_id = d.user_id";

$result = $con->query($sql);

if ($result === false) {
    echo "SQL Error: " . $con->error;
    die();
}

$treatmentPlans = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patientId = $row['patient_user_id'];
        $doctorName = $row['doctor_first_name'] . ' ' . $row['doctor_last_name'];

        if (!isset($treatmentPlans[$patientId])) {
            $treatmentPlans[$patientId] = [
                'patient_name' => $row['patient_first_name'] . ' ' . $row['patient_last_name'],
                'doctors' => []
            ];
        }

        if (!isset($treatmentPlans[$patientId]['doctors'][$doctorName])) {
            $treatmentPlans[$patientId]['doctors'][$doctorName] = [];
        }

        $treatmentPlans[$patientId]['doctors'][$doctorName][] = [
            'trtplan_id' => $row['trtplan_id'],
            'prescribe_date' => $row['prescribe_date'],
            'dosage' => $row['dosage'],
            'suggestion' => $row['suggestion']
        ];
    }
}
$con->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <title>Nurse Dashboard</title>
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    </style>
</head>

<body style="padding-top: 50px;">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <a class="navbar-brand" href="#">
            <i class="fa fa-hospital-o"></i> Hospital Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid" style="margin-top: 50px;">
        <h3 class="text-center">Welcome <?php echo htmlspecialchars($nurse['first_name']); ?></h3>

        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-4" style="max-width:18%;margin-top: 3%;">
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" href="#list-dash" data-toggle="list">Dashboard</a>
                    <a class="list-group-item list-group-item-action" href="#list-profile" data-toggle="list">Update Profile</a>
                    <a class="list-group-item list-group-item-action" href="#list-patients" data-toggle="list">Patient Overview</a>
                    <a class="list-group-item list-group-item-action" href="#list-pdetails" data-toggle="list">Patient Medical Details</a>
                    <a class="list-group-item list-group-item-action" href="#list-performtest" data-toggle="list">Perform Tests</a>
                </div>
            </div>

            <div class="col-md-8" style="margin-top: 3%;">
                <div class="tab-content" id="nav-tabContent" style="width: 950px;">
                    <!-- Dashboard -->
                    <div class="tab-pane fade show active" id="list-dash">
                        <div class="container-fluid bg-white p-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="profile-card">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>User ID:</strong> <?php echo htmlspecialchars($nurse['user_id']); ?></p>
                                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($nurse['phone']); ?></p>
                                                <p><strong>Gender:</strong> <?php echo htmlspecialchars($nurse['gender']); ?></p>
                                                <p><strong>Duty Hour:</strong> <?php echo htmlspecialchars($nurse['duty_hour']); ?></p>

                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($nurse['first_name'] . ' ' . $nurse['last_name']); ?></p>
                                                <p><strong>Email:</strong> <?php echo htmlspecialchars($nurse['email']); ?></p>
                                                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($nurse['dob']); ?></p>
                                                <p><strong>Salary:</strong> <?php echo htmlspecialchars($nurse['salary']); ?></p>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Update Profile -->
                    <div class="tab-pane fade show" id="list-profile">
                        <h4>Personal Information</h4>
                        <form method="POST">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>User ID:</label>
                                    <input type="text" class="form-control  " value="<?= $nurse['user_id'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>First Name:</label>
                                    <input type="text" class="form-control  " value="<?= $nurse['first_name'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Last Name:</label>
                                    <input type="text" class="form-control  " value="<?= $nurse['last_name'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Gender:</label>
                                    <select class="form-control  " name="gender">
                                        <option value="Male" <?= isset($nurse['gender']) && $nurse['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= isset($nurse['gender']) && $nurse['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= isset($nurse['gender']) && $nurse['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Email:</label>
                                    <input type="email" name="email" class="form-control  " value="<?= $nurse['email'] ?? '' ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Phone:</label>
                                    <input type="text" name="phone" class="form-control  " value="<?= $nurse['phone'] ?? '' ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Date of Birth:</label>
                                    <input type="date" class="form-control  " name="dob" value="<?= htmlspecialchars($nurse['dob'] ?? '') ?>">
                                </div>
                            </div>
                            <h4 class="mt-4">Professional Information</h4>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Salary:</label>
                                    <input type="text" class="form-control  " value="<?= $nurse['salary'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Duty Hour:</label>
                                    <input type="text" name="duty_hour" class="form-control  " value="<?= $nurse['duty_hour'] ?? '' ?>">
                                </div>
                            </div>
                        </form>
                        <br>
                        <div class="text-left">
                            <button type="submit" name="update_nurse" class="btn btn-primary">Update</button>
                        </div>
                        </form>
                    </div>

                    <!-- Patient Overview -->
                    <div class="tab-pane fade" id="list-patients">
                        <div class="overview-section">
                            <div class="row">
                                <div class="col-md-3 filter-group">
                                    <label for="patientSearch">Filter by Name:</label>
                                    <input type="text" class="form-control form-control-sm " id="patientSearch" placeholder="Enter Name">
                                </div>
                                <div class="col-md-3 filter-group">
                                    <label for="ageFilter">Filter by Age:</label>
                                    <select class="form-control form-control-sm " id="ageFilter">
                                        <option value="">All Ages</option>
                                        <option value="0-18">0-18</option>
                                        <option value="19-35">19-35</option>
                                        <option value="36-50">36-50</option>
                                        <option value="51+">51+</option>
                                    </select>
                                </div>
                                <div class="col-md-3 filter-group">
                                    <label for="bloodGroupFilter">Filter by Blood Group:</label>
                                    <select class="form-control form-control-sm " id="bloodGroupFilter">
                                        <option value="">All Blood Groups</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover" id="patientsTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Patient Name</th>
                                    <th style="width: 10%;">Gender</th>
                                    <th style="width: 10%;">Age</th>
                                    <th style="width: 20%;">Blood Group</th>
                                    <th style="width: 20%;">Allergies</th>
                                    <th style="width: 25%;">Preconditions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($patientInfo) > 0): ?>
                                    <?php $index = 1;
                                    foreach ($patientInfo as $patient): ?>
                                        <tr>
                                            <td><?= $index++ ?></td>
                                            <td><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
                                            <td><?= htmlspecialchars($patient['gender']) ?></td>
                                            <td data-age="<?php
                                                            if (!empty($patient['dob'])) {
                                                                $dob = new DateTime($patient['dob']);
                                                                $today = new DateTime();
                                                                $age = $today->diff($dob)->y;
                                                                echo $age;
                                                            } else {
                                                                echo 'N/A';
                                                            }
                                                            ?>">
                                                <?php
                                                if (!empty($patient['dob'])) {
                                                    $dob = new DateTime($patient['dob']);
                                                    $today = new DateTime();
                                                    $age = $today->diff($dob)->y;
                                                    echo $age;
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </td>
                                            <td data-blood-group="<?= htmlspecialchars($patient['blood_group']) ?>"><?= htmlspecialchars($patient['blood_group']) ?></td>
                                            <td><?= htmlspecialchars($patient['allergies'] ?: 'N/A') ?></td>
                                            <td><?= htmlspecialchars($patient['pre_conditions'] ?: 'N/A') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7">No patient information found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Patient Medical Details -->
                    <div class="tab-pane fade" id="list-pdetails">
                        <div class="medical-details-section">
                            <div class="row">
                                <div class="col-md-4 filter-group">
                                    <label for="patientNameFilter">Filter by Patient Name:</label>
                                    <input type="text" class="form-control form-control-sm " id="patientNameFilter" placeholder="Enter Name">
                                </div>
                                <div class="col-md-4 filter-group">
                                    <label for="doctorNameFilter">Filter by Doctor Name:</label>
                                    <input type="text" class="form-control form-control-sm " id="doctorNameFilter" placeholder="Enter Doctor Name">
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover" id="medicalDetailsTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Patient Name</th>
                                    <th style="width: 20%;">Doctor Name</th>
                                    <th style="width: 20%;">Date of Treatment</th>
                                    <th style="width: 20%;">Dosage</th>
                                    <th style="width: 25%;">Suggestions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($treatmentPlans) > 0): ?>
                                    <?php $index = 1;
                                    foreach ($treatmentPlans as $patientId => $patientData): ?>
                                        <?php foreach ($patientData['doctors'] as $doctorName => $doctorPlans): ?>
                                            <tr data-plans='<?= json_encode($doctorPlans) ?>' data-patient-name='<?= htmlspecialchars($patientData['patient_name']) ?>' data-doctor-name='<?= htmlspecialchars($doctorName) ?>'>
                                                <td><?= $index++ ?></td>
                                                <td><?= htmlspecialchars($patientData['patient_name']) ?></td>
                                                <td><?= htmlspecialchars($doctorName) ?></td>
                                                <td>
                                                    <select class="form-control  date-select">
                                                        <option value="">Select Date</option>
                                                        <?php foreach ($doctorPlans as $plan): ?>
                                                            <option value="<?= htmlspecialchars($plan['trtplan_id']) ?>"><?= htmlspecialchars($plan['prescribe_date'] ?? 'Not Yet Prescribed') ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td class="dosage"></td>
                                                <td class="suggestion"></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">No medical details found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const patientSearchInput = document.getElementById('patientSearch');
            const ageFilterSelect = document.getElementById('ageFilter');
            const bloodGroupFilterSelect = document.getElementById('bloodGroupFilter');
            const patientsTableBody = document.querySelector('#patientsTable tbody');

            function filterPatients() {
                const searchValue = patientSearchInput.value.toLowerCase();
                const ageFilterValue = ageFilterSelect.value;
                const bloodGroupFilterValue = bloodGroupFilterSelect.value;
                const rows = patientsTableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const age = row.querySelector('td:nth-child(4)').dataset.age;
                    const bloodGroup = row.querySelector('td:nth-child(5)').dataset.bloodGroup;

                    let nameMatch = name.includes(searchValue);
                    let ageMatch = true;
                    let bloodGroupMatch = true;

                    if (ageFilterValue) {
                        if (ageFilterValue === '51+') {
                            ageMatch = parseInt(age) >= 51;
                        } else {
                            const [minAge, maxAge] = ageFilterValue.split('-').map(Number);
                            ageMatch = parseInt(age) >= minAge && parseInt(age) <= maxAge;
                        }
                    }

                    if (bloodGroupFilterValue) {
                        bloodGroupMatch = bloodGroup === bloodGroupFilterValue;
                    }

                    if (nameMatch && ageMatch && bloodGroupMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            patientSearchInput.addEventListener('keyup', filterPatients);
            ageFilterSelect.addEventListener('change', filterPatients);
            bloodGroupFilterSelect.addEventListener('change', filterPatients);
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Patient and Doctor Name Filters
            const patientNameFilter = document.getElementById('patientNameFilter');
            patientNameFilter.addEventListener('keyup', function() {
                filterTable();
            });

            const doctorNameFilter = document.getElementById('doctorNameFilter');
            doctorNameFilter.addEventListener('keyup', function() {
                filterTable();
            });

            // Date Selection
            const table = document.getElementById('medicalDetailsTable');
            table.addEventListener('change', function(event) {
                if (event.target.classList.contains('date-select')) {
                    const select = event.target;
                    const row = select.closest('tr');
                    if (!row || !row.dataset.plans) return;

                    let plans;
                    try {
                        plans = JSON.parse(row.dataset.plans);
                    } catch (e) {
                        console.error("Invalid JSON in dataset.plans", e);
                        return;
                    }

                    const selectedPlan = plans.find(plan => plan.trtplan_id == select.value);

                    if (selectedPlan) {
                        row.querySelector('.dosage').innerHTML = selectedPlan.dosage ?
                            htmlspecialchars(selectedPlan.dosage) : '<span class="text-muted">No Dosage Given</span>';
                        row.querySelector('.suggestion').innerHTML = selectedPlan.suggestion ?
                            htmlspecialchars(selectedPlan.suggestion) : '<span class="text-muted">No Suggestion</span>';
                    } else {
                        row.querySelector('.dosage').innerHTML = '<span class="text-muted">No Dosage Given</span>';
                        row.querySelector('.suggestion').innerHTML = '<span class="text-muted">No Suggestion</span>';
                    }
                }
            });

            // Helper function to filter the table
            function filterTable() {
                const patientFilter = document.getElementById('patientNameFilter').value.toLowerCase();
                const doctorFilter = document.getElementById('doctorNameFilter').value.toLowerCase();
                const rows = document.querySelectorAll('#medicalDetailsTable tbody tr');

                rows.forEach(row => {
                    const patientName = row.dataset.patientName.toLowerCase();
                    const doctorName = row.dataset.doctorName.toLowerCase();
                    const shouldShow = patientName.includes(patientFilter) && doctorName.includes(doctorFilter);
                    row.style.display = shouldShow ? '' : 'none';
                });
            }

            // Helper function for HTML escaping
            function htmlspecialchars(str) {
                if (typeof(str) == "string") {
                    str = str.replace(/&/g, "&amp;");
                    str = str.replace(/"/g, "&quot;");
                    str = str.replace(/'/g, "&#039;");
                    str = str.replace(/</g, "&lt;");
                    str = str.replace(/>/g, "&gt;");
                }
                return str;
            }
        });
    </script>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>