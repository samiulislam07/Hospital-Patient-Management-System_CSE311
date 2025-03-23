<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$patient = [];

// Fetch patient details
$sql = "SELECT user_id, first_name, last_name, email, gender, blood_group, dob, hno, street, city, zip, country FROM Patient WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $patient = $result->fetch_assoc();
    }
    $stmt->close();
}

// Update Patient Profile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_patient'])) {
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $dob = $_POST['dob'];
    $hno = $_POST['hno'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $country = $_POST['country'];

    // Ensure patient ID is set
    if (!empty($patient_id)) {
        $update_sql = "UPDATE Patient SET email = ?, gender = ?, blood_group = ?, dob = ?, hno = ?, street = ?, city = ?, zip = ?, country = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        if ($stmt) {
            $stmt->bind_param("ssssssssss", $email, $gender, $blood_group, $dob, $hno, $street, $city, $zip, $country, $patient_id);
            if ($stmt->execute()) {
                echo "<script>alert('Profile updated successfully!'); window.location.href='patient_dashboard.php';</script>";
            } else {
                echo "<script>alert('Error updating profile. Please try again.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Database error. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Doctor ID missing. Cannot update profile.');</script>";
    }
}

// Fetch departments for dropdown
$departments = [];
$dept_sql = "SELECT * FROM Department";
$dept_result = $conn->query($dept_sql);
while($dept = $dept_result->fetch_assoc()) {
    $departments[] = $dept;
}

// Add this in the PHP section before HTML
$selected_doctors = [];
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_dept'])) {
    $dept_id = $_POST['selected_dept'];
    $sql = "SELECT d.user_id, d.first_name, d.last_name, 
                   d.specialization, d.availability, d.doc_fee 
            FROM Doctor d
            WHERE d.dept_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $dept_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $selected_doctors = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
</head>

<body style="padding-top: 50px;">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <a class="navbar-brand" href="#">
            <i class="fa fa-hospital-o"></i> Hospital Management System
        </a>
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
        <h3 class="text-center">Welcome <?php echo htmlspecialchars($patient['first_name']); ?></h3>

        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-4" style="max-width:18%;margin-top: 3%;">
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" href="#list-dash" data-toggle="tab">Dashboard</a>
                    <a class="list-group-item list-group-item-action" href="#update-profile" data-toggle="tab">Update Profile</a>
                    <a class="list-group-item list-group-item-action" href="#list-profile" data-toggle="tab">Book Appointments</a>
                    <a class="list-group-item list-group-item-action" href="#list-appt" data-toggle="tab">Pending Tests</a>
                    <a class="list-group-item list-group-item-action" href="#list-tests" data-toggle="tab">Test Results</a>
                    <a class="list-group-item list-group-item-action" href="#list-trtplans" data-toggle="tab">Pay Bill</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-8" style="margin-top: 3%;">
                <div class="tab-content" id="nav-tabContent" style="width: 950px;">
                    <!-- Dashboard -->
                    <div class="tab-pane fade show active" id="list-dash">
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
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></p>
                                            <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($patient['blood_group']); ?></p>
                                            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient['dob']); ?></p>
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
                                        <div class="col-md-3">
                                            <p><strong>City:</strong> <?php echo htmlspecialchars($patient['city']); ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>ZIP:</strong> <?php echo htmlspecialchars($patient['zip']); ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>Country:</strong> <?php echo htmlspecialchars($patient['country']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Profile Tab -->
                    <div class="tab-pane fade" id="update-profile">
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
                                                <input type="text" class="form-control" value="<?= $patient['first_name'] ?>" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Last Name:</label>
                                                <input type="text" class="form-control" value="<?= $patient['last_name'] ?>" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Email:</label>
                                                <input type="email" name="email" class="form-control" value="<?= $patient['email'] ?>">
                                            </div>
                                        </div>

                                        <!-- Medical Information -->
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <label>Gender:</label>
                                                <select class="form-control" name="gender">
                                                    <option value="Male" <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                                    <option value="Female" <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                                    <option value="Other" <?= $patient['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Blood Group:</label>
                                                <input type="text" name="blood_group" class="form-control" value="<?= $patient['blood_group'] ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Date of Birth:</label>
                                                <input type="date" name="dob" class="form-control" value="<?= $patient['dob'] ?>">
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
                    <div class="tab-pane fade" id="list-profile">
                        <div class="container-fluid bg-white p-4">
                            <h4 class="mb-4">Book New Appointment</h4>
                            
                            <form method="POST">
                                <div class="form-group">
                                    <label>Select Department:</label>
                                    <select name="selected_dept" class="form-control" required>
                                        <option value="" disabled selected>Select Department</option>
                                        <?php foreach($departments as $dept): ?>
                                            <option value="<?= $dept['dept_id'] ?>" 
                                                <?= isset($_POST['selected_dept']) && $_POST['selected_dept'] == $dept['dept_id'] ? 'selected' : '' ?>>
                                                <?= $dept['dept_name'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Show Doctors</button>
                            </form>

                            <?php if(!empty($selected_doctors)): ?>
                            <div class="mt-4">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Doctor Name</th>
                                            <th>Specialization</th>
                                            <th>Availability</th>
                                            <th>Fee</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($selected_doctors as $doctor): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($doctor['first_name'].' '.$doctor['last_name']) ?></td>
                                            <td><?= htmlspecialchars($doctor['specialization']) ?></td>
                                            <td><?= htmlspecialchars($doctor['availability']) ?></td>
                                            <td>$<?= htmlspecialchars($doctor['doc_fee']) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm book-btn" 
                                                        data-doctor-id="<?= $doctor['user_id'] ?>">
                                                    Book Appointment
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="mt-4">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Doctor Name</th>
                                            <th>Specialization</th>
                                            <th>Availability</th>
                                            <th>Fee</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center">Select a department to view doctors</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                            <!-- Appointment Modal -->
                            <div class="modal fade" id="appointmentModal" tabindex="-1">
                                <div class="modal fade" id="appointmentModal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Appointment</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form id="appointmentForm" method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="doctor_id" id="selectedDoctorId">
                                                    <div class="form-group">
                                                        <label>Appointment Date</label>
                                                        <input type="date" name="appt_date" class="form-control" required 
                                                            min="<?= date('Y-m-d') ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Preferred Time</label>
                                                        <input type="time" name="appt_time" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="book_appointment" class="btn btn-primary">Confirm Booking</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    <!-- Pending Tests Tab -->
                    <div class="tab-pane fade" id="list-appt">
                        <!-- ... existing pending tests content ... -->
                    </div>

                    <!-- Test Results Tab -->
                    <div class="tab-pane fade" id="list-tests">
                        <h4>Test Results</h4>
                        <input type="text" class="form-control mb-2" placeholder="Search by Patient Name/ID">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 20%;">Patient</th>
                                    <th style="width: 20%;">Test Name</th>
                                    <th style="width: 20%;">Date</th>
                                    <th style="width: 20%;">Result</th>

                                </tr>
                            </thead>
                        </table>
                    </div>

                    <!-- Treatment Plans -->
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        $(document).ready(function() {
        $('#departmentSelect').change(function() {
            var dept_id = $(this).val();
            if(dept_id) {
                $.ajax({
                    url: 'get_doctors.php',
                    type: 'POST',
                    data: {dept_id: dept_id},
                    success: function(response) {
                        $('#doctorsTableBody').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        $('#doctorsTableBody').html('<tr><td colspan="5">Error loading doctors</td></tr>');
                    }
                });
            }
        });
    });
    </script>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>