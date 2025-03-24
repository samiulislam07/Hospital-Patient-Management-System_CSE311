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

$con->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
            <i class="fa fa-hospital-o"></i>Hospital Management System</a>
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
                    <a class="list-group-item list-group-item-action" href="#list-patients" data-toggle="list">View All Patients</a>
                    <a class="list-group-item list-group-item-action" href="#list-pdetails" data-toggle="list">Patients Medical Details</a>
                    <a class="list-group-item list-group-item-action" href="#list-performtest" data-toggle="list">Perform Tests</a>
                </div>
            </div>

            <div class="col-md-8" style="margin-top: 3%;">
                <div class="tab-content" id="nav-tabContent" style="width: 950px;">
                    <!-- Dashboard -->
                    <div class="tab-pane fade show active" id="list-dash">
                        <div class="container-fluid bg-white p-4">
                            <div class="row">
                                <!-- Nurse Profile -->
                                <div class="col-12">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <p><strong>User ID:</strong> <?php echo htmlspecialchars($nurse['user_id']); ?></p>
                                            <p><strong>First Name:</strong> <?php echo htmlspecialchars($nurse['first_name']); ?></p>
                                            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($nurse['last_name']); ?></p>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($nurse['email']); ?></p>
                                            <p><strong>Gender:</strong> <?php echo htmlspecialchars($nurse['gender']); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($nurse['phone']); ?></p>
                                            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($nurse['dob']); ?></p>
                                            <p><strong>Salary:</strong> <?php echo htmlspecialchars($nurse['salary']); ?></p>
                                            <p><strong>Duty Hour: </strong> <?php echo htmlspecialchars($nurse['duty_hour']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Update Profile -->
                    <div class="tab-pane fade show" id="list-profile">
                        <h3>Update Profile</h3>
                        <form method="POST">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>User ID:</label>
                                    <input type="text" class="form-control" value="<?= $nurse['user_id'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>First Name:</label>
                                    <input type="text" class="form-control" value="<?= $nurse['first_name'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Last Name:</label>
                                    <input type="text" class="form-control" value="<?= $nurse['last_name'] ?? '' ?>" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Email:</label>
                                    <input type="email" name="email" class="form-control" value="<?= $nurse['email'] ?? '' ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Phone:</label>
                                    <input type="text" name="phone" class="form-control" value="<?= $nurse['phone'] ?? '' ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Gender:</label>
                                    <select class="form-control" name="gender">
                                        <option value="Male" <?= isset($nurse['gender']) && $nurse['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= isset($nurse['gender']) && $nurse['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= isset($nurse['gender']) && $nurse['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Date of Birth:</label>
                                    <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($nurse['dob'] ?? '') ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Salary:</label>
                                    <input type="text" class="form-control" value="<?= $nurse['salary'] ?? '' ?>" disabled>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Duty Hour:</label>
                                    <input type="text" name="duty_hour" class="form-control" value="<?= $nurse['duty_hour'] ?? '' ?>">
                                </div>
                            </div>

                            <br>
                            <div class="text-left">
                                <button type="submit" name="update_nurse" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                    <!-- Patient Information -->
                    <div class="tab-pane fade" id="list-patients">
                        <h4>Patients Information</h4>
                        <input type="text" class="form-control mb-2" id="patientSearch" placeholder="Search by Patient Name">
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
                                            <td><?= htmlspecialchars($patient['blood_group']) ?></td>
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

                </div>
            </div>
        </div>
    </div>


    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // Patient Information Search
        const patientSearchInput = document.getElementById('patientSearch');
        const patientsTableBody = document.querySelector('#patientsTable tbody');

        patientSearchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = patientsTableBody.querySelectorAll('tr');

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
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