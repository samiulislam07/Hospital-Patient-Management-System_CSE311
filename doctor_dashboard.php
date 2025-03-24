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
$stmt = $conn->prepare($sql);
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
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row['dept_name'];
    }
}

// Update Doctor Profile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_doctor'])) {
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $doc_fee = $_POST['doc_fee'];
    $specialization = $_POST['specialization'];
    $availability = $_POST['availability'];

    // Ensure doctor ID is set
    if (!empty($doctor_id)) {
        $update_sql = "UPDATE Doctor SET email = ?, gender = ?, phone = ?, dob = ?, doc_fee = ?, specialization = ?, availability = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        if ($stmt) {
            $stmt->bind_param("ssssssss", $email, $gender, $phone, $dob, $doc_fee, $specialization, $availability, $doctor_id);
            if ($stmt->execute()) {
                echo "<script>alert('Profile updated successfully!'); window.location.href='doctor_dashboard.php';</script>";
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


// Fetch Appointments (Corrected)
$appointments = [];
$sql = "SELECT a.appt_id, a.appt_date, a.appt_time, c.appt_status, p.first_name AS patient_first_name, p.last_name AS patient_last_name, p.gender AS patient_gender
        FROM Appointment a
        INNER JOIN checkup c ON a.appt_id = c.appt_id
        INNER JOIN Patient p ON c.patient_user_id = p.user_id
        WHERE c.doctor_user_id = ?";
$stmt = $conn->prepare($sql);
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
    $stmt = $conn->prepare($update_sql);
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
$stmt = $conn->prepare($sql);
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

$conn->close();
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
            <i class="fa fa-hospital-o"></i> Hospital Management
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
                                <!-- Doctor Profile -->
                                <div class="col-12">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <p><strong>User ID:</strong> <?php echo htmlspecialchars($doctor['user_id']); ?></p>
                                            <p><strong>First Name:</strong> <?php echo htmlspecialchars($doctor['first_name']); ?></p>
                                            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($doctor['last_name']); ?></p>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
                                            <p><strong>Gender:</strong> <?php echo htmlspecialchars($doctor['gender']); ?></p>
                                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['phone']); ?></p>
                                            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($doctor['dob']); ?></p>
                            
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Salary:</strong> <?php echo htmlspecialchars($doctor['salary']); ?></p>
                                            <p><strong>Specialization</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
                                            <p><strong>Fees:</strong> <?php echo htmlspecialchars($doctor['doc_fee']); ?></p>
                                            <p><strong>Availability:</strong> <?php echo htmlspecialchars($doctor['availability']); ?></p>
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
                                    <label>Email:</label>
                                    <input type="email" name="email" class="form-control" value="<?= $doctor['email'] ?? '' ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Phone:</label>
                                    <input type="text" name="phone" class="form-control" value="<?= $doctor['phone'] ?? '' ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Gender:</label>
                                    <select class="form-control" name="gender">
                                        <option value="Male" <?= isset($doctor['gender']) && $doctor['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= isset($doctor['gender']) && $doctor['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= isset($doctor['gender']) && $doctor['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Date of Birth:</label>
                                    <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($doctor['dob'] ?? '') ?>">
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Doctor Fee:</label>
                                    <input type="text" name="doc_fee" class="form-control" value="<?= $doctor['doc_fee'] ?? '' ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Salary:</label>
                                    <input type="text" class="form-control" value="<?= $doctor['salary'] ?? '' ?>" disabled>
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
                                                        $status_class = 'badge-info';
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
                                                        $status_class = 'badge-warning';
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
                                            <td><?= htmlspecialchars($patient['blood_group']) ?></td>
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
                        <input type="text" class="form-control mb-2" placeholder="Search by Patient Name/ID">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 20%;">Patient</th>
                                    <th style="width: 20%;">Test Name</th>
                                    <th style="width: 20%;"> Performed Date</th>
                                    <th style="width: 20%;">Result</th>

                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- Treatment Plans -->
                    <div class="tab-pane fade" id="list-trtplans">
                        <h4>Treatment Plans</h4>
                        <input type="text" class="form-control mb-2" placeholder="Search by Patient Name/ID">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Patient</th>
                                    <th>Dosage</th>
                                    <th>Suggestions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>500mg</td>
                                    <td>Twice a day after food</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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