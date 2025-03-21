<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <link rel="stylesheet" href="css/style.css">
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
        <img id="role-image" src="images/favicon.png">
        <h2 id="role-text">Welcome</h2>
    </div>

    <!-- Right Section -->
    <div class="right-section">
        <div class="tab-buttons">
            <button class="tab-btn active" onclick="showForm('patient')">Patient</button>
            <button class="tab-btn" onclick="showForm('doctor')">Doctor</button>
            <button class="tab-btn" onclick="showForm('nurse')">Nurse</button>
        </div>

        <!-- Patient Form -->
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
                <p>Already have an account? <a href="#">Sign in</a></p>
            </div>
        </div>


        <!-- Doctor Form -->
        <div class="form-container" id="doctor-form">
            <h2>Login as Doctor</h2>
            <div class="form-group"><input type="text" placeholder="User Name *"></div>
            <div class="form-group"><input type="password" placeholder="Password *"></div>
            <button class="submit-btn">Login</button>
        </div>

        <!-- Admin Form -->
        <div class="form-container" id="nurse-form">
            <h2>Login as Nurse</h2>
            <div class="form-group"><input type="text" placeholder="User Name *"></div>
            <div class="form-group"><input type="password" placeholder="Password *"></div>
            <button class="submit-btn">Login</button>
        </div>
    </div>
</div>

<script>
    function showForm(role) {
        // Remove 'active' class from all buttons and forms
        document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("active"));
        document.querySelectorAll(".form-container").forEach(form => form.classList.remove("active"));

        // Add 'active' class to the clicked button and corresponding form
        document.querySelector(`[onclick="showForm('${role}')"]`).classList.add("active");
        document.getElementById(`${role}-form`).classList.add("active");

        // Update image and text on the left section
        const roleImage = document.getElementById("role-image");
        const roleText = document.getElementById("role-text");

        if (role === "patient") {
            roleImage.src = "images/patient.png"; // Replace with actual patient image URL
            roleText.innerText = "Welcome, Patient!";
        } else if (role === "doctor") {
            roleImage.src = "images/doctor.png"; // Replace with actual doctor image URL
            roleText.innerText = "Welcome, Doctor!";
        } else if (role === "nurse") {
            roleImage.src = "images/nurse.png"; // Replace with actual nurse image URL
            roleText.innerText = "Welcome, Nurse!";
        }
    }
</script>

</body>
</html>
