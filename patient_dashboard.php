<?php include 'config.php';
include 'patient_func.php';
// Handle AJAX cancellation requests
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cancel_appt_id'])) {
    $appt_id = intval($_POST['cancel_appt_id']);
    $response = cancel_appointment($appt_id);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;  // Stop further processing so no HTML is output
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <title>Patient Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- External Stylesheets -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
</head>

<body style="padding-top: 50px;">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <a class="navbar-brand" href="#">
            <i class="fa fa-hospital-o"></i> Hospital Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
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
        <h3 class="text-center">Welcome <?php echo htmlspecialchars($patient['first_name']); ?></h3>

        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-4" style="max-width:25%; margin-top: 3%">
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-dash-list" data-toggle="list" href="#list-dash" role="tab" aria-controls="list-dash">Dashboard</a>
                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="list-profile">Update Profile</a>
                    <a class="list-group-item list-group-item-action" id="list-appt-list" data-toggle="list" href="#list-appt" role="tab" aria-controls="list-appt">Book Appointments</a>
                    <a class="list-group-item list-group-item-action" id="list-apptHis-list" data-toggle="list" href="#list-apptHis" role="tab" aria-controls="list-apptHis">Appointment History</a>
                    <a class="list-group-item list-group-item-action" id="list-trtPlan-list" data-toggle="list" href="#list-trtPlan" role="tab" aria-controls="list-trtPlan">Treatment Plans</a>
                    <a class="list-group-item list-group-item-action" id="list-test-list" data-toggle="list" href="#list-test" role="tab" aria-controls="list-test">Pending Tests</a>
                    <a class="list-group-item list-group-item-action" id="list-result-list" data-toggle="list" href="#list-result" role="tab" aria-controls="list-result">Test Results</a>
                    <a class="list-group-item list-group-item-action" id="list-bill-list" data-toggle="list" href="#list-bill" role="tab" aria-controls="list-bill">Pay Bill</a>
                </div><br>
            </div>

            <!-- Main Content -->
            <div class="col-md-8" style="margin-top: 3%;">
                <div class="tab-content" id="nav-tabContent" style="width: 950px;">
                    <!-- Dashboard -->
                    <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                        <div class="container-fluid bg-white p-4">
                            <div class="row">
                                <!-- Patient Profile -->
                                <div class="col-12">
                                    <!-- First Row: Personal Details -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <p><strong>User ID:</strong> <?php echo htmlspecialchars($patient['user_id']); ?></p>
                                            <p><strong>First Name:</strong> <?php echo htmlspecialchars($patient['first_name']); ?></p>
                                            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($patient['last_name']); ?></p>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></p>
                                            <p><strong>Contact 1:</strong> <?php echo htmlspecialchars($patient['phno1']); ?></p>
                                            <p><strong>Contact 2:</strong> <?php echo htmlspecialchars($patient['phno2']); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></p>
                                            <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($patient['blood_group']); ?></p>
                                            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient['dob']); ?></p>
                                            <p><strong>Allergies:</strong> <?php echo $medHistory ? htmlspecialchars($medHistory['allergies']) : ''; ?></p>
                                            <p><strong>Preconditions:</strong> <?php echo $medHistory ? htmlspecialchars($medHistory['pre_conditions']) : ''; ?></p>

                                        </div>
                                    </div>

                                    <!-- Second Row: Address Details -->
                                    <div class="row border-top pt-3">
                                        <div class="col-md-2">
                                            <p><strong>House No:</strong> <?php echo htmlspecialchars($patient['hno']); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Street:</strong> <?php echo htmlspecialchars($patient['street']); ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>City:</strong> <?php echo htmlspecialchars($patient['city']); ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>ZIP:</strong> <?php echo htmlspecialchars($patient['zip']); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Country:</strong> <?php echo htmlspecialchars($patient['country']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Profile Tab -->
                    <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                        <div class="container-fluid bg-white p-4">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="mb-4">Update Patient Profile</h4>

                                        <!-- Personal Information -->
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <label>User ID:</label>
                                                <input type="text" class="form-control" value="<?= $patient['user_id'] ?>" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label>First Name:</label>
                                                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($patient['first_name']) ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Last Name:</label>
                                                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($patient['last_name']) ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Email:</label>
                                                <input type="email" name="email" class="form-control" value="<?= $patient['email'] ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <label>Contact 1:</label>
                                                <input type="tel" name="phno1" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?= isset($phoneNumbers[0]) ? htmlspecialchars($phoneNumbers[0]) : '' ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <label>Contact 2:</label>
                                                <input type="tel" name="phno2" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?= isset($phoneNumbers[0]) ? htmlspecialchars($phoneNumbers[1]) : '' ?>">
                                            </div>
                                        </div>

                                        <!-- Medical Information -->
                                        <div class="row mb-4">
                                            <div class="col-md-2">
                                                <label>Gender:</label>
                                                <input type="text" name="gender" class="form-control" value="<?= htmlspecialchars($patient['gender']) ?>" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Blood Group:</label>
                                                <select name="blood_group" class="form-control">
                                                    <option value="A+" <?= $patient['blood_group'] == 'A+' ? 'selected' : '' ?>>A+</option>
                                                    <option value="A-" <?= $patient['blood_group'] == 'A-' ? 'selected' : '' ?>>A-</option>
                                                    <option value="B+" <?= $patient['blood_group'] == 'B+' ? 'selected' : '' ?>>B+</option>
                                                    <option value="B-" <?= $patient['blood_group'] == 'B-' ? 'selected' : '' ?>>B-</option>
                                                    <option value="AB+" <?= $patient['blood_group'] == 'AB+' ? 'selected' : '' ?>>AB+</option>
                                                    <option value="AB-" <?= $patient['blood_group'] == 'AB-' ? 'selected' : '' ?>>AB-</option>
                                                    <option value="O+" <?= $patient['blood_group'] == 'O+' ? 'selected' : '' ?>>O+</option>
                                                    <option value="O-" <?= $patient['blood_group'] == 'O-' ? 'selected' : '' ?>>O-</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Date of Birth:</label>
                                                <input type="date" name="dob" class="form-control" value="<?= $patient['dob'] ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Allergies:</label>
                                                <input type="text" name="allergies" class="form-control" value="<?= ($medHistory['allergies'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Preconditions:</label>
                                                <input type="text" name="preconditions" class="form-control" value="<?= ($medHistory['pre_conditions'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <!-- Address Information -->
                                        <div class="row mb-4">
                                            <div class="col-md-2">
                                                <label>House No:</label>
                                                <input type="text" name="hno" class="form-control" value="<?= $patient['hno'] ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Street:</label>
                                                <input type="text" name="street" class="form-control" value="<?= $patient['street'] ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>City:</label>
                                                <input type="text" name="city" class="form-control" value="<?= $patient['city'] ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <label>ZIP:</label>
                                                <input type="text" name="zip" class="form-control" value="<?= $patient['zip'] ?>">
                                            </div>
                                            <div class="col-md-2"> <!-- Changed from col-md-1 -->
                                                <label>Country:</label>
                                                <input type="text" name="country" class="form-control"
                                                    value="<?= $patient['country'] ?>"
                                                    style="min-width: 150px;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Update Button -->
                                    <div class="row mt-4">
                                        <div class="col-md-12 text-right">
                                            <button type="submit" name="update_patient" class="btn btn-primary">
                                                <i class="fa fa-pencil"></i> Update Profile
                                            </button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>

                    <!-- Book Appointments Tab -->
                    <div class="tab-pane fade" id="list-appt" role="tabpanel" aria-labelledby="list-appt-list">
                        <div class="container-fluid">
                            <div class="card">
                                <div class="card-body">
                                    <center>
                                        <h4>Book Appointment</h4>
                                    </center><br>
                                    <form id="bookApptForm" class="form-group" method="post" action="patient_dashboard.php">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="spec">Specialization</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select name="spec" class="form-control" id="spec" required>
                                                    <option value="" disabled selected>Select Specialization</option>
                                                    <?php display_specs() ?>
                                                </select>
                                            </div>
                                            <br><br>

                                            <script>
                                                document.getElementById('spec').onchange = function () {
                                                    let spec = this.value;
                                                    console.log(spec);
                                                    let doctorSelect = document.getElementById('doctor');
                                                    let docs = [...doctorSelect.options];
                                                    // Reset all doctor options first, then hide those that don't match.
                                                    docs.forEach((option) => {
                                                        option.style.display = "";
                                                        if (option.getAttribute("data-spec") !== spec) {
                                                            option.style.display = "none";
                                                        }
                                                    });
                                                    // Reset the doctor select element to the default option
                                                    doctorSelect.selectedIndex = 0;
                                                };
                                            </script>

                                            <div class="col-md-4"><label for="doctor">Doctors:</label></div>
                                            <div class="col-md-8">
                                                <select name="doctor" class="form-control" id="doctor" required>
                                                    <option value="" disabled selected>Select Doctor</option>
                                                    <?php display_docs(); ?>
                                                </select>
                                            </div>
                                            <br><br>

                                            <script>
                                                document.getElementById('doctor').onchange = function updateFees(e) {
                                                    var selectedOption = this.options[this.selectedIndex];
                                                    var fee = selectedOption.getAttribute('data-fee');
                                                    var avail = selectedOption.getAttribute('data-availability');
                                                    var id = selectedOption.getAttribute('data-id');
                                                    document.getElementById('docFees').value = fee;
                                                    document.getElementById('availibility').value = avail;
                                                    document.getElementById('docId').value = id;
                                                }
                                            </script>

                                            <!-- <div class="col-md-4">
                                                <label for="consultancyfees">Doctor ID</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="docId" id="docId" readonly="readonly" />
                                            </div> -->
                                            <input type="hidden" name="docId" id="docId">

                                            <div class="col-md-4">
                                                <label for="consultancyfees">Consultancy Fees</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="docFees" id="docFees" readonly="readonly" />
                                            </div>
                                            <br><br>
                                            <div class="col-md-4">
                                                <label for="availibility">Available Schedule</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="availibility" id="availibility" readonly="readonly" />
                                            </div>
                                            <br><br>
                                            <div class="col-md-4"><label>Appointment Date</label></div>
                                            <div class="col-md-8"><input type="date" class="form-control datepicker" name="appdate" required></div><br><br>

                                            <div class="col-md-4"><label>Appointment Time</label></div>
                                            <div class="col-md-8"><input type="time" class="form-control timepicker" name="appointmentTime" required></div><br><br>

                                            <div class="col-md-4">
                                                <input type="submit" name="app-submit" value="Create New Appointment" class="btn btn-primary" id="inputbtn">
                                            </div>
                                            <div class="col-md-8"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><br>
                    </div>
                    <!-- Appointment History Tab -->
                     <div class="tab-pane fade" id="list-apptHis" role="tabpanel" aria-labelledby="list-apptHis-list">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Doctor</th>
                                    <th scope="col">Specialization</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php display_appointment_history(); ?>
                            </tbody>

                        </table>

                     </div>
                    <!-- Treatment Plans Tab -->
                     <div class="tab-pane fade" id="list-trtPlan" role="tabpanel" aria-labelledby="list-trtPlan-list">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered">
                                <thead>
                                    <th scope="col">#</th>
                                    <th scope="col">Prescribed Date</th>
                                    <th scope="col">Doctor Name</th>
                                    <th scope="col">Specialization</th>                                
                                    <th scope="col">Dosages</th>
                                    <th scope="col">Suggestions</th>                                
                                </thead>
                                <tbody> <?php display_treatment_plans(); ?> </tbody>                            
                            </table>
                        </div>
                     </div>
                    <!-- Pending Tests Tab -->
                    <div class="tab-pane fade" id="list-test" role="tabpanel" aria-labelledby="list-test-list">
                        <!-- ... pending tests content ... -->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Test Name</th>
                                    <th scope="col">Doctor Name</th>
                                    <th scope="col">Specialization</th>
                                    <th scope="col">Prescribed Date</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                display_pending_tests();
                                ?>
                            </tbody>

                        </table>
                    </div>

                    <!-- Test Results Tab -->
                    <div class="tab-pane fade" id="list-result" role="tabpanel" aria-labelledby="list-result-list">
                        <!-- <h4>Test Results</h4> -->
                        <div class="row">
                            <div class="col-md-4 filter-group">
                                <label for="testNameFilter">Filter by Test Name:</label>
                                <input type="text" class="form-control form-control-sm" id="testNameFilter" placeholder="Enter Test Name">
                            </div>
                            <div class="col-md-4 filter-group">
                                <label for="testDateFilter">Filter by Test Date:</label>
                                <input type="date" class="form-control form-control-sm" id="testDateFilter" placeholder="Enter Test Date">
                            </div>
                        </div><br>

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Test Name</th>
                                    <th scope="col">Test Date</th>
                                    <th scope="col">Test Result</th>
                                    <th scope="col">Doctor Name</th>
                                    <th scope="col">Specialization</th>
                                    <th scope="col">Prescribed Date</th>
                                </tr>
                            </thead>
                            <tbody id="testResultsBody">
                                <?php display_test_results(); ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pay Bill -->
                    <div class="tab-pane fade" id="list-bill" role="tabpanel" aria-labelledby="list-bill-list">
                        <div class="container-fluid py-3">
                            <!-- Bill Breakdown -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Bill Breakdown</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Bill Type</th>                                                    
                                                    <th>Doctor Name</th>
                                                    <th>Test Name</th>
                                                    <th>Bill Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $subtotal = display_due_bills_and_subtotal(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Container -->
                            <div class="card mt-4">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <!-- Subtotal or Amount Due -->
                                    <h5 class="mb-0">Subtotal: <span class="text-primary">$<?= htmlspecialchars($subtotal) ?></span></h5>
                                    <!-- Action Buttons -->
                                    <div>
                                        <button id="payNowBtn" class="btn btn-primary mr-2">
                                            <i class="fa fa-credit-card"></i> Pay Now
                                        </button>
                                        <button id="downloadInvoiceBtn" class="btn btn-success">
                                            <i class="fa fa-download"></i> Download Invoice
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.10.1/sweetalert2.all.min.js">
    </script>
    <script>
        // Initialize Bootstrap tabs PROPERLY
        $(document).ready(function() {
            $('#list-tab a').tab();
        });
    </script>

    <!-- Test result search filter -->
    <script>
    // Wait until the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get references to the filter fields and table body
        const testNameFilter = document.getElementById("testNameFilter");
        const testDateFilter = document.getElementById("testDateFilter");
        const tableBody = document.getElementById("testResultsBody");

        // Attach event listeners to filter fields
        testNameFilter.addEventListener("input", filterRows);
        testDateFilter.addEventListener("input", filterRows); // "input" works for type=date in modern browsers

        function filterRows() {
            // Get current filter values (trim and lowercase for text)
            const nameFilter = testNameFilter.value.toLowerCase().trim();
            const dateFilter = testDateFilter.value.trim(); // Expected in YYYY-MM-DD format

            // Iterate over each row in the table body
            const rows = tableBody.querySelectorAll("tr");
            rows.forEach(row => {
                // Assuming the table columns are as follows:
                // 0: counter, 1: Test Name, 2: Test Date, 3: Test Result, 4: Doctor Name, 5: Specialization, 6: Prescribed Date
                const testNameCell = row.children[1].textContent.toLowerCase();
                const testDateCell = row.children[2].textContent.trim();

                // Check if row matches the filters:
                // For test name, use a partial match.
                // For test date, check for an exact match (if a date is provided).
                const matchName = nameFilter === "" || testNameCell.includes(nameFilter);
                const matchDate = dateFilter === "" || testDateCell === dateFilter;

                // If both conditions are met, show the row; otherwise hide it.
                row.style.display = (matchName && matchDate) ? "" : "none";
            });
        }
    });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('payNowBtn');
            if(payButton){
                payButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Use the Fetch API to call the endpoint
                    fetch('pay_bill.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'action=pay_bill'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // You can use SweetAlert2 or a simple alert
                            alert("Bill is paid successfully!");
                            // Optionally, reload the page to update the bill list
                            location.reload();
                        } else {
                            alert("Error: " + (data.error || "Payment could not be processed."));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert("There was a problem processing your payment.");
                    });
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const downloadInvoiceBtn = document.getElementById('downloadInvoiceBtn');
            if(downloadInvoiceBtn){
                downloadInvoiceBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Redirect to the PDF generation endpoint
                    window.location.href = 'download_invoice.php';
                });
            }
        });
    </script>
    <script>
        function cancelAppointment(apptId) {
            if (!confirm("Are you sure you want to cancel this appointment?")) {
                return;
            }
                
            // Use Fetch API to post to the current page (which will handle the cancellation)
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'cancel_appt_id=' + encodeURIComponent(apptId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Appointment cancelled successfully.");
                    // Optionally reload the page or update the table row to show the new status
                    location.reload();
                } else {
                    alert("Error: " + (data.error || "Could not cancel appointment."));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("There was a problem processing your request.");
            });
        }
        </script>
        <script>
        document.getElementById('bookApptForm').addEventListener('submit', function(e) {
            // Get the appointment date and time values.
            let apptDate = document.querySelector('input[name="appdate"]').value; // format: YYYY-MM-DD
            let apptTime = document.querySelector('input[name="appointmentTime"]').value; // format: HH:MM (24-hour)
            
            // Get the doctor's availability as a string (for example "Mon-Fri 9AM-5PM")
            let availability = document.getElementById('availibility').value;
            
            // Proceed only if availability is provided.
            if (availability.trim() !== "") {
                // 1. Determine allowed days.
                // Here, we check if the availability contains "Mon-Fri" and set allowed days accordingly.
                let allowedDays = [];
                if (availability.indexOf("Mon-Fri") !== -1) {
                    allowedDays = ["Mon", "Tue", "Wed", "Thu", "Fri"];
                } else {
                    // You could add more parsing logic here for other availability formats.
                    alert("Doctor's availability days not recognized.");
                    e.preventDefault();
                    return false;
                }
                
                // 2. Parse the allowed time range.
                // We expect something like "9AM-5PM". Use a regular expression to extract numbers and periods.
                let timeRangeMatch = availability.match(/(\d{1,2})(AM|PM)[\s-]+(\d{1,2})(AM|PM)/);
                if (!timeRangeMatch) {
                    alert("Doctor's availability time format not recognized.");
                    e.preventDefault();
                    return false;
                }
                
                let startHour = parseInt(timeRangeMatch[1]);
                let startPeriod = timeRangeMatch[2];
                let endHour = parseInt(timeRangeMatch[3]);
                let endPeriod = timeRangeMatch[4];
                
                // Convert start time and end time to 24-hour format.
                if (startPeriod === 'PM' && startHour < 12) {
                    startHour += 12;
                }
                if (startPeriod === 'AM' && startHour === 12) {
                    startHour = 0;
                }
                if (endPeriod === 'PM' && endHour < 12) {
                    endHour += 12;
                }
                if (endPeriod === 'AM' && endHour === 12) {
                    endHour = 0;
                }
                
                // Format allowed times as strings "HH:MM"
                let startTimeAllowed = ('0' + startHour).slice(-2) + ":00";
                let endTimeAllowed = ('0' + endHour).slice(-2) + ":00";
                
                // 3. Check if appointment date falls within allowed days.
                // Convert appointment date to a day abbreviation ("Mon", "Tue", etc.)
                let apptDay = new Date(apptDate).toString().slice(0, 3);
                if (!allowedDays.includes(apptDay)) {
                    e.preventDefault();
                    alert("The appointment date (" + apptDay + ") is not within the doctor's available working days (" + allowedDays.join(", ") + ").");
                    return false;
                }
                
                // 4. Check if appointment time is within allowed range.
                // Since apptTime is in 24-hour HH:MM format, we can do a simple string comparison.
                if (apptTime < startTimeAllowed || apptTime > endTimeAllowed) {
                    e.preventDefault();
                    alert("The appointment time (" + apptTime + ") is outside the doctor's available time range (" + startTimeAllowed + " to " + endTimeAllowed + ").");
                    return false;
                }
            }
            // If all checks pass, the form is submitted.
        });
        </script>

</body>

</html>