<?php include 'config.php'; include 'patient_func.php';?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/dashboard_style.css">
    <!-- External Stylesheets -->
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=IBM+Plex+Sans&display=swap" rel="stylesheet">
</head>

<body style="padding-top: 50px;">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <a class="navbar-brand" href="#">
            <i class="fa fa-hospital-o"></i> Hospital Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
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
        <h3 class="text-center">Welcome <?php echo htmlspecialchars($patient['first_name']); ?></h3>

        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-4" style="max-width:25%; margin-top: 3%">
                <div class="list-group" id="list-tab" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-dash-list" data-toggle="list" href="#list-dash" role="tab" aria-controls="list-dash">Dashboard</a>
                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="list-profile">Update Profile</a>
                    <a class="list-group-item list-group-item-action" id="list-appt-list" data-toggle="list" href="#list-appt" role="tab" aria-controls="list-appt">Book Appointments</a>
                    <a class="list-group-item list-group-item-action" id="list-test-list" data-toggle="list" href="#list-test" role="tab" aria-controls="list-test">Pending Tests</a>
                    <a class="list-group-item list-group-item-action" id="list-result-list" data-toggle="list" href="#list-result" role="tab" aria-controls="list-result">Test Results</a>
                    <a class="list-group-item list-group-item-action" id="list-bill-list" data-toggle="list" href="#list-bill" role="tab" aria-controls="list-bill">Pay Bill</a>
                </div><br>
            </div>

            <!-- Main Content -->
            <div class="col-md-8" style="margin-top: 3%;">
                <div class="tab-content" id="nav-tabContent" style="width: 950px;">
                    <!-- Dashboard -->
                    <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
                        <div class="container-fluid bg-white p-4">
                            <div class="row">
                                <!-- Patient Profile -->
                                <div class="col-12">
                                    <!-- First Row: Personal Details -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <p><strong>User ID:</strong> <?php echo htmlspecialchars($patient['user_id']); ?></p>
                                            <p><strong>First Name:</strong> <?php echo htmlspecialchars($patient['first_name']); ?></p>
                                            <p><strong>Last Name:</strong> <?php echo htmlspecialchars($patient['last_name']); ?></p>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></p>
                                            <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($patient['blood_group']); ?></p>
                                            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient['dob']); ?></p>
                                        </div>
                                    </div>

                                    <!-- Second Row: Address Details -->
                                    <div class="row border-top pt-3">
                                        <div class="col-md-2">
                                            <p><strong>House No:</strong> <?php echo htmlspecialchars($patient['hno']); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>Street:</strong> <?php echo htmlspecialchars($patient['street']); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <p><strong>City:</strong> <?php echo htmlspecialchars($patient['city']); ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>ZIP:</strong> <?php echo htmlspecialchars($patient['zip']); ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <p><strong>Country:</strong> <?php echo htmlspecialchars($patient['country']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Update Profile Tab -->
                    <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                        <div class="container-fluid bg-white p-4">
                            <form method="POST">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="mb-4">Update Patient Profile</h4>

                                        <!-- Personal Information -->
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <label>User ID:</label>
                                                <input type="text" class="form-control" value="<?= $patient['user_id'] ?>" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label>First Name:</label>
                                                <input type="text" class="form-control" value="<?= $patient['first_name'] ?>" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Last Name:</label>
                                                <input type="text" class="form-control" value="<?= $patient['last_name'] ?>" disabled>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Email:</label>
                                                <input type="email" name="email" class="form-control" value="<?= $patient['email'] ?>">
                                            </div>
                                        </div>

                                        <!-- Medical Information -->
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <label>Gender:</label>
                                                <select class="form-control" name="gender">
                                                    <option value="Male" <?= $patient['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                                    <option value="Female" <?= $patient['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                                    <option value="Other" <?= $patient['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Blood Group:</label>
                                                <select name="blood_group" class="form-control">
                                                    <option value="A+" <?= $patient['blood_group'] == 'A+' ? 'selected' : '' ?>>A+</option>
                                                    <option value="A-" <?= $patient['blood_group'] == 'A-' ? 'selected' : '' ?>>A-</option>
                                                    <option value="B+" <?= $patient['blood_group'] == 'B+' ? 'selected' : '' ?>>B+</option>
                                                    <option value="B-" <?= $patient['blood_group'] == 'B-' ? 'selected' : '' ?>>B-</option>
                                                    <option value="AB+" <?= $patient['blood_group'] == 'AB+' ? 'selected' : '' ?>>AB+</option>
                                                    <option value="AB-" <?= $patient['blood_group'] == 'AB-' ? 'selected' : '' ?>>AB-</option>
                                                    <option value="O+" <?= $patient['blood_group'] == 'O+' ? 'selected' : '' ?>>O+</option>
                                                    <option value="O-" <?= $patient['blood_group'] == 'O-' ? 'selected' : '' ?>>O-</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Date of Birth:</label>
                                                <input type="date" name="dob" class="form-control" value="<?= $patient['dob'] ?>">
                                            </div>
                                        </div>

                                        <!-- Address Information -->
                                        <div class="row mb-4">
                                            <div class="col-md-2">
                                                <label>House No:</label>
                                                <input type="text" name="hno" class="form-control" value="<?= $patient['hno'] ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Street:</label>
                                                <input type="text" name="street" class="form-control" value="<?= $patient['street'] ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>City:</label>
                                                <input type="text" name="city" class="form-control" value="<?= $patient['city'] ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <label>ZIP:</label>
                                                <input type="text" name="zip" class="form-control" value="<?= $patient['zip'] ?>">
                                            </div>
                                            <div class="col-md-2"> <!-- Changed from col-md-1 -->
                                                <label>Country:</label>
                                                <input type="text" name="country" class="form-control" 
                                                    value="<?= $patient['country'] ?>" 
                                                    style="min-width: 150px;">
                                            </div>
                                        </div>
                                    </div>

                                        <!-- Update Button -->
                                        <div class="row mt-4">
                                            <div class="col-md-12 text-right">
                                                <button type="submit" name="update_patient" class="btn btn-primary">
                                                    <i class="fa fa-pencil"></i> Update Profile
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    <!-- Book Appointments Tab -->
                     <div class="tab-pane fade" id="list-appt" role="tabpanel" aria-labelledby="list-appt-list">
                        <div class="container-fluid">
                            <div class="card">
                                <div class="card-body">
                                    <center><h4>Book Appointment</h4></center><br>
                                    <form class="form-group" method="post" action="patient_dashboard.php">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="spec">Specialization</label>
                                            </div>
                                            <div class="col-md-8">
                                                <select name="spec" class="form-control" id="spec">
                                                    <option value="" disabled selected>Select Specialization</option>
                                                    <?php display_specs() ?>
                                                </select>
                                            </div>
                                            <br><br>

                                            <script>
                                                document.getElementById('spec').onchange = function foo() {
                                                    let spec = this.value;
                                                    console.log(spec)
                                                    let docs = [...document.getElementById('doctor').options];
                                                    docs.forEach((el, ind, arr)=>{
                                                        arr[ind].setAttribute("style","");
                                                        if (el.getAttribute("data-spec") != spec ) {
                                                arr[ind].setAttribute("style","display: none");
                                                }
                                                });
                                                };
                                            </script>
                                            
                                            <div class="col-md-4"><label for="doctor">Doctors:</label></div>
                                            <div class="col-md-8">
                                                <select name="doctor" class="form-control" id="doctor">
                                                    <option value="" disabled selected>Select Doctor</option>
                                                    <?php display_docs(); ?>
                                                </select>
                                            </div>
                                            <br><br>

                                            <script>
                                                document.getElementById('doctor').onchange = function updateFees(e){
                                                var selectedOption = this.options[this.selectedIndex]; 
                                                var fee = selectedOption.getAttribute('data-fee');
                                                var avail = selectedOption.getAttribute('data-availability');
                                                var id = selectedOption.getAttribute('data-id');
                                                document.getElementById('docFees').value = fee;
                                                document.getElementById('appointmentTime').value = avail;
                                                document.getElementById('docId').value = id;
                                                }
                                            </script>

                                            <div class="col-md-4">
                                                <label for="consultancyfees">Doctor ID</label>                                                
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="docId" id="docId" readonly="readonly"/>
                                            </div>
                                            <br><br>
                                            
                                            <div class="col-md-4">
                                                <label for="consultancyfees">Consultancy Fees</label>                                                
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="docFees" id="docFees" readonly="readonly"/>
                                            </div>
                                            <br><br>
                                            <div class="col-md-4"><label>Appointment Date</label></div>
                                            <div class="col-md-8"><input type="date" class="form-control datepicker" name="appdate"></div><br><br>
                                            
                                            <div class="col-md-4">
                                                <label for="appointmentTime">Appointment Time</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" name="appointmentTime" id="appointmentTime" readonly="readonly"/>
                                            </div>
                                            <br><br>
                                            <div class="col-md-4">
                                                <input type="submit" name="app-submit" value="Create New Appointment" class="btn btn-primary" id="inputbtn">
                                            </div>
                                            <div class="col-md-8"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><br>
                     </div>
                    <!-- Pending Tests Tab -->
                    <div class="tab-pane fade" id="list-test" role="tabpanel" aria-labelledby="list-test-list">
                        <!-- ... existing pending tests content ... -->
                    </div>

                    <!-- Test Results Tab -->
                    <div class="tab-pane fade" id="list-result" role="tabpanel" aria-labelledby="list-result-list">
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
                    <!-- Pay Bill -->
                    <div class="tab-pane fade" id="list-bill" role="tabpanel" aria-labelledby="list-bill-list">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.10.1/sweetalert2.all.min.js">
   </script>
    <script>
    // Initialize Bootstrap tabs PROPERLY
    $(document).ready(function() {
        $('#list-tab a').tab();
    });
    </script>
</body>

</html>