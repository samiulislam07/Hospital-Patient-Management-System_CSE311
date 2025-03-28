<?php
session_start();
include 'config.php';


// Check if the user is logged in and is a doctor
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    header("Location: index.php");
    exit();
}

$current_session = session_id();
$user_id = $_SESSION['user_id'];

// Fetch the stored session id from the Doctor table
$sql = "SELECT session_id FROM Doctor WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row || $row['session_id'] !== $current_session) {
    // Session mismatch: possibly logged in from another device
    session_destroy();
    header("Location: index.php?error=Your account was logged in from another device. Please log in again.");
    exit();
}

// Get the doctor's user ID from the session.
$doctor_id = $_SESSION['user_id'];

// Initialize arrays to store doctor details and department information.
$doctor = [];
$dept = [];


// Fetch doctor details from the database.
$sql = "SELECT user_id, first_name, last_name, email, gender, phone, 
            dob, salary, doc_fee, specialization, availability
        FROM Doctor d
        WHERE user_id = ?";

$stmt = $con->prepare($sql);

if ($stmt) {
    // Bind the doctor's user ID to the prepared statement.
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a doctor record is found, store it in the $doctor array.
    if ($result->num_rows === 1) {
        $doctor = $result->fetch_assoc();
    }

    // Close the prepared statement.
    $stmt->close();
}

//Fetch department details of the doctor
$departmentDetails = [];
$sql = "SELECT dept.dept_name, CONCAT(s.first_name, ' ', s.last_name) AS head_name
        FROM Doctor d
        LEFT JOIN Department dept ON d.dept_id = dept.dept_id
        LEFT JOIN Staff s ON dept.dept_head = s.user_id
        WHERE d.user_id = ?";

$stmt = $con->prepare($sql);

if (!$stmt) {
    die("Error: Prepare failed: " . $con->error); // Exit on error
}

$stmt->bind_param("s", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $departmentDetails = $result->fetch_assoc();
}
$stmt->close();


