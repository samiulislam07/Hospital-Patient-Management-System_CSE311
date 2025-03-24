<?php
session_start();
include 'config.php';

$error = "";

// Check if login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = trim($_POST['user_id']);
    $password = $_POST['password'];
    $role = $_POST['role']; // Identifies if login is for doctor or nurse

    if ($role === "doctor") {
        $sql = "SELECT user_id, password FROM Users WHERE user_id = ?";
    } elseif ($role === "nurse") {
        $sql = "SELECT user_id, password FROM Users WHERE user_id = ?";
    } else {
        $error = "Invalid role!";
    }

    if (empty($error)) {
        $stmt = $con->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $con->error);
        }

        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_user_id, $db_password);
            $stmt->fetch();

            // Directly compare passwords (since they are stored in plain text)
            if ($password === $db_password) {
                $_SESSION['user_id'] = $db_user_id;
                $_SESSION['role'] = $role;
                
                if ($role === "doctor") {
                    header("Location: doctor_dashboard.php");
                } elseif ($role === "nurse") {
                    header("Location: nurse_dashboard.php");
                }
                exit();
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "User ID not found!";
        }

        $stmt->close();
    }
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <div class="logo">
            <span>🏥 HOSPITAL MANAGEMENT SYSTEM</span>
        </div>
    </nav>

    <div class="container">
        <div class="left-section">
            <img id="role-image" src="images/favicon.png">
            <h2 id="role-text">Welcome</h2>
        </div>

        <div class="right-section">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="showForm('patient')">Patient</button>
                <button class="tab-btn" onclick="showForm('doctor')">Doctor</button>
                <button class="tab-btn" onclick="showForm('nurse')">Nurse</button>
            </div>

            <!-- Patient Registration -->
            <div class="form-container active" id="patient-form">
                <h2>Register as Patient</h2>
                <form action="register_patient.php" method="POST">
                    <div class="form-group"><input type="text" name="first_name" placeholder="First Name *" required></div>
                    <div class="form-group"><input type="text" name="last_name" placeholder="Last Name *" required></div>
                    <div class="form-group"><input type="email" name="email" placeholder="Your Email *" required></div>
                    <div class="form-group"><input type="password" name="password" placeholder="Password *" required></div>
                    <div class="form-group"><input type="password" name="confirm_password" placeholder="Confirm Password *" required></div>
                    <button type="submit" class="submit-btn">Register</button>
                </form>
                <div class="register-link">
                    <p>Already have an account? <a href="patient_login.php">Sign in</a></p>
                </div>
            </div>

            <!-- Doctor Login -->
            <div class="form-container" id="doctor-form">
                <h2>Login as Doctor</h2>
                <form action="" method="POST">
                    <input type="hidden" name="role" value="doctor">
                    <div class="form-group"><input type="text" name="user_id" placeholder="User ID" required></div>
                    <div class="form-group"><input type="password" name="password" placeholder="Password" required></div>
                    <button type="submit" class="submit-btn">Login</button>
                </form>
                <?php if (!empty($error) && isset($_POST['role']) && $_POST['role'] === "doctor") { echo "<p style='color:red;'>$error</p>"; } ?>
            </div>

            <!-- Nurse Login -->
            <div class="form-container" id="nurse-form">
                <h2>Login as Nurse</h2>
                <form action="" method="POST">
                    <input type="hidden" name="role" value="nurse">
                    <div class="form-group"><input type="text" name="user_id" placeholder="User ID" required></div>
                    <div class="form-group"><input type="password" name="password" placeholder="Password" required></div>
                    <button type="submit" class="submit-btn">Login</button>
                </form>
                <?php if (!empty($error) && isset($_POST['role']) && $_POST['role'] === "nurse") { echo "<p style='color:red;'>$error</p>"; } ?>
            </div>
        </div>
    </div>

<script>
    function showForm(role) {
        document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("active"));
        document.querySelectorAll(".form-container").forEach(form => form.classList.remove("active"));

        document.querySelector(`[onclick="showForm('${role}')"]`).classList.add("active");
        document.getElementById(`${role}-form`).classList.add("active");

        const roleImage = document.getElementById("role-image");
        const roleText = document.getElementById("role-text");

        if (role === "patient") {
            roleImage.src = "images/patient.png";
            roleText.innerText = "Welcome, Patient!";
        } else if (role === "doctor") {
            roleImage.src = "images/doctor.png";
            roleText.innerText = "Welcome, Doctor!";
        } else if (role === "nurse") {
            roleImage.src = "images/nurse.png";
            roleText.innerText = "Welcome, Nurse!";
        }
    }
</script>

</body>
</html>
