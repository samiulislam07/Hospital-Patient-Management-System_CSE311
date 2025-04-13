<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = trim($_POST['user_id']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Error: Passwords do not match!");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Verify user ID and email
    $sql = "SELECT user_id FROM Users WHERE user_id = ? AND email = ?";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $con->error);
    }
    $stmt->bind_param("ss", $user_id, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->close();

        // Update password in Users table
        $sql = "UPDATE Users SET password = ? WHERE user_id = ?";
        $stmt = $con->prepare($sql);
        if (!$stmt) {
            die("Error preparing Users update statement: " . $con->error);
        }
        $stmt->bind_param("ss", $hashed_password, $user_id);

        if ($stmt->execute()) {
            $stmt->close();

            // Additional updates for staff or patient tables
            if (strpos($user_id, 'p') === 0) {
                // Update Patient table
                $sql = "UPDATE Patient SET password = ? WHERE user_id = ?";
                $stmt = $con->prepare($sql);
                if (!$stmt) {
                    die("Error preparing Patient update statement: " . $con->error);
                }
                $stmt->bind_param("ss", $hashed_password, $user_id);
                $stmt->execute();
            } else {
                // Update Staff table
                $sql = "UPDATE Staff SET password = ? WHERE user_id = ?";
                $stmt = $con->prepare($sql);
                if (!$stmt) {
                    die("Error preparing Staff update statement: " . $con->error);
                }
                $stmt->bind_param("ss", $hashed_password, $user_id);
                $stmt->execute();

                if (strpos($user_id, 'd') === 0) {
                    // Update Doctor table
                    $sql = "UPDATE Doctor SET password = ? WHERE user_id = ?";
                    $stmt = $con->prepare($sql);
                    if (!$stmt) {
                        die("Error preparing Doctors update statement: " . $con->error);
                    }
                    $stmt->bind_param("ss", $hashed_password, $user_id);
                    $stmt->execute();
                } else if (strpos($user_id, 'n') === 0) {
                    // Update Nurse table
                    $sql = "UPDATE Nurse SET password = ? WHERE user_id = ?";
                    $stmt = $con->prepare($sql);
                    if (!$stmt) {
                        die("Error preparing Nurses update statement: " . $con->error);
                    }
                    $stmt->bind_param("ss", $hashed_password, $user_id);
                    $stmt->execute();
                }
            }

            echo "<script>alert('Password reset successful!');</script>";
            header('Refresh: 1; URL=index.php');
            exit();
        } else {
            echo "<script>alert('Error Updating Password');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error: User ID or email not found.');</script>";
    }

    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <title>Reset Password</title>
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
            <h5>Reset Password</h5>
            <form action="reset_password.php" method="POST">
                <div class="form-group">
                    <input type="text" name="user_id" placeholder="User ID*" required>
                </div>
                <div class="form-group">
                    <input type="text" name="email" placeholder="Email*" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder=" New Password*" required>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" placeholder="Confirm New Password*" required>
                </div>
                <button type="submit" class="submit-btn">Reset</button>
            </form>

        </div>
    </div>

</body>

</html>