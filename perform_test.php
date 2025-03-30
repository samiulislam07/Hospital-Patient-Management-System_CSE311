<?php
session_start();
header('Content-Type: application/json');
include 'config.php';

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve posted values
    $patientId  = trim($_POST['patient_user_id'] ?? '');
    $testId     = trim($_POST['test_id'] ?? '');
    $test_date  = $_POST['test_date'];
    $resultText = $_POST['result'];

    // Validate required fields
    if (empty($patientId) || empty($testId) || empty($test_date) || empty($resultText)) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
        exit;
    }

    // Get nurse id from session
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'error' => 'Not logged in.']);
        exit;
    }
    $nurseId = $_SESSION['user_id'];

    // Begin transaction
    $con->autocommit(false);
    $success = true;

    // 1. Insert into Nurse_Test_Patient table
    $sqlNurse = "INSERT INTO Nurse_Test_Patient (nurse_user_id, test_id, patient_user_id) VALUES (?, ?, ?)";
    $stmtNurse = $con->prepare($sqlNurse);
    if (!$stmtNurse) {
        echo json_encode(['success' => false, 'error' => 'Prepare failed (Nurse_Test_Patient): ' . $con->error]);
        exit;
    }
    $stmtNurse->bind_param("sis", $nurseId, $testId, $patientId);
    if (!$stmtNurse->execute()) {
        $success = false;
    }
    $stmtNurse->close();

    // 2. Update Doc_Test_Patient record to set test_date and result
    if ($success) {
        $sqlDocTest = "UPDATE Doc_Test_Patient SET test_date = ?, result = ? 
                       WHERE  test_id = ? AND patient_user_id = ? AND test_date IS NULL AND result IS NULL";
        $stmtDocTest = $con->prepare($sqlDocTest);
        if (!$stmtDocTest) {
            $success = false;
        } else {
            $stmtDocTest->bind_param("ssis", $test_date, $resultText, $testId, $patientId);
            if (!$stmtDocTest->execute()) {
                $success = false;
            }
            $stmtDocTest->close();
        }
    }

    // 3. Retrieve test cost from Test table
    if ($success) {
        $sqlTestCost = "SELECT test_cost FROM Test WHERE test_id = ?";
        $stmtTestCost = $con->prepare($sqlTestCost);
        if (!$stmtTestCost) {
            echo json_encode(['success' => false, 'error' => 'Prepare failed (Test cost): ' . $con->error]);
            exit;
        }
        $stmtTestCost->bind_param("i", $testId);
        $stmtTestCost->execute();
        $resultTestCost = $stmtTestCost->get_result();
        if ($resultTestCost && $rowTestCost = $resultTestCost->fetch_assoc()) {
            $testCost = $rowTestCost['test_cost'];
        } else {
            $success = false;
        }
        $stmtTestCost->close();
    }

    // 4. Insert into Bill_detail table
    if ($success) {
        $sqlBillDetail = "INSERT INTO Bill_detail (patient_user_id, test_id, charge_amount) VALUES (?, ?, ?)";
        $stmtBillDetail = $con->prepare($sqlBillDetail);
        if (!$stmtBillDetail) {
            echo json_encode(['success' => false, 'error' => 'Prepare failed (Bill_detail): ' . $con->error]);
            exit;
        }

        $stmtBillDetail->bind_param("sis", $patientId, $testId, $testCost);
        if (!$stmtBillDetail->execute()) {
            $success = false;
        }
        $stmtBillDetail->close();
    }

    // Commit or rollback transaction based on success
    if ($success) {
        $con->commit();
        echo json_encode(['success' => true]);
    } else {
        $con->rollback();
        echo json_encode(['success' => false, 'error' => 'Failed to update records.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
