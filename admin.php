<?php
include 'config.php';
include 'admin_func.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    </style>
</head>

<body style="padding-top: 50px;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <a class="navbar-brand" href="#">
            <i class="fa fa-hospital-o"></i> Hospital Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid" style="margin-top: 10px;">
        <div class="row">
            <div class="col-md-4" style="max-width:18%;margin-top: 3%;">
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" href="#list-dept" data-toggle="list">Departments</a>
                    <a class="list-group-item list-group-item-action" href="#list-admin" data-toggle="list">Admin</a>
                    <a class="list-group-item list-group-item-action" href="#list-staff" data-toggle="list">Staff</a>
                    <a class="list-group-item list-group-item-action" href="#list-patient" data-toggle="list">Patient</a>
                </div>
            </div>
            <div class="col-md-8" style="margin-top: 3%;">
                <div class="tab-content" id="nav-tabContent" style="width: 1200px;">
                    <!-- Departments -->
                    <div class="tab-pane fade show active" id="list-dept">
                        <table class="table table-hover" id="deptViewTable">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 20%;">Department Name</th>
                                    <th style="width: 20%;">Department Head</th>
                                    <th style="width: 20%;">Staff Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php displayDepartmentsTable(); ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Admin -->
                    <div class="tab-pane fade show" id="list-admin">
                        <h5> Admin Operations </h5>
                    </div>
                    <!-- Staff -->
                    <div class="tab-pane fade show" id="list-staff">
                        <h5> Staff Operations </h5>
                    </div>
                    <!-- Patient -->
                    <div class="tab-pane fade show" id="list-patient">
                        <table class="table table-hover" id="patientViewTable">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 15%;">Patient Name</th>
                                    <th style="width: 15%;">Gender</th>
                                    <th style="width: 15%;">Email</th>
                                    <th style="width: 15%;">Mobile</th>
                                    <th style="width: 20%;">Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php displayPatientTable(); ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>