<?php
session_start();
include 'config.php';

// Get patient id from GET (or POST) parameter
$patient_id = $_GET['patient_id'] ?? '';
if (empty($patient_id)) {
    echo "<p class='text-danger'>No patient specified.</p>";
    exit;
}

// --- Query 1: Fetch test history (distinct tests with latest result) ---
// We assume that if a patient had the same test multiple times, we want the record with the latest test_date.
$sqlTests = "SELECT t.test_name, MAX(dtp.test_date) AS latest_test_date, 
                    SUBSTRING_INDEX(GROUP_CONCAT(dtp.result ORDER BY dtp.test_date DESC), ',', 1) AS latest_result
             FROM Doc_Test_Patient dtp
             JOIN Test t ON dtp.test_id = t.test_id
             WHERE dtp.patient_user_id = ? 
               AND dtp.test_date IS NOT NULL
               AND dtp.result IS NOT NULL
             GROUP BY t.test_id, t.test_name
             ORDER BY latest_test_date DESC";

$stmtTests = $con->prepare($sqlTests);
if (!$stmtTests) {
    echo "<p class='text-danger'>Database error: " . $con->error . "</p>";
    exit;
}
$stmtTests->bind_param("s", $patient_id);
$stmtTests->execute();
$resultTests = $stmtTests->get_result();
$tests = [];
if ($resultTests && $resultTests->num_rows > 0) {
    while ($row = $resultTests->fetch_assoc()) {
        $tests[] = $row;
    }
}
$stmtTests->close();

// --- Query 2: Fetch treatment plans ---
$sqlTreatment = "SELECT prescribe_date, dosage, suggestion
                 FROM TreatmentPlan
                 WHERE patient_user_id = ?
                 ORDER BY prescribe_date DESC";
$stmtTreatment = $con->prepare($sqlTreatment);
if (!$stmtTreatment) {
    echo "<p class='text-danger'>Database error: " . $con->error . "</p>";
    exit;
}
$stmtTreatment->bind_param("s", $patient_id);
$stmtTreatment->execute();
$resultTreatment = $stmtTreatment->get_result();
$treatmentPlans = [];
if ($resultTreatment && $resultTreatment->num_rows > 0) {
    while ($row = $resultTreatment->fetch_assoc()) {
        $treatmentPlans[] = $row;
    }
}
$stmtTreatment->close();

// --- Build HTML output ---
$output = "";

// Test History Section
$output .= "<h5>Test History</h5>";
if (count($tests) > 0) {
    $output .= "<div class='table-responsive'><table class='table table-bordered'>";
    $output .= "<thead><tr>
                    <th>#</th>
                    <th>Test Name</th>
                    <th>Performed Date</th>
                    <th>Result</th>
                </tr></thead><tbody>";
    $i = 1;
    foreach ($tests as $test) {
        $output .= "<tr>
                        <td>" . $i++ . "</td>
                        <td>" . htmlspecialchars($test['test_name']) . "</td>
                        <td>" . htmlspecialchars($test['latest_test_date']) . "</td>
                        <td>" . htmlspecialchars($test['latest_result']) . "</td>
                    </tr>";
    }
    $output .= "</tbody></table></div>";
} else {
    $output .= "<p>No test history found.</p>";
}

// Treatment Plans Section
$output .= "<h5 class='mt-4'>Treatment Plans</h5>";
if (count($treatmentPlans) > 0) {
    $output .= "<div class='table-responsive'><table class='table table-bordered'>";
    $output .= "<thead><tr>
                    <th>#</th>
                    <th>Prescribed Date</th>
                    <th>Dosage</th>
                    <th>Suggestion</th>
                </tr></thead><tbody>";
    $j = 1;
    foreach ($treatmentPlans as $plan) {
        $output .= "<tr>
                        <td>" . $j++ . "</td>
                        <td>" . htmlspecialchars($plan['prescribe_date']) . "</td>
                        <td>" . htmlspecialchars($plan['dosage']) . "</td>
                        <td>" . htmlspecialchars($plan['suggestion']) . "</td>
                    </tr>";
    }
    $output .= "</tbody></table></div>";
} else {
    $output .= "<p>No treatment plans found.</p>";
}

echo $output;
?>
