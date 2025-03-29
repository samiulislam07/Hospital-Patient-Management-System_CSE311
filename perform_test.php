<?php
session_start();
header('Content-Type: application/json');
include 'config.php';

// $testData = json_decode(file_get_contents('php://input'), true);

// if ($testData) {
//     $success = true;
//     foreach ($testData as $test) {
//         $patientId = $test['patientId'];
//         $testId = $test['testId'];
//         $testDate = $test['testDate'];
//         $result = $test['result'];

//         $sql = "UPDATE Doc_Test_Patient
//                     SET test_date = '$testDate', result = '$result'
//                     WHERE patient_user_id = '$patientId' AND test_id = '$testId'";

//         if (!$con->query($sql)) {
//             $success = false;
//             break;
//         }

//         // Insert into Nurse_Test_Patient
//         $nurse_Id = $_SESSION['user_id'];
//         $sqlNurse = "INSERT INTO Nurse_Test_Patient (nurse_user_id, test_id, patient_user_id) VALUES ('$nurse_Id', '$testId', '$patientId')";

//         if (!$con->query($sqlNurse)) {
//             $success = false;
//             break;
//         }

//         // Check if test date and result are not null, then add to Bill_detail
//         if ($testDate !== null && $result !== null) {
//             // Get test cost from Test table
//             $sqlTestCost = "SELECT test_cost FROM Test WHERE test_id = '$testId'";
//             $resultTestCost = $con->query($sqlTestCost);

//             if ($resultTestCost && $rowTestCost = $resultTestCost->fetch_assoc()) {
//                 $testCost = $rowTestCost['test_cost'];

//                 // Insert into Bill_detail
//                 $sqlBillDetail = "INSERT INTO Bill_detail (patient_user_id, test_id, charge_amount) VALUES ('$patientId', '$testId', '$testCost')";

//                 if (!$con->query($sqlBillDetail)) {
//                     $success = false;
//                     break;
//                 }
//             } else {
//                 $success = false;
//                 break;
//             }
//         }
//     }

//     echo json_encode(['success' => $success]);
// } else {
//     echo json_encode(['success' => false]);
// }

// Get POST data.
$patient_id = $_POST['patient_user_id'] ?? '';
$test_id = $_POST['test_id'] ?? '';
$test_date = $_POST['test_date'] ?? '';
$result = $_POST['result'] ?? '';

// Basic validation.
if (empty($patient_id) || empty($test_id) || empty($test_date) || empty($result)) {
    echo json_encode(['error' => 'Missing required fields.']);
    exit;
}

// Update the Doc_Test_Patient record with test_date and result.
$sql = "UPDATE Doc_Test_Patient SET test_date = ?, result = ? WHERE patient_user_id = ? AND test_id = ? AND test_date IS NULL AND result IS NULL";
$stmt = $con->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Database error: ' . $con->error]);
    exit;
}

$stmt->bind_param("ssss", $test_date, $result, $patient_id, $test_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update test record.']);
}
$stmt->close();
?>