<?php
include 'config.php';

// Function to generate new Patient ID
function generatePatientID($con) {
    $query = "SELECT user_id FROM Users WHERE user_id LIKE 'p%' ORDER BY user_id DESC LIMIT 1"; //fetching the last patientID
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
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = generatePatientID($con);  // Auto-generated ID
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = $_POST['gender'];


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
    $stmt2->bind_param("sssssssssssss", $user_id, $first_name, $last_name, $email, $hashed_password, $gender, $blood_group, $dob, $hno, $street, $city, $zip, $country); //missing information stored as null for later updation

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <title>Patient Registration</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style1.css">
</head>

<body>

    <nav>
        <div class="logo">
            <span>🏥 HOSPITAL MANAGEMENT SYSTEM</span>
        </div>
    </nav>

    <div class="container register" style="font-family: 'IBM Plex Sans', sans-serif;">

        <div class="right-section">
            <img src="images/favicon.png" alt="Hospital Icon">
            <h2>Register as Patient</h2>
            <form action="patient_registration.php" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; grid-gap: 10px;">
                    <div class="form-group"><input type="text" name="first_name" placeholder="First Name *" required></div>
                    <div class="form-group"><input type="text" name="last_name" placeholder="Last Name *" required></div>
                    <div class="form-group"><input type="email" name="email" placeholder="Your Email *" required></div>
                    <div class="form-group">
                        <input type="text" name="gender" placeholder="Gender *" required list="genderOptions">
                        <datalist id="genderOptions">
                            <option value="Male">
                            <option value="Female">
                            <option value="Other">
                        </datalist>
                    </div>
                    <div class="form-group"><input type="password" name="password" placeholder="Password *" required></div>
                    <div class="form-group"><input type="password" name="confirm_password" placeholder="Confirm Password *" required></div>
                </div>
                <button type="submit" class="submit-btn" style="margin-top: 20px;">Register</button>
            </form>
        </div>
    </div>

</body>

</html>