<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = trim($_POST['admin_id']);
    $password = $_POST['password'];

    $sql = "SELECT admin_id, password 
            FROM Admint 
            WHERE admin_id = ?";
    $stmt = $con->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $con->error);
    }

    $stmt->bind_param("s", $admin_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_user_id, $db_password);
        if (!$stmt->fetch()) {
            die("Fetch failed: " . $stmt->error);
        }

        if ($password === $db_password) { 
            $_SESSION['admin_id'] = $db_user_id;
            header("Location: admin1.php");
            $stmt->close();
            $con->close();
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User ID not found!";
    }

    $stmt->close();

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <title>Admin Login - Hospital Management System</title>
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
            <h3>Login as Admin</h3>
            <?php if (isset($error)) : ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="admin.php" method="POST">
                <div class="form-group">
                    <input type="text" name="admin_id" placeholder="User ID*" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password*" required>
                </div>
                <button type="submit" class="submit-btn">Login</button>
            </form>
        </div>
    </div>
</body>

</html>