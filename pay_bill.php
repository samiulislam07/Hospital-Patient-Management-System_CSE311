<?php

header('Content-Type: application/json');
include 'config.php';
include 'patient_func.php';

$response = pay_due_bills();
echo json_encode($response);
?>
