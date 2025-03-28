<?php
session_start();
include 'config.php';

// Check if the user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'nurse') {
    header("Location: index.php");
    exit();
}

$current_session = session_id();
$user_id = $_SESSION['user_id'];

// Fetch the stored session id from the Patient table
$sql = "SELECT session_id FROM Nurse WHERE user_id = ?";
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

$nurse_id = $_SESSION['user_id'];
$nurse = [];

// Fetch nurse details from the database.
$sql = "SELECT user_id, first_name, last_name, email, gender, 
        phone, dob, salary, duty_hour 
        FROM Nurse 
        WHERE user_id = ?";
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

// Handle nurse profile update via POST request.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_nurse'])) {
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $dutyhour = $_POST['duty_hour'];

    // Ensure user ID is set before proceeding with updates.
    if (!empty($nurse_id)) {

        // Begin a transaction to ensure all updates are atomic (all or nothing).
        $con->begin_transaction();

        // Update email in the Users table.
        $update_users_sql = "UPDATE Users 
                            SET email = ? 
                            WHERE user_id = ?";
        $stmt_users = $con->prepare($update_users_sql);

        if ($stmt_users) {
            $stmt_users->bind_param("ss", $email, $nurse_id);

            if ($stmt_users->execute()) {
                // Update Staff table (email, gender, phone, dob).
                $update_staff_sql = "UPDATE Staff 
                                    SET email = ?, gender = ?, phone = ?, dob = ? 
                                    WHERE user_id = ?";
                $stmt_staff = $con->prepare($update_staff_sql);

                if ($stmt_staff) {
                    $stmt_staff->bind_param("sssss", $email, $gender, $phone, $dob, $nurse_id);

                    if ($stmt_staff->execute()) {
                        // Update Nurse table (email, gender, phone, dob, duty_hour).
                        $update_nurse_sql = "UPDATE Nurse 
                                            SET email = ?, gender = ?, phone = ?, dob = ?, duty_hour = ? 
                                            WHERE user_id = ?";
                        $stmt_nurse = $con->prepare($update_nurse_sql);

                        if ($stmt_nurse) {
                            $stmt_nurse->bind_param("ssssss", $email, $gender, $phone, $dob, $dutyhour, $nurse_id);

                            if ($stmt_nurse->execute()) {
                                // Commit the transaction if all updates succeed.
                                $con->commit();
                                echo "<script>alert('Profile updated successfully!'); window.location.href='nurse_dashboard.php';</script>";
                            } else {
                                // Rollback if Nurse table update fails.
                                $con->rollback();
                                echo "<script>alert('Error updating Nurse profile. Please try again.');</script>";
                            }
                            $stmt_nurse->close();
                        } else {
                            // Rollback if Nurse prepare fails.
                            $con->rollback();
                            echo "<script>alert('Database error updating Nurse. Please try again.');</script>";
                        }
                    } else {
                        // Rollback if Staff table update fails.
                        $con->rollback();
                        echo "<script>alert('Error updating Staff profile. Please try again.');</script>";
                    }
                    $stmt_staff->close();
                } else {
                    // Rollback if Staff prepare fails.
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
            // Rollback if Users prepare fails.
            $con->rollback();
            echo "<script>alert('Database error updating Users. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('User ID missing. Cannot update profile.');</script>";
    }
}

// Fetch patient information for display.
$patientInfo = [];
$sql = "SELECT p.user_id, p.first_name, p.last_name, p.gender, 
        p.dob, p.blood_group, mh.allergies, mh.pre_conditions 
        FROM Patient p 
        LEFT JOIN MedicalHistory mh 
        ON p.user_id = mh.patient_user_id";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patientInfo[] = $row;
    }
}

// Fetch treatment plans data, including doctor and patient names, for display.
$sql = "SELECT tp.trtplan_id, tp.prescribe_date, tp.dosage, tp.suggestion, 
        p.first_name AS patient_first_name, p.last_name AS patient_last_name, 
        p.user_id AS patient_user_id, 
        d.first_name AS doctor_first_name, d.last_name AS doctor_last_name 
        FROM TreatmentPlan tp 
        JOIN Patient p ON tp.patient_user_id = p.user_id 
        LEFT JOIN Doctor d ON tp.doctor_user_id = d.user_id 
        ORDER BY p.first_name, p.last_name, d.first_name, d.last_name";
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

// Fetch tests with null test_date and result for display.
$sql = "SELECT dtp.test_id, dtp.patient_user_id, dtp.pres_date, 
                p.first_name AS patient_first_name, p.last_name AS patient_last_name, 
                t.test_name 
                FROM Doc_Test_Patient dtp 
                JOIN Patient p ON dtp.patient_user_id = p.user_id 
                JOIN Test t ON dtp.test_id = t.test_id 
                WHERE dtp.test_date IS NULL AND dtp.result IS NULL 
                ORDER BY dtp.pres_date DESC";
$result = $con->query($sql);

if ($result === false) {
    echo "SQL Error: " . $con->error;
    die();
}

$tests = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tests[] = $row;
    }
}

$con->close(); // Close the database connection.

?>