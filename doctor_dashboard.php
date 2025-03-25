<?php

session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
include 'form_modal.php';

$doctor_id = $_SESSION['user_id'];
$doctor = [];
$departments = [];

// Fetch doctor details
$sql = "SELECT user_id, first_name, last_name, email, gender, phone, dob, salary, doc_fee, specialization, availability FROM Doctor WHERE user_id = ?";
$stmt = $con->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $doctor = $result->fetch_assoc();
    }
    $stmt->close();
}

// Fetch specialization options from Department table
$sql = "SELECT dept_name FROM Department";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row['dept_name'];
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_doctor'])) {
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $doc_fee = $_POST['doc_fee'];
    $specialization = $_POST['specialization'];
    $availability = $_POST['availability'];

    // Ensure user ID is set
    if (!empty($doctor_id)) {

        // Start a transaction (for atomicity)
        $con->begin_transaction();

        // Update Users Table (email only)
        $update_users_sql = "UPDATE Users SET email = ? WHERE user_id = ?";
        $stmt_users = $con->prepare($update_users_sql);

        if ($stmt_users) {
            $stmt_users->bind_param("ss", $email, $doctor_id);

            if ($stmt_users->execute()) {
                // Users table updated successfully, now update Staff table
                $update_staff_sql = "UPDATE Staff SET email = ?, gender = ?, phone = ?, dob = ? WHERE user_id = ?";
                $stmt_staff = $con->prepare($update_staff_sql);

                if ($stmt_staff) {
                    $stmt_staff->bind_param("sssss", $email, $gender, $phone, $dob, $doctor_id);

                    if ($stmt_staff->execute()) {
                        // Staff table updated successfully, now update Doctor table
                        $update_doctor_sql = "UPDATE Doctor SET email = ?, gender = ?, phone = ?, dob = ?, doc_fee = ?, specialization = ?, availability = ? WHERE user_id = ?";
                        $stmt_doctor = $con->prepare($update_doctor_sql);

                        if ($stmt_doctor) {
                            $stmt_doctor->bind_param("ssssssss", $email, $gender, $phone, $dob, $doc_fee, $specialization, $availability, $doctor_id);

                            if ($stmt_doctor->execute()) {
                                // All updates successful, commit the transaction
                                $con->commit();
                                echo "<script>alert('Profile updated successfully!'); window.location.href='doctor_dashboard.php';</script>";
                            } else {
                                // Doctor table update failed, rollback transaction
                                $con->rollback();
                                echo "<script>alert('Error updating Doctor profile. Please try again.');</script>";
                            }
                            $stmt_doctor->close();
                        } else {
                            // Doctor prepare failed, rollback transaction
                            $con->rollback();
                            echo "<script>alert('Database error updating Doctor. Please try again.');</script>";
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



// Fetch Appointments 
$appointments = [];
$sql = "SELECT a.appt_id, a.appt_date, a.appt_time, c.appt_status, p.first_name AS patient_first_name, p.last_name AS patient_last_name, p.gender AS patient_gender
        FROM Appointment a
        INNER JOIN checkup c ON a.appt_id = c.appt_id
        INNER JOIN Patient p ON c.patient_user_id = p.user_id
        WHERE c.doctor_user_id = ?";
$stmt = $con->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }
    $stmt->close();
}

// Update Appointment Status 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $appt_id = $_POST['appt_id'];
    $appt_status = $_POST['appt_status'];

    $update_sql = "UPDATE checkup SET appt_status = ? WHERE appt_id = ? AND doctor_user_id = ?";
    $stmt = $con->prepare($update_sql);
    if ($stmt) {
        $stmt->bind_param("sss", $appt_status, $appt_id, $doctor_id);
        if ($stmt->execute()) {
            echo "<script>alert('Appointment status updated successfully!'); window.location.href='doctor_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating appointment status. Please try again.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Database error. Please try again.');</script>";
    }
}


// Fetch Ongoing Patients
$ongoingPatients = [];
$sql = "SELECT p.user_id, p.first_name, p.last_name, p.blood_group, mh.allergies, mh.pre_conditions
        FROM Patient p
        INNER JOIN checkup c ON p.user_id = c.patient_user_id
        LEFT JOIN MedicalHistory mh ON p.user_id = mh.patient_user_id
        WHERE c.doctor_user_id = ? AND c.appt_status = 'Ongoing'";
$stmt = $con->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ongoingPatients[] = $row;
        }
    }
    $stmt->close();
}

// Fetch Test Results
$patientTests = [];
$sql = "SELECT 
            p.first_name, 
            p.last_name, 
            t.test_name, 
            dtp.test_date, 
            dtp.result,
            dtp.patient_user_id
        FROM Doc_Test_Patient dtp
        JOIN Patient p ON dtp.patient_user_id = p.user_id
        JOIN Test t ON dtp.test_id = t.test_id
        WHERE dtp.doctor_user_id = ?
        ORDER BY dtp.pres_date DESC";

$stmt = $con->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patientTests[$row['patient_user_id']]['patient_name'] = $row['first_name'] . ' ' . $row['last_name'];
            $patientTests[$row['patient_user_id']]['tests'][] = [
                'test_name' => $row['test_name'],
                'test_date' => $row['test_date'],
                'result' => $row['result']
            ];
        }
    }
    $stmt->close();
}

