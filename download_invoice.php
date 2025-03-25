<?php
//session_start();
include 'config.php'; 
include 'patient_func.php'; // If you want to reuse any functions
require('fpdf186/fpdf.php');   // Ensure the path to FPDF is correct

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}
$patient_id = $_SESSION['user_id'];

// Query to fetch paid bills for the logged-in patient
// (Here we assume invoice is generated only for bills that have been marked as 'Paid')
$sql = "SELECT 
            b.bill_detail_id,
            b.charge_amount,
            b.status,
            CASE 
                WHEN b.test_id IS NOT NULL THEN 'Test Fee'
                ELSE 'Consultancy Fee'
            END AS bill_type,
            d.first_name AS doc_fname,
            d.last_name AS doc_lname,
            t.test_name
        FROM Bill_detail b
        LEFT JOIN doctor d ON b.doctor_user_id = d.user_id
        LEFT JOIN test t ON b.test_id = t.test_id
        WHERE b.patient_user_id = ? AND b.status = 'Due'";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
$stmt->close();

// Initialize PDF using FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

// Invoice Title
$pdf->Cell(0,10,'Invoice',0,1,'C');
$pdf->Ln(5);

// Patient Info
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,8,'Patient ID: '.$patient_id,0,1);
$pdf->Ln(5);

// Table headers
$pdf->SetFont('Arial','B',12);
$pdf->Cell(10,10,'#',1);
$pdf->Cell(40,10,'Bill Type',1);
$pdf->Cell(50,10,'Doctor Name',1);
$pdf->Cell(50,10,'Test Name',1);
$pdf->Cell(30,10,'Amount ($)',1);
$pdf->Ln();

// Reset font for table rows and prepare subtotal
$pdf->SetFont('Arial','',12);
$counter = 1;
$subtotal = 0;
foreach ($rows as $row) {
    $billType = $row['bill_type'];
    if ($billType == 'Test Fee') {
        $doctorName = "-";
        $testName = !empty($row['test_name']) ? $row['test_name'] : "-";
    } else { // Consultancy Fee
        $doctorName = trim($row['doc_fname'] . " " . $row['doc_lname']);
        if(empty($doctorName)) { $doctorName = "-"; }
        $testName = "-";
    }
    $amount = $row['charge_amount'];
    $subtotal += (float)$amount;
    
    $pdf->Cell(10,10,$counter,1);
    $pdf->Cell(40,10,$billType,1);
    $pdf->Cell(50,10,$doctorName,1);
    $pdf->Cell(50,10,$testName,1);
    $pdf->Cell(30,10,number_format($amount,2),1);
    $pdf->Ln();
    $counter++;
}

$pdf->Ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(150,10,'Subtotal:',0,0,'R');
$pdf->Cell(30,10,'$'.number_format($subtotal,2),0,1,'L');

// Force download of the PDF invoice
$pdf->Output('D', 'Invoice.pdf');
exit;
?>