function deptViewTable($userId)
{
    global $con;

    // Check if the current user is a Head of Department (HoD)
    $hodCheckSql = "SELECT dept_id FROM Department WHERE dept_head = ?";
    $hodCheckStmt = $con->prepare($hodCheckSql);
    $hodCheckStmt->bind_param("s", $userId);
    $hodCheckStmt->execute();
    $hodCheckStmt->store_result();

    if ($hodCheckStmt->num_rows > 0) {
        $hodCheckStmt->bind_result($deptId);
        $hodCheckStmt->fetch();

        // Retrieve staff details for the HoD's department
        $staffSql = "SELECT s.user_id, s.first_name, s.last_name, s.gender, s.email, d.specialization, n.duty_hour
                    FROM Staff s
                    LEFT JOIN Doctor d ON s.user_id = d.user_id
                    LEFT JOIN Nurse n ON s.user_id = n.user_id
                    WHERE s.dept_id = ?";
        $staffStmt = $con->prepare($staffSql);
        $staffStmt->bind_param("s", $deptId);
        $staffStmt->execute();
        $staffResult = $staffStmt->get_result();

        if ($staffResult->num_rows > 0) {

            while ($staffRow = $staffResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($staffRow['user_id']) . "</td>";
                echo "<td>" . htmlspecialchars($staffRow['first_name'] . ' ' . $staffRow['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($staffRow['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($staffRow['email']) . "</td>";
                echo "<td>" . htmlspecialchars($staffRow['specialization']) . "</td>";
                echo "<td>" . htmlspecialchars($staffRow['duty_hour']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>No Staff found in your department.</td></tr>";
        }

        $staffStmt->close();
    } else {
        echo "<tr><td colspan='6' class='text-center'>You are not a Head of Departemnt.</td></tr>";
    }

    $hodCheckStmt->close();
}

// Handle doctor profile update form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_doctor'])) {
    // Get form data.
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $doc_fee = $_POST['doc_fee'];
    $specialization = $_POST['specialization'];
    $availability = $_POST['availability'];

    // Ensure the doctor's user ID is set.
    if (!empty($doctor_id)) {
        // Start a database transaction for atomicity.
        $con->begin_transaction();

        // Update the Users table (email only).
        $update_users_sql = "UPDATE Users SET email = ? WHERE user_id = ?";
        $stmt_users = $con->prepare($update_users_sql);

        if ($stmt_users) {
            // Bind the email and doctor's user ID to the prepared statement.
            $stmt_users->bind_param("ss", $email, $doctor_id);

            if ($stmt_users->execute()) {
                // Update the Staff table.
                $update_staff_sql = "UPDATE Staff SET email = ?, phone = ?, dob = ? WHERE user_id = ?";
                $stmt_staff = $con->prepare($update_staff_sql);

                if ($stmt_staff) {
                    // Bind the form data and doctor's user ID to the prepared statement.
                    $stmt_staff->bind_param("ssss", $email, $phone, $dob, $doctor_id);

                    if ($stmt_staff->execute()) {
                        // Update the Doctor table.
                        $update_doctor_sql = "UPDATE Doctor SET email = ?, phone = ?, dob = ?, 
                                                doc_fee = ?, specialization = ?, availability = ? WHERE user_id = ?";
                        $stmt_doctor = $con->prepare($update_doctor_sql);

                        if ($stmt_doctor) {
                            // Bind the form data and doctor's user ID to the prepared statement.
                            $stmt_doctor->bind_param(
                                "sssssss",
                                $email,
                                $phone,
                                $dob,
                                $doc_fee,
                                $specialization,
                                $availability,
                                $doctor_id
                            );

                            if ($stmt_doctor->execute()) {
                                // Commit the transaction if all updates are successful.
                                $con->commit();
                                echo "<script>alert('Profile updated successfully!'); 
                                        window.location.href='doctor_dashboard.php';</script>";
                            } else {
                                // Rollback the transaction if Doctor table update fails.
                                $con->rollback();
                                echo "<script>alert('Error updating Doctor profile. Please try again.');</script>";
                            }
                            $stmt_doctor->close();
                        } else {
                            // Rollback if Doctor prepare failed
                            $con->rollback();
                            echo "<script>alert('Database error updating Doctor. Please try again.');</script>";
                        }
                    } else {
                        // Rollback if Staff table update fails.
                        $con->rollback();
                        echo "<script>alert('Error updating Staff profile. Please try again.');</script>";
                    }
                    $stmt_staff->close();
                } else {
                    // Rollback if staff prepare failed
                    $con->rollback();
                    echo "<script>alert('Database error updating Staff. Please try again.');</script>";
                }
            } else {
                // Rollback if Users table update fails.
                $con->rollback();
                echo "<script>alert('Error updating Users profile. Please try again.');</script>";
            }
            $stmt_users->close();
        } else {
            // Rollback if user prepare failed
            $con->rollback();
            echo "<script>alert('Database error updating Users. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('User ID missing. Cannot update profile.');</script>";
    }
}


// Fetch Appointments for the doctor.
$appointments = [];
$sql = "SELECT a.appt_id, a.appt_date, a.appt_time, c.appt_status, 
            p.first_name AS patient_first_name, p.last_name AS patient_last_name, 
            p.gender AS patient_gender
        FROM Appointment a
        INNER JOIN checkup c ON a.appt_id = c.appt_id
        INNER JOIN Patient p ON c.patient_user_id = p.user_id
        WHERE c.doctor_user_id = ?";

$stmt = $con->prepare($sql);

if ($stmt) {
    // Bind the doctor's user ID to the prepared statement.
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store appointment data in the $appointments array.
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }

    // Close the prepared statement.
    $stmt->close();
}


// Handle appointment status update form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $appt_id = $_POST['appt_id'];
    $appt_status = $_POST['appt_status'];

    $update_sql = "UPDATE checkup SET appt_status = ? WHERE appt_id = ? AND doctor_user_id = ?";
    $stmt = $con->prepare($update_sql);

    if ($stmt) {
        // Bind the appointment status, appointment ID, and doctor's user ID.
        $stmt->bind_param("sis", $appt_status, $appt_id, $doctor_id);

        if ($stmt->execute()) {
            // If the status is 'Completed', generate a bill.
            if ($appt_status === 'Completed') {
                // Get patient and doctor user IDs from the checkup table.
                $select_checkup_sql = "SELECT patient_user_id, doctor_user_id FROM checkup WHERE appt_id = ?";
                $select_checkup_stmt = $con->prepare($select_checkup_sql);
                $select_checkup_stmt->bind_param("i", $appt_id);
                $select_checkup_stmt->execute();
                $select_checkup_stmt->bind_result($patient_user_id, $doctor_user_id);
                $select_checkup_stmt->fetch();
                $select_checkup_stmt->close();

                // Get the doctor's fee from the Doctor table.
                $select_doctor_sql = "SELECT doc_fee FROM Doctor WHERE user_id = ?";
                $select_doctor_stmt = $con->prepare($select_doctor_sql);
                $select_doctor_stmt->bind_param("s", $doctor_user_id);
                $select_doctor_stmt->execute();
                $select_doctor_stmt->bind_result($doc_fee);
                $select_doctor_stmt->fetch();
                $select_doctor_stmt->close();

                // Insert a record into the Bill_detail table.
                $insert_sql = "INSERT INTO Bill_detail (patient_user_id, doctor_user_id, test_id, charge_amount) 
                                VALUES (?, ?, NULL, ?)";
                $insert_stmt = $con->prepare($insert_sql);

                if ($insert_stmt) {
                    $insert_stmt->bind_param("sss", $patient_user_id, $doctor_user_id, $doc_fee);

                    if ($insert_stmt->execute()) {
                        echo "<script>alert('Appointment status updated and bill detail added successfully!'); 
                                window.location.href='doctor_dashboard.php';</script>";
                    } else {
                        echo "<script>alert('Appointment status updated, but error adding bill detail. Please try again.'); 
                                window.location.href='doctor_dashboard.php';</script>";
                    }
                    $insert_stmt->close();
                } else {
                    echo "<script>alert('Appointment status updated, but database error adding bill detail. Please try again.'); 
                            window.location.href='doctor_dashboard.php';</script>";
                }
            } else {
                echo "<script>alert('Appointment status updated successfully!'); 
                        window.location.href='doctor_dashboard.php';</script>";
            }
        } else {
            echo "<script>alert('Error updating appointment status. Please try again.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Database error. Please try again.');</script>";
    }
}

// Fetch Ongoing Patients for the doctor.
$ongoingPatients = [];
$sql = "SELECT p.user_id, p.first_name, p.last_name, 
            p.blood_group, mh.allergies, mh.pre_conditions
        FROM Patient p
        INNER JOIN checkup c ON p.user_id = c.patient_user_id
        LEFT JOIN MedicalHistory mh ON p.user_id = mh.patient_user_id
        WHERE c.doctor_user_id = ? AND c.appt_status = 'Scheduled'";

$stmt = $con->prepare($sql);

if ($stmt) {
    // Bind the doctor's user ID to the prepared statement.
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store ongoing patient data in the $ongoingPatients array.
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ongoingPatients[] = $row;
        }
    }

    // Close the prepared statement.
    $stmt->close();
}

// Fetch Test Results for the logged-in doctor.
$patientTests = [];
$sql = "SELECT p.first_name, p.last_name, t.test_name, 
            dtp.test_date, dtp.result, dtp.patient_user_id
        FROM Doc_Test_Patient dtp
        JOIN Patient p ON dtp.patient_user_id = p.user_id
        JOIN Test t ON dtp.test_id = t.test_id
        WHERE dtp.doctor_user_id = ?
        ORDER BY dtp.pres_date DESC";

$stmt = $con->prepare($sql);

if ($stmt) {
    // Bind the doctor's user ID to the prepared statement.
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Process the result set and store test results.
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patient_id = $row['patient_user_id'];
            $patientTests[$patient_id]['patient_name'] = $row['first_name'] . ' ' . $row['last_name'];
            $patientTests[$patient_id]['tests'][] = [
                'test_name' => $row['test_name'],
                'test_date' => $row['test_date'],
                'result' => $row['result']
            ];
        }
    }
    // Close the prepared statement.
    $stmt->close();
}

// Fetch Treatment Plans for the logged-in doctor.
$treatmentPlans = [];
$sql = "SELECT p.first_name, p.last_name, tp.trtplan_id,
            tp.prescribe_date, tp.dosage, tp.suggestion,
            tp.patient_user_id
        FROM TreatmentPlan tp
        JOIN Patient p ON tp.patient_user_id = p.user_id
        WHERE tp.doctor_user_id = ?
        ORDER BY tp.prescribe_date DESC";

$stmt = $con->prepare($sql);

if ($stmt) {
    // Bind the doctor's user ID to the prepared statement.
    $stmt->bind_param("s", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Process the result set and store treatment plans.
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patient_id = $row['patient_user_id'];
            $treatmentPlans[$patient_id]['patient_name'] = $row['first_name'] . ' ' . $row['last_name'];
            $treatmentPlans[$patient_id]['plans'][] = [
                'trtplan_id' => $row['trtplan_id'],
                'prescribe_date' => $row['prescribe_date'],
                'dosage' => $row['dosage'],
                'suggestion' => $row['suggestion']
            ];
        }
    }
    // Close the prepared statement.
    $stmt->close();
}

// Close the database connection.
$con->close();
