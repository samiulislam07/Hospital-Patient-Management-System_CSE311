<?php
include 'config.php';

// Function to generate new Patient ID
function generatePatientID($con) {
    $query = "SELECT user_id FROM Users WHERE user_id LIKE 'p%' ORDER BY user_id DESC LIMIT 1";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['user_id']; // Example: "p009"
        $num = (int)substr($last_id, 1); // Extract number: 9
        $new_num = $num + 1;
        return 'p' . str_pad($new_num, 3, '0', STR_PAD_LEFT); // Output: "p010"
    } else {
        return "p001"; // First patient
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = generatePatientID($con);  // Auto-generated ID
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Error: Passwords do not match!");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into Users table
    $sql1 = "INSERT INTO Users (user_id, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt1 = $con->prepare($sql1);
    if (!$stmt1) {
        die("Prepare failed: " . $con->error);
    }
    $stmt1->bind_param("sssss", $user_id, $first_name, $last_name, $email, $hashed_password);

    // Insert into Patient table
    $sql2 = "INSERT INTO Patient (user_id, first_name, last_name, email, password, gender, blood_group, dob, hno, street, city, zip, country) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt2 = $con->prepare($sql2);
    if (!$stmt2) {
        die("Prepare failed: " . $con->error);
    }
    $stmt2->bind_param("sssssssssssss", $user_id, $first_name, $last_name, $email, $hashed_password, $gender, $blood_group, $dob, $hno, $street, $city, $zip, $country);

    // Execute both queries
    if ($stmt1->execute() && $stmt2->execute()) {
        echo "<script>alert('Registration successful! Your Patient ID: " . $user_id . "');</script>";
        header('Refresh: 2; URL=patient_dashboard.php');
    } else {
        echo "Error: " . $stmt1->error . "<br>" . $stmt2->error;
    }

    // Close statements
    $stmt1->close();
    $stmt2->close();

}

$con->close();
?>
