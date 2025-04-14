<?php
include 'config.php';
include 'doc_func.php';
include 'form_modal.php';

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
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    </style>
</head>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

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
                    <a class="nav-link" href="index.php"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a>
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
                    <a class="list-group-item list-group-item-action" href="#list-dept" data-toggle="list">HoD Access</a>
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
                                                <p><strong>Department:</strong> <?php echo htmlspecialchars($departmentDetails['dept_name']); ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']); ?></p>
                                                <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
                                                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($doctor['dob']); ?></p>
                                                <p><strong>Salary:</strong> <?php echo htmlspecialchars($doctor['salary']); ?></p>
                                                <p><strong>Fees:</strong> <?php echo htmlspecialchars($doctor['doc_fee']); ?></p>
                                                <p><strong>Head of Department:</strong> <?php echo htmlspecialchars($departmentDetails['head_name']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Department -->
                    <div class="tab-pane fade show" id="list-dept">
                        <table class="table table-hover" id="deptViewTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Staff Name</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th>Specialization</th>
                                    <th>Duty Hour</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                deptViewTable($doctor['user_id']);
                                ?>
                            </tbody>
                        </table>
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
                                    <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($doctor['first_name']) ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Last Name:</label>
                                    <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($doctor['last_name']) ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Gender:</label>
                                    <input type="text" class="form-control" value="<?= $doctor['gender'] ?? '' ?>" disabled>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Email:</label>
                                    <input type="email" name="email" class="form-control" value="<?= $doctor['email'] ?? '' ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Phone:</label>
                                    <input type="text" name="phone" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?= $doctor['phone'] ?? '' ?>">
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
                                    <input type="text" name="specialization" class="form-control " value="<?= $doctor['specialization'] ?? '' ?>">
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

                            <br>
                            <div class="text-left">
                                <button type="submit" name="update_doctor" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                    <!-- Appointments -->
                    <div class="tab-pane fade" id="list-appt">
                        <div class="row">
                            <div class="col-md-4 filter-group">
                                <label for="apptDateFilter">Filter by Appointment Date:</label>
                                <input type="date" class="form-control form-control-sm" id="apptDateFilter" placeholder="Enter Test Date">
                            </div>
                        </div><br>
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
                            <tbody id="appointments">
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
                                                    case 'Completed':
                                                        $status_class = 'badge-success';
                                                        break;
                                                    case 'Cancelled by Doctor':
                                                    case 'Cancelled by Patient':
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
                                                        <option value="Scheduled" <?= $appointment['appt_status'] == 'Scheduled' ? 'selected' : '' ?>>Select Status</option>
                                                        <option value="Completed" <?= $appointment['appt_status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                                        <option value="Cancelled by Doctor" <?= $appointment['appt_status'] == 'Cancelled by Doctor' ? 'selected' : '' ?>>Cancel</option>
                                                        <option value="Missed" <?= $appointment['appt_status'] == 'Missed' ? 'selected' : '' ?>>Missed</option>
                                                    </select>
                                            </td>
                                            <td>
                                                <?php if ($appointment['appt_status'] == 'Scheduled'): ?>
                                                    <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-sm btn-secondary" disabled>Update</button>
                                                <?php endif; ?>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan='6' class='text-center'>No appointments found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                    <!-- JavaScript for Appointment Date Filter -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Get date filter input and table body.
                            const apptDateFilter = document.getElementById("apptDateFilter");
                            const tableBody = document.getElementById("appointments");

                            // Filter rows on date input change.
                            apptDateFilter.addEventListener("input", filterRows);

                            function filterRows() {
                                const dateFilter = apptDateFilter.value.trim();
                                const rows = tableBody.querySelectorAll("tr");

                                rows.forEach(row => {
                                    // Get date from table cell.
                                    const testDateCell = row.children[3].textContent.trim();
                                    // Check for date match.
                                    const matchDate = dateFilter === "" || testDateCell === dateFilter;
                                    // Show/hide row based on match.
                                    row.style.display = (matchDate) ? "" : "none";
                                });
                            }
                        });
                    </script>
                    <!-- Ongoing Patients -->
                    <div class="tab-pane fade" id="list-patients">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Blood Group</th>
                                    <th>Allergy</th>
                                    <th>Preconditions</th>
                                    <th>Medical History</th>
                                    <th>Order Test</th>
                                    <th>Treatment Plan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($ongoingPatients) > 0): ?>
                                    <!-- Loop through each scheduled patient and show their details-->
                                    <?php foreach ($ongoingPatients as $index => $patient): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></td>
                                            <td>
                                                <?php
                                                // Calculate and display patient's age if date of birth is available.
                                                if (!empty($patient['dob'])) {
                                                    $dob = new DateTime($patient['dob']);
                                                    $today = new DateTime();
                                                    $age = $today->diff($dob)->y;
                                                    echo $age;
                                                } else {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </td> <!-- Display patient details, if not set show N/A -->
                                            <td><?= htmlspecialchars($patient['blood_group'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($patient['allergies'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($patient['pre_conditions'] ?? 'N/A') ?></td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm view-history-btn"
                                                    data-patient-id="<?= htmlspecialchars($patient['user_id']) ?>"
                                                    data-patient-name="<?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?>">
                                                    View History
                                                </button>
                                            </td>
                                            <td>
                                                <!-- Button to Order Test -->
                                                <button type="button" class="btn btn-info btn-sm order-form-btn"
                                                    data-patient-id="<?= htmlspecialchars($patient['user_id']) ?>"
                                                    data-patient-name="<?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?>">
                                                    Order
                                                </button>
                                            </td>
                                            <td>
                                                <!-- Button to Prescribe Treatmentplan -->
                                                <button type="button" class="btn btn-info btn-sm treatment-form-btn"
                                                    data-patient-id="<?= htmlspecialchars($patient['user_id']) ?>"
                                                    data-patient-name="<?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?>">
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
                        <!-- Patient History Modal -->
                        <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="historyModalLabel">Patient History</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- History content will be loaded here via AJAX -->
                                        <div id="historyContent">
                                            <p>Loading history...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Attach click event to the "View History" buttons
                                document.querySelectorAll('.view-history-btn').forEach(function(button) {
                                    button.addEventListener('click', function() {
                                        const patientId = this.getAttribute('data-patient-id');
                                        const patientName = this.getAttribute('data-patient-name'); // retrieve patient name

                                        // Update the modal title to include the patient name
                                        document.getElementById('historyModalLabel').textContent = "Patient History - " + patientName;
            
                                        // Show a loading message in the modal body
                                        document.getElementById('historyContent').innerHTML = "<p>Loading history...</p>";
                                        // Open the modal (Bootstrap 4 syntax)
                                        $('#historyModal').modal('show');

                                        // Fetch the patient history using AJAX
                                        fetch('get_patient_history.php?patient_id=' + encodeURIComponent(patientId)) //calling for history content
                                            .then(response => response.text())
                                            .then(data => {
                                                document.getElementById('historyContent').innerHTML = data;
                                            })
                                            .catch(error => {
                                                console.error('Error fetching patient history:', error);
                                                document.getElementById('historyContent').innerHTML = "<p class='text-danger'>Error loading history.</p>";
                                            });
                                    });
                                });
                            });
                        </script>
                    </div>
                    <!-- Test Results -->
                    <div class="tab-pane fade" id="list-tests">
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
                                    // Loop through each patient's test results, where $patientId is the patient's ID, 
                                    // and $patientData contains the patient's name and their test results.
                                    foreach ($patientTests as $patientId => $patientData): ?>
                                        <tr data-tests='<?= json_encode($patientData['tests']) ?>'>
                                            <!-- Display row number and increment counter and patient name -->
                                            <td><?= $index++ ?></td>
                                            <td><?= htmlspecialchars($patientData['patient_name']) ?></td>

                                            <td>
                                                <select class="form-control test-select">
                                                    <option value="">Select Test</option>
                                                    <!-- Loop through each test associated with the patient. -->
                                                    <?php foreach ($patientData['tests'] as $test): ?>
                                                        <option value="<?= htmlspecialchars($test['test_name']) ?>">
                                                            <!-- Display test names -->
                                                            <?= htmlspecialchars($test['test_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <!-- Placeholder for test date, populated by JavaScript -->
                                            <td class="test-date">
                                            </td>
                                            <!-- Placeholder for test result, populated by JavaScript -->
                                            <td class="test-result"></td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No test results found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- JavaScript for Test Tab -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Search input and table selection.
                            const searchInput = document.querySelector('input[placeholder="Search by Patient Name"]');
                            const table = document.querySelector('#testResultsTable');
                            if (!searchInput || !table) return;

                            const rows = Array.from(table.querySelectorAll('tbody tr'));

                            // Search functionality.
                            searchInput.addEventListener('input', function() {
                                const searchText = this.value.toLowerCase();
                                rows.forEach(row => {
                                    const patientName = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase();
                                    row.style.display = patientName.includes(searchText) ? '' : 'none';
                                });
                            });

                            // Test selection functionality.
                            table.addEventListener('change', function(event) {
                                if (!event.target.classList.contains('test-select')) return;
                                const select = event.target;
                                const row = select.closest('tr');
                                if (!row || !row.dataset.tests) return;

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
                    </script>
                    <!-- Treatment Plans -->
                    <div class="tab-pane fade" id="list-trtplans">
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
                                    // Loop through each patient's treatment plans, where $patientId is the patient's ID, 
                                    // and $patientData contains the patient's name and their treatment plans.
                                    foreach ($treatmentPlans as $patientId => $patientData): ?>
                                        <tr data-plans='<?= json_encode($patientData['plans']) ?>'>
                                            <!-- Display row number and increment counter and patient name -->
                                            <td><?= $index++ ?></td>
                                            <td><?= htmlspecialchars($patientData['patient_name']) ?></td>
                                            <td>
                                                <select class="form-control plan-select">
                                                    <option value="">Select Date</option>
                                                    <!-- Loop through each trtplan date associated with the patient. -->
                                                    <?php foreach ($patientData['plans'] as $plan): ?>
                                                        <option value="<?= htmlspecialchars($plan['trtplan_id']) ?>">
                                                            <?= htmlspecialchars($plan['prescribe_date'] ?? 'Not Yet Prescribed') ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <!-- Placeholder for dosage, populated by JavaScript -->
                                            <td class="dosage"></td>
                                            <!-- Placeholder for suggestion, populated by JavaScript -->
                                            <td class="suggestion"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No treatment plans found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- JavaScript for Treatment Plan Tab -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Search input and table selection.
                            const searchInput = document.querySelector('#list-trtplans input[placeholder="Search by Patient Name"]');
                            const table = document.querySelector('#treatmentPlanTable');
                            if (!searchInput || !table) return;

                            const rows = Array.from(table.querySelectorAll('tbody tr'));

                            // Search functionality.
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

                            // Treatment plan selection dropdown change.
                            table.addEventListener('change', function(event) {
                                if (!event.target.classList.contains('plan-select')) return;
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
                                    row.querySelector('.dosage').innerHTML = '';
                                    row.querySelector('.suggestion').innerHTML = '';
                                }
                            });

                            // HTML escaping function.
                            function htmlspecialchars(str) {
                                return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
                            }
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
</body>

</html>