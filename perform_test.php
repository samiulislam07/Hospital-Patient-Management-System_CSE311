<?php
session_start();
include 'config.php';

$testData = json_decode(file_get_contents('php://input'), true);

if ($testData) {
    $success = true;
    foreach ($testData as $test) {
        $patientId = $test['patientId'];
        $testId = $test['testId'];
        $testDate = $test['testDate'];
        $result = $test['result'];

        $sql = "UPDATE Doc_Test_Patient
                SET test_date = '$testDate', result = '$result'
                WHERE patient_user_id = '$patientId' AND test_id = '$testId'";

        if (!$con->query($sql)) {
            $success = false;
            break;
        }

        // Insert into Nurse_Test_Patient
        $nurse_Id = $_SESSION['user_id']; 
        $sqlNurse = "INSERT INTO Nurse_Test_Patient (nurse_user_id, test_id, patient_user_id) VALUES ('$nurse_Id', '$testId', '$patientId')";

        if (!$con->query($sqlNurse)) {
            $success = false;
            break;
        }
    }

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false]);
}

?>