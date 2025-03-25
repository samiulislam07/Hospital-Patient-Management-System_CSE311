
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Doctor Dashboard</title>

    <!-- External Stylesheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">

    <style>
        .bg-primary {
            background: linear-gradient(to right, #000C40, #F0F2F0);
        }

        .list-group-item.active {
            background: linear-gradient(to right, #000C40, #F0F2F0);
            border-color: #c3c3c3;
        }

        .text-primary {
            color: #342ac1 !important;
        }

        button:hover {
            cursor: pointer;
        }
    </style>
</head>

<body style="padding-top: 50px;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <a class="navbar-brand" href="#">
            <i class="fa fa-hospital-o"></i> Hospital Management
        </a>
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

    <div class="container-fluid" style="margin-top: 50px;">
        <h3 class="text-center">Welcome, Doctor</h3>
        <div class="row">
            <div class="col-md-4" style="max-width:18%;margin-top: 3%;">
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" href="#list-dash" data-toggle="list">Dashboard</a>
                    <a class="list-group-item list-group-item-action" href="#list-profile" data-toggle="list">Doctor Profile</a>
                    <a class="list-group-item list-group-item-action" href="#list-appt" data-toggle="list">Appointments</a>
                    <a class="list-group-item list-group-item-action" href="#list-patients" data-toggle="list">Ongoing Patients</a>
                    <a class="list-group-item list-group-item-action" href="#list-tests" data-toggle="list">Test Results</a>
                    <a class="list-group-item list-group-item-action" href="#list-trtplans" data-toggle="list">Treatment Plans</a>
                </div>
            </div>

            <div class="col-md-8" style="margin-top: 3%;">
                <div class="tab-content" id="nav-tabContent" style="width: 950px;">
                    <!-- Dashboard -->
                    <div class="tab-pane fade show active" id="list-dash">
                        <div class="container-fluid bg-white p-4">
                            <div class="row">

                                <!-- Doctor Profile -->
                                <div class="col-md-4">
                                    <div class="panel text-center">
                                        <div class="panel-body">
                                            <i class="fa fa-user-md fa-3x text-primary"></i>
                                            <h4>Doctor Profile</h4>
                                            <p class="links cl-effect-1">
                                                <a href="#list-profile" data-toggle="list">
                                                    View Profile
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Appointments -->
                                <div class="col-md-4">
                                    <div class="panel text-center">
                                        <div class="panel-body">
                                            <i class="fa fa-calendar-check-o fa-3x text-primary"></i>
                                            <h4>Appointments</h4>
                                            <p class="links cl-effect-1">
                                                <a href="#list-appt" data-toggle="list">
                                                    Appointment List
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ongoing Patients -->
                                <div class="col-md-4">
                                    <div class="panel text-center">
                                        <div class="panel-body">
                                            <i class="fa fa-user fa-3x text-primary"></i>
                                            <h4>Ongoing Patients</h4>
                                            <p class="links cl-effect-1">
                                                <a href="#list-patients" data-toggle="list">
                                                    View Patients
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Test Results -->
                                <div class="col-md-6" style="margin-top: 50px;">
                                    <div class="panel text-center">
                                        <div class="panel-body">
                                            <i class="fa fa-flask fa-3x text-primary"></i>
                                            <h4>Test Results</h4>
                                            <p class="links cl-effect-1">
                                                <a href="#list-tests" data-toggle="list">
                                                    View Results
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Treatment Plans -->
                                <div class="col-md-6" style="margin-top: 50px;">
                                    <div class="panel text-center">
                                        <div class="panel-body">
                                            <i class="fa fa-stethoscope fa-3x text-primary"></i>
                                            <h4>Treatment Plans</h4>
                                            <p class="links cl-effect-1">
                                                <a href="#list-trtplans" data-toggle="list">
                                                    View Plans
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Doctor Profile -->
                    <div class="tab-pane fade show" id="list-profile">
                        <h3>Doctor Profile</h3>
                        <form>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>User ID:</label>
                                    <input type="text" class="form-control" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>First Name:</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Last Name:</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Email:</label>
                                    <input type="email" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Phone:</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Gender:</label>
                                    <select class="form-control">
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Date of Birth:</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Doctor Fee:</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Salary:</label>
                                    <input type="text" class="form-control" disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Specialization:</label>
                                    <select class="form-control">
                                        <option>Choose...</option>
                                        <option>Cardiology</option>
                                        <option>Dermatology</option>
                                        <option>Neurology</option>
                                        <option>Orthopedics</option>
                                        <option>Pediatrics</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Availability:</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>

                            <br>
                            <div class="text-left">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </form>
                    </div>

                    <!-- Appointments -->
                    <div class="tab-pane fade" id="list-appt">
                        <h4>Appointments</h4>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Patient</th>
                                    <th>Gender</th>
                                    <th>Mobile</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Update Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>Male</td>
                                    <td>1234567890</td>
                                    <td>2025-03-22</td>
                                    <td>10:00 AM</td>
                                    <td><span class="badge badge-info">Scheduled</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success">Ongoing</button>
                                        <button class="btn btn-sm btn-warning">Completed</button>
                                        <button class="btn btn-sm btn-danger">Cancelled</button>
                                        <button class="btn btn-sm btn-secondary">Missed</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Ongoing Patients -->
                    <div class="tab-pane fade" id="list-patients">
                        <h4>Ongoing Patients</h4>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Blood Group</th>
                                    <th>Allergy</th>
                                    <th>Preconditions</th>
                                    <th>Order Test</th>
                                    <th>Treatment Plan</th>
                                    <th>Assign Nurse</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>A+</td>
                                    <td>Peanuts</td>
                                    <td>Diabetes</td>
                                    <td><button class="btn btn-warning btn-sm">Order</button></td>
                                    <td><button class="btn btn-success btn-sm">Prescribe</button></td>
                                    <td><button class="btn btn-primary btn-sm">Assign</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Test Results -->
                    <div class="tab-pane fade" id="list-tests">
                        <h4>Test Results</h4>
                        <input type="text" class="form-control mb-2" placeholder="Search by Patient Name/ID">

                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">#</th>
                                    <th style="width: 20%;">Patient</th>
                                    <th style="width: 20%;">Test Name</th>
                                    <th style="width: 20%;">Date</th>
                                    <th style="width: 20%;">Result</th>

                                </tr>
                            </thead>
                        </table>
                    </div>

                    <!-- Treatment Plans -->
                    <div class="tab-pane fade" id="list-trtplans">
                        <h4>Treatment Plans</h4>
                        <input type="text" class="form-control mb-2" placeholder="Search by Patient Name/ID">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Patient</th>
                                    <th>Dosage</th>
                                    <th>Suggestions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>500mg</td>
                                    <td>Twice a day after food</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>