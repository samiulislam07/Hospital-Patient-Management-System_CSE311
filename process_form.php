<?php
session_start();
include 'config.php';

// Handle POST requests only.
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
    // Get patient and doctor IDs, with error handling for missing IDs.
    $patientId = $_POST['patientId'] ?? null;
    $doctorId = $_SESSION['user_id'] ?? null;

    if (empty($doctorId) || empty($patientId)) {
        echo json_encode(["status" => "error", "message" => "Doctor ID or Patient ID are missing!"]);
        exit();
    }

    // Retrieve appointment date for the patient's ongoing appointment.
    $sql = "SELECT a.appt_date FROM Appointment a INNER JOIN checkup c ON a.appt_id = c.appt_id WHERE c.patient_user_id = ? AND c.doctor_user_id = ? AND c.appt_status = 'scheduled' ORDER BY a.appt_id DESC LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $patientId, $doctorId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $appointmentDate = $row['appt_date'];
    } else {
        echo json_encode(["status" => "error", "message" => "No ongoing appointment found for this patient!"]);
        exit();
    }

    // Handle test order insertion.
    if (isset($_POST['selectedTests'])) {
        $selectedTests = explode(", ", $_POST['selectedTests']);
        $insertSql = "INSERT INTO doc_test_patient (doctor_user_id, test_id, patient_user_id, pres_date) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($insertSql);

        foreach ($selectedTests as $testName) {
            $testQuery = "SELECT test_id FROM test WHERE test_name = ?";
            $testStmt = $con->prepare($testQuery);
            $testStmt->bind_param("s", $testName);
            $testStmt->execute();
            $testResult = $testStmt->get_result();

            if ($testRow = $testResult->fetch_assoc()) {
                $testId = $testRow['test_id'];
                $stmt->bind_param("siss", $doctorId, $testId, $patientId, $appointmentDate);
                $stmt->execute();
            } else {
                echo json_encode(["status" => "error", "message" => "Test \"" . $testName . "\" not found in database!"]);
                exit();
            }
        }
        echo json_encode(["status" => "success", "message" => "Test Order Successfully Saved!"]);
    }
    // Handle treatment plan insertion.
    else if (isset($_POST['dosage']) && isset($_POST['suggestion'])) {
        $dosage = $_POST['dosage'];
        $suggestion = $_POST['suggestion'];

        $insertSql = "INSERT INTO TreatmentPlan (prescribe_date, dosage, suggestion, patient_user_id, doctor_user_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insertSql);
        $stmt->bind_param("sssss", $appointmentDate, $dosage, $suggestion, $patientId, $doctorId);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Treatment plan saved successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error saving treatment plan: " . $stmt->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing parameters!"]);
        exit();
    }
} else {
    exit(); // Exit if not a POST request.
}
?>