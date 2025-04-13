
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
            <form action="register_patient.php" method="POST">
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