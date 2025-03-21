<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = trim($_POST['user_id']);
    $password = $_POST['password'];

    // Fetch user from Patient table
    $sql = "SELECT user_id, password FROM Patient WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_user_id, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $db_user_id; // Store user ID in session
            header("Location: patient_dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User ID not found!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Login - Hospital Management System</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>

    <nav>
        <div class="logo">
            <span>🏥 HOSPITAL MANAGEMENT SYSTEM</span>
        </div>
    </nav>

    <div class="container">
        <div class="left-section">
            <img src="images/ambulance1.png" alt="Ambulance Icon">
            <p>We are here for you!</p>
        </div>

        <div class="right-section">
            <img src="images/favicon.png" alt="Hospital Icon">
            <h2>Patient Login</h2>

            <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

            <form action="" method="POST">
                <div class="input-group">
                    <label>User ID</label>
                    <input type="text" name="user_id" placeholder="Enter your User ID" required>
                </div>
                <div class="input-group">
                    <label>Password:</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>

</body>
</html>
