<?php
include 'config.php';
include 'nurse_func.php';

// Redirect if not logged in; security measure.
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

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
                                                <p><strong>Department:</strong> <?php echo htmlspecialchars($departmentDetails['dept_name']); ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($nurse['first_name'] . ' ' . $nurse['last_name']); ?></p>
                                                <p><strong>Email:</strong> <?php echo htmlspecialchars($nurse['email']); ?></p>
                                                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($nurse['dob']); ?></p>
                                                <p><strong>Salary:</strong> <?php echo htmlspecialchars($nurse['salary']); ?></p>
                                                <p><strong>Head of department:</strong> <?php echo htmlspecialchars($departmentDetails['head_name']); ?></p> 

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
                                    <input type="text" class="form-control" value="<?= $nurse['gender'] ?? '' ?>" disabled>
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
                                    <input type="text" name="duty_hour" class="form-control  " value="<?= $nurse['duty_hour'] ?? '' ?>" disabled>
                                </div>
                            </div>
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
                                    // Loop through each patient's information.
                                    foreach ($patientInfo as $patient): ?>
                                        <tr>
                                            <td><?= $index++ ?></td>
                                            <td><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
                                            <td><?= htmlspecialchars($patient['gender']) ?></td>
                                            <td data-age="<?php // Calculate and store patient's age as a data attribute.
                                                            if (!empty($patient['dob'])) {
                                                                $dob = new DateTime($patient['dob']);
                                                                $today = new DateTime();
                                                                $age = $today->diff($dob)->y;
                                                                echo $age;
                                                            } else {
                                                                echo 'N/A';
                                                            }
                                                            ?>">
                                                <?php  // Display patient's age.
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
                    <!-- JavaScript for Patient Overview -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Get filter elements and table body.
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
                                        ageMatch = (ageFilterValue === '51+') ? parseInt(age) >= 51 :
                                            (parseInt(age) >= ageFilterValue.split('-').map(Number)[0] &&
                                                parseInt(age) <= ageFilterValue.split('-').map(Number)[1]);
                                    }

                                    if (bloodGroupFilterValue) {
                                        bloodGroupMatch = bloodGroup === bloodGroupFilterValue;
                                    }

                                    row.style.display = (nameMatch && ageMatch && bloodGroupMatch) ? '' : 'none';
                                });
                            }

                            // Add event listeners for filtering.
                            patientSearchInput.addEventListener('keyup', filterPatients);
                            ageFilterSelect.addEventListener('change', filterPatients);
                            bloodGroupFilterSelect.addEventListener('change', filterPatients);
                        });
                    </script>
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
                                    // Loop through each patient's treatment plans.
                                    foreach ($treatmentPlans as $patientId => $patientData): ?>
                                        <!-- Loop through each doctor associated with the patient. -->
                                        <?php foreach ($patientData['doctors'] as $doctorName => $doctorPlans): ?>
                                            <tr data-plans='<?= json_encode($doctorPlans) ?>' data-patient-name='<?= htmlspecialchars($patientData['patient_name']) ?>' data-doctor-name='<?= htmlspecialchars($doctorName) ?>'>
                                                <td><?= $index++ ?></td>
                                                <td><?= htmlspecialchars($patientData['patient_name']) ?></td>
                                                <td><?= htmlspecialchars($doctorName) ?></td>
                                                <td> <!-- Table row with plan data, patient name, and doctor name as data attributes for JavaScript access. -->
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
                    <!-- JavaScript for Patient Medical Details -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Patient and Doctor Name Filters
                            const patientNameFilter = document.getElementById('patientNameFilter');
                            const doctorNameFilter = document.getElementById('doctorNameFilter');

                            // Filter on patient/doctor name input change.
                            patientNameFilter.addEventListener('keyup', filterTable);
                            doctorNameFilter.addEventListener('keyup', filterTable);

                            // Date Selection Dropdown Change
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
                                        row.querySelector('.dosage').innerHTML = selectedPlan.dosage ? htmlspecialchars(selectedPlan.dosage) : '<span class="text-muted">No Dosage Given</span>';
                                        row.querySelector('.suggestion').innerHTML = selectedPlan.suggestion ? htmlspecialchars(selectedPlan.suggestion) : '<span class="text-muted">No Suggestion</span>';
                                    } else {
                                        row.querySelector('.dosage').innerHTML = '<span class="text-muted">No Dosage Given</span>';
                                        row.querySelector('.suggestion').innerHTML = '<span class="text-muted">No Suggestion</span>';
                                    }
                                }
                            });

                            // Function to filter table rows based on patient and doctor names.
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

                            // Function to escape HTML for security.
                            function htmlspecialchars(str) {
                                if (typeof(str) == "string") {
                                    str = str.replace(/&/g, "&amp;").replace(/"/g, "&quot;").replace(/'/g, "&#039;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
                                }
                                return str;
                            }
                        });
                    </script>
                    <!-- Perform Tests -->
                    <div class="tab-pane fade" id="list-performtest">
                        <div class="test-details-section">
                            <div class="row">
                                <div class="col-md-4 filter-group">
                                    <label for="patientNameFilterNurse">Filter by Patient Name:</label>
                                    <input type="text" class="form-control form-control-sm" id="patientNameFilterNurse" placeholder="Enter Name">
                                </div>
                            </div>
                        </div>
                        <form id="nurseTestForm" method="post" action="perform_test.php">
                            <table class="table table-hover" id="nurseTestsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 20%;">Patient Name</th>
                                        <th style="width: 20%;">Prescribed Date</th>
                                        <th style="width: 20%;">Test Name</th>
                                        <th style="width: 15%;">Test Date</th>
                                        <th style="width: 25%;">Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($tests) > 0): ?>
                                        <?php $index = 1;
                                        // Loop through each test record.
                                        foreach ($tests as $test): ?>
                                            <tr data-patient-id="<?= $test['patient_user_id'] ?>" data-test-id="<?= $test['test_id'] ?>">
                                                <td><?= $index++ ?></td>
                                                <!--  Table row with patient and test IDs as data attributes for JavaScript access. -->
                                                <td><?= htmlspecialchars($test['patient_first_name'] . ' ' . $test['patient_last_name']) ?></td>
                                                <td><?= htmlspecialchars($test['pres_date']) ?></td>
                                                <td><?= htmlspecialchars($test['test_name']) ?></td>
                                                <td>
                                                    <input type="date" class="form-control test-date-input" name="test_date[]">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control test-result-input" name="result[]">
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5">No tests found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <button style="margin-bottom: 20px;" type="submit" class="btn btn-primary">Submit Results</button>
                        </form>
                    </div>
                    <!-- JavaScript for Perform Tests -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Patient Name Filter
                            const patientNameFilterNurse = document.getElementById('patientNameFilterNurse');
                            patientNameFilterNurse.addEventListener('keyup', function() {
                                const filter = patientNameFilterNurse.value.toLowerCase();
                                const rows = document.querySelectorAll('#nurseTestsTable tbody tr');
                                rows.forEach(row => {
                                    const patientName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                                    row.style.display = patientName.includes(filter) ? '' : 'none';
                                });
                            });

                            // Form Submission
                            const nurseTestForm = document.getElementById('nurseTestForm');
                            nurseTestForm.addEventListener('submit', function(event) {
                                event.preventDefault(); // Prevent default form submission
                                const rows = document.querySelectorAll('#nurseTestsTable tbody tr');
                                const testData = [];
                                rows.forEach(row => {
                                    const patientId = row.dataset.patientId;
                                    const testId = row.dataset.testId;
                                    const testDate = row.querySelector('.test-date-input').value;
                                    const result = row.querySelector('.test-result-input').value;
                                    if (testDate && result) {
                                        testData.push({
                                            patientId,
                                            testId,
                                            testDate,
                                            result
                                        });
                                    }
                                });

                                // Send data to PHP for processing
                                fetch('perform_test.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify(testData),
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Test results submitted successfully!');
                                            location.reload();
                                        } else {
                                            alert('Error submitting test results.');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('An error occurred.');
                                    });
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>