<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Login - Hospital Management System</title>
    <link rel="stylesheet" href="css/style1.css"> <!-- Link to external CSS -->
</head>
<body>

    <!-- Navigation Bar -->
    <nav>
        <div class="logo">
            <span>🏥 HOSPITAL MANAGEMENT SYSTEM</span>
        </div>
        <!-- <div class="nav-links">
            <a href="#">HOME</a>
            <a href="#">CONTACT</a>
        </div> -->
    </nav>

    <div class="container">
        <!-- Left Section -->
        <div class="left-section">
            <img src="images/ambulance1.png" alt="Ambulance Icon">
            <p>We are here for you!</p>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <img src="images/favicon.png" alt="Hospital Icon">
            <h2>Patient Login</h2>
            
            <form action="patient_dashboard.php" method="POST">
                <div class="input-group">
                    <label>Email:</label>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <label>Password:</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>

</body>
</html>