// Fetch Treatment Plans for Logged-in Doctor
$treatmentPlans = [];
$sql = "SELECT 
            p.first_name, 
            p.last_name, 
            tp.trtplan_id,
            tp.prescribe_date, 
            tp.dosage, 
            tp.suggestion,
            tp.patient_user_id
        FROM TreatmentPlan tp
        JOIN Patient p ON tp.patient_user_id = p.user_id
        WHERE tp.doctor_user_id = ?
        ORDER BY tp.prescribe_date DESC";

$stmt = $con->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $treatmentPlans[$row['patient_user_id']]['patient_name'] = $row['first_name'] . ' ' . $row['last_name'];
            $treatmentPlans[$row['patient_user_id']]['plans'][] = [
                'trtplan_id' => $row['trtplan_id'],
                'prescribe_date' => $row['prescribe_date'],
                'dosage' => $row['dosage'],
                'suggestion' => $row['suggestion']
            ];
        }
    }
    $stmt->close();
}

$con->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Doctor Dashboard</title>
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
        <h3 class="text-center">Welcome <?php echo htmlspecialchars($doctor['first_name']); ?></h3>

        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-4" style="max-width:18%;margin-top: 3%;">
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" href="#list-dash" data-toggle="list">Dashboard</a>
                    <a class="list-group-item list-group-item-action" href="#list-profile" data-toggle="list">Update Profile</a>
                    <a class="list-group-item list-group-item-action" href="#list-appt" data-toggle="list">Appointments</a>
                    <a class="list-group-item list-group-item-action" href="#list-patients" data-toggle="list">Ongoing Patients</a>
                    <a class="list-group-item list-group-item-action" href="#list-tests" data-toggle="list">Test Results</a>
                    <a class="list-group-item list-group-item-action" href="#list-trtplans" data-toggle="list">Treatment Plans</a>
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
                                                <p><strong>User ID:</strong> <?php echo htmlspecialchars($doctor['user_id']); ?></p>
                                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['phone']); ?></p>
                                                <p><strong>Gender:</strong> <?php echo htmlspecialchars($doctor['gender']); ?></p>
                                                <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
                                                <p><strong>Availability:</strong> <?php echo htmlspecialchars($doctor['availability']); ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></p>
                                                <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
                                                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($doctor['dob']); ?></p>
                                                <p><strong>Salary:</strong> <?php echo htmlspecialchars($doctor['salary']); ?></p>
                                                <p><strong>Fees:</strong> <?php echo htmlspecialchars($doctor['doc_fee']); ?></p>

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
                                    <input type="text" class="form-control" value="<?= $doctor['user_id'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>First Name:</label>
                                    <input type="text" class="form-control" value="<?= $doctor['first_name'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Last Name:</label>
                                    <input type="text" class="form-control" value="<?= $doctor['last_name'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Gender:</label>
                                    <select class="form-control" name="gender">
                                        <option value="Male" <?= isset($doctor['gender']) && $doctor['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= isset($doctor['gender']) && $doctor['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= isset($doctor['gender']) && $doctor['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Email:</label>
                                    <input type="email" name="email" class="form-control" value="<?= $doctor['email'] ?? '' ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Phone:</label>
                                    <input type="text" name="phone" class="form-control" value="<?= $doctor['phone'] ?? '' ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Date of Birth:</label>
                                    <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($doctor['dob'] ?? '') ?>">
                                </div>
                            </div>

                            <h4 class="mt-4">Professional Information</h4>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Salary:</label>
                                    <input type="text" class="form-control " value="<?= $doctor['salary'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Specialization:</label>
                                    <select name="specialization" class="form-control">
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?= $dept ?>" <?= ($doctor['specialization'] ?? '') == $dept ? 'selected' : '' ?>><?= $dept ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Doctor Fee:</label>
                                    <input type="text" name="doc_fee" class="form-control" value="<?= $doctor['doc_fee'] ?? '' ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Availability:</label>
                                    <input type="text" name="availability" class="form-control" value="<?= $doctor['availability'] ?? '' ?>">
                                </div>
                            </div>
                        </form>

                        <br>
                        <div class="text-left">
                            <button type="submit" name="update_doctor" class="btn btn-primary">Update</button>
                        </div>
                        </form>
                    </div>

                    <!-- Appointments -->
                    <div class="tab-pane fade" id="list-appt">
                        <h4>Appointments</h4>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Patient</th>
                                    <th>Gender</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Update Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($appointments) > 0): ?>
                                    <?php foreach ($appointments as $index => $appointment): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']) ?></td>
                                            <td><?= htmlspecialchars($appointment['patient_gender']) ?></td>
                                            <td><?= htmlspecialchars($appointment['appt_date']) ?></td>
                                            <td><?= htmlspecialchars($appointment['appt_time']) ?></td>
                                            <td>
                                                <?php
                                                $status_class = '';
                                                switch ($appointment['appt_status']) {
                                                    case 'Scheduled':
                                                        $status_class = 'badge-warning';
                                                        break;
                                                    case 'Ongoing':
                                                        $status_class = 'badge-info';
                                                        break;
                                                    case 'Completed':
                                                        $status_class = 'badge-success';
                                                        break;
                                                    case 'Cancelled':
                                                        $status_class = 'badge-danger';
                                                        break;
                                                    case 'Missed':
                                                        $status_class = 'badge-secondary';
                                                        break;
                                                    default:
                                                        $status_class = 'badge-secondary';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $status_class ?>"><?= htmlspecialchars($appointment['appt_status']) ?></span>
                                            </td>
                                            <td>
                                                <form method="POST">
                                                    <input type="hidden" name="appt_id" value="<?= htmlspecialchars($appointment['appt_id']) ?>">
                                                    <select class="form-control form-control-sm" name="appt_status">
                                                        <option value="Scheduled" <?= $appointment['appt_status'] == 'Scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                                        <option value="Ongoing" <?= $appointment['appt_status'] == 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                                        <option value="Completed" <?= $appointment['appt_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                                        <option value="Cancelled" <?= $appointment['appt_status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                        <option value="Missed" <?= $appointment['appt_status'] == 'Missed' ? 'selected' : '' ?>>Missed</option>
                                                    </select>
                                            </td>
                                            <td>
                                                <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">No appointments found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Ongoing Patients -->
                    <div class="tab-pane fade" id="list-patients">
                        <h4>Ongoing Patients</h4>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Blood Group</th>
                                    <th>Allergy</th>
                                    <th>Preconditions</th>
                                    <th>Order Test</th>
                                    <th>Treatment Plan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($ongoingPatients) > 0): ?>
                                    <?php foreach ($ongoingPatients as $index => $patient): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
                                            <td>
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
                                            <td><?= htmlspecialchars($patient['blood_group'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($patient['allergies'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($patient['pre_conditions'] ?? 'N/A') ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm order-form-btn"
                                                    data-patient-id="<?= htmlspecialchars($patient['user_id']) ?>">
                                                    Order
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm treatment-form-btn"
                                                    data-patient-id="<?= htmlspecialchars($patient['user_id']) ?>">
                                                    Prescribe
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No ongoing patients.</td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- Test Results -->
                    <div class="tab-pane fade" id="list-tests">
                        <h4>Test Results</h4>
                        <input type="text" class="form-control mb-2 form-control-sm" style="width: 40%;" placeholder="Search by Patient Name">
                        <table class="table table-hover" id="testResultsTable">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 20%;">Patient</th>
                                    <th style="width: 20%;">Test Name</th>
                                    <th style="width: 20%;">Performed Date</th>
                                    <th style="width: 20%;">Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($patientTests) > 0): ?>
                                    <?php $index = 1;
                                    foreach ($patientTests as $patientId => $patientData): ?>
                                        <tr data-tests='<?= json_encode($patientData['tests']) ?>'>
                                            <td><?= $index++ ?></td>
                                            <td><?= htmlspecialchars($patientData['patient_name']) ?></td>

                                            <td>
                                                <select class="form-control test-select">
                                                    <option value="">Select Date</option>
                                                    <?php foreach ($patientData['tests'] as $test): ?>
                                                        <option value="<?= htmlspecialchars($test['test_name']) ?>">
                                                            <?= htmlspecialchars($test['test_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="test-date">
                                            </td>
                                            <td class="test-result"></td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">No test results found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Treatment Plans -->
                    <div class="tab-pane fade" id="list-trtplans">
                        <h4>Treatment Plans</h4>
                        <input type="text" class="form-control mb-2 form-control-sm" style="width: 40%;" placeholder="Search by Patient Name">
                        <table class="table table-hover" id="treatmentPlanTable">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 20%;">Patient</th>
                                    <th style="width: 20%;">Prescribed Date</th>
                                    <th style="width: 20%;">Dosage</th>
                                    <th style="width: 20%;">Suggestion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($treatmentPlans) > 0): ?>
                                    <?php $index = 1;
                                    foreach ($treatmentPlans as $patientId => $patientData): ?>
                                        <tr data-plans='<?= json_encode($patientData['plans']) ?>'>
                                            <td><?= $index++ ?></td>
                                            <td><?= htmlspecialchars($patientData['patient_name']) ?></td>
                                            <td>
                                                <select class="form-control plan-select">
                                                    <option value="">Select Date</option>
                                                    <?php foreach ($patientData['plans'] as $plan): ?>
                                                        <option value="<?= htmlspecialchars($plan['trtplan_id']) ?>">
                                                            <?= htmlspecialchars($plan['prescribe_date'] ?? 'Not Yet Prescribed') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td class="dosage"></td>
                                            <td class="suggestion"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">No treatment plans found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        //JavaScript for Test Tab
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[placeholder="Search by Patient Name"]');
            const table = document.querySelector('#testResultsTable');
            if (!searchInput || !table) return; // Prevent errors if elements are missing

            const rows = Array.from(table.querySelectorAll('tbody tr'));

            // Search Functionality
            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                rows.forEach(row => {
                    const patientName = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase();
                    row.style.display = patientName.includes(searchText) ? '' : 'none';
                });
            });

            // Test Selection Functionality
            table.addEventListener('change', function(event) {
                if (!event.target.classList.contains('test-select')) return; // Ensure event is triggered by select

                const select = event.target;
                const row = select.closest('tr');
                if (!row || !row.dataset.tests) return; // Ensure row and data exist

                let tests;
                try {
                    tests = JSON.parse(row.dataset.tests);
                } catch (e) {
                    console.error("Invalid JSON in dataset.tests", e);
                    return;
                }

                const selectedTest = tests.find(test => test.test_name === select.value);
                if (selectedTest) {
                    row.querySelector('.test-date').textContent = selectedTest.test_date || 'Not Yet Performed';
                    row.querySelector('.test-result').textContent = selectedTest.result || 'Pending';
                } else {
                    row.querySelector('.test-date').textContent = '';
                    row.querySelector('.test-result').textContent = '';
                }
            });
        });

        //JavaScript for Treatmentplan Tab
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('#list-trtplans input[placeholder="Search by Patient Name"]');
            const table = document.querySelector('#treatmentPlanTable');

            if (!searchInput || !table) return; // Prevents errors if elements are missing

            const rows = Array.from(table.querySelectorAll('tbody tr')); // Store rows in an array

            // Search Functionality: Filters only by Patient Name (2nd column)
            searchInput.addEventListener('input', function() {
                const searchText = this.value.toLowerCase();
                rows.forEach(row => {
                    const patientNameCell = row.querySelector('td:nth-child(2)');
                    if (patientNameCell) {
                        const patientName = patientNameCell.textContent.toLowerCase();
                        row.style.display = patientName.includes(searchText) ? '' : 'none';
                    }
                });
            });

            // Handle Dropdown Change for Treatment Plan
            table.addEventListener('change', function(event) {
                if (!event.target.classList.contains('plan-select')) return; // Ensure event is triggered by select

                const select = event.target;
                const row = select.closest('tr');
                if (!row || !row.dataset.plans) return; // Ensure row and data exists

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
                    row.querySelector('.dosage').innerHTML = '';
                    row.querySelector('.suggestion').innerHTML = '';
                }
            });

            // Function to escape HTML for security
            function htmlspecialchars(str) {
                return str.replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
        });
    </script>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>