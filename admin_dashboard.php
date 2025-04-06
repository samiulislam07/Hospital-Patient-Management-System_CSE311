<?php
include 'config.php';
include 'admin_func.php';

// Redirect if not logged in; security measure.
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}


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
                    <a class="nav-link" href="admin.php"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a>
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
                    <a class="list-group-item list-group-item-action" href="#list-staff" data-toggle="list">Staff</a>
                    <a class="list-group-item list-group-item-action" href="#list-patient" data-toggle="list">Patient</a>
                </div>
            </div>
            <div class="col-md-8" style="margin-top: 3%;">
                <div class="tab-content" id="nav-tabContent" style="width: 1200px;">
                    <!-- Departments -->
                    <div class="tab-pane fade show active" id="list-dept">
                        <div class="d-flex justify-content-between mb-3">
                            <button class="btn btn-primary" onclick="showAddModal()">Add Department</button>
                            <button class="btn btn-danger" onclick="showDeleteModal()">Delete Department</button>
                        </div>
                        <table class="table table-hover" id="deptViewTable">
                            <thead>
                                <tr>
                                    <th style="width: 10%;">ID</th>
                                    <th style="width: 20%;">Department Name</th>
                                    <th style="width: 20%;">Department Head</th>
                                    <th style="width: 20%;">Staff Count</th>
                                    <th style="width: 20%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php displayDepartmentsTable(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="addDeptModal" tabindex="-1" role="dialog" aria-labelledby="addDeptModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addDeptModalLabel">Add Department</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <div class="form-group">
                                            <label for="addDeptName">Department Name:</label>
                                            <input type="text" class="form-control" id="addDeptName" name="addDeptName" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteDeptModal" tabindex="-1" role="dialog" aria-labelledby="deleteDeptModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteDeptModalLabel">Delete Department</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <div class="form-group">
                                            <label for="deleteDeptName">Department Name:</label>
                                            <input type="text" class="form-control" id="deleteDeptName" name="deleteDeptName" required>
                                        </div>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editDeptModal" tabindex="-1" role="dialog" aria-labelledby="editDeptModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editDeptModalLabel">Edit Department Head</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <input type="hidden" id="editDeptId" name="editDeptId">
                                        <div class="form-group">
                                            <label for="editDocId">Doctor Name:</label>
                                            <select class="form-control" id="editDocId" name="editDocId">
                                                <option value="">Select Doctor</option>
                                                <?php
                                                $sqlDoctors = "SELECT user_id, first_name, last_name FROM Doctor";
                                                $resultDoctors = $con->query($sqlDoctors);
                                                if ($resultDoctors && $resultDoctors->num_rows > 0) {
                                                    while ($rowDoctor = $resultDoctors->fetch_assoc()) {
                                                        echo "<option value='" . $rowDoctor['user_id'] . "'>" . htmlspecialchars($rowDoctor['first_name'] . ' ' . $rowDoctor['last_name']) . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        function showAddModal() {
                            $('#addDeptModal').modal('show');
                        }

                        function showDeleteModal() {
                            $('#deleteDeptModal').modal('show');
                        }

                        function showEditModal(deptId) {
                            document.getElementById('editDeptId').value = deptId;
                            $('#editDeptModal').modal('show');
                        }
                    </script>
                    <!-- Staff -->
                    <div class="tab-pane fade show" id="list-staff">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <input type="text" id="staffNameFilter" class="form-control" placeholder="Search by Name">
                            </div>
                            <div class="col-md-3">
                                <select id="staffGenderFilter" class="form-control">
                                    <option value="">All Genders</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                            <button class="btn btn-primary" onclick="showAddStaffModal()">Add Staff</button>
                            <button class="btn btn-danger" onclick="showDeleteStaffModal()">Delete Staff</button>
                            </div>
                        </div>
                        <table class="table table-hover" id="staffViewTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th>DOB</th>
                                    <th>Salary</th>
                                    <th>Department</th>
                                    <th>Fee</th>
                                    <th>Specialization</th>
                                    <th>Availability</th>
                                    <th>Duty Hour</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php displayStaffTable(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal fade" id="addStaffModal" tabindex="-1" role="dialog" aria-labelledby="addStaffModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addStaffModalLabel">Add Staff</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <div class="form-group">
                                            <label for="firstName">First Name</label>
                                            <input type="text" class="form-control" name="firstName" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="lastName">Last Name</label>
                                            <input type="text" class="form-control" name="lastName" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" name="gender" required>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Role:</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="isDoctor" id="isDoctorYes" value="1">
                                                <label class="form-check-label" for="isDoctorYes">Doctor</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="isDoctor" id="isDoctorNo" value="0">
                                                <label class="form-check-label" for="isDoctorNo">Nurse</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="addStaff">Add</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="deleteStaffModal" tabindex="-1" role="dialog" aria-labelledby="deleteStaffModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteStaffModalLabel">Delete Staff</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <div class="form-group">
                                            <label for="UserId">User ID:</label>
                                            <input type="text" class="form-control" name="UserId" required>
                                        </div>
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editStaffModal" tabindex="-1" role="dialog" aria-labelledby="editStaffModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editStaffModalLabel">Edit Staff Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post">
                                        <input type="hidden" id="editUserId" name="userId">
                                        <div class="form-group">
                                            <label for="salary">Salary</label>
                                            <input type="text" class="form-control" name="salary">
                                        </div>
                                        <div class="form-group" id="dutyHourGroup">
                                            <label for="dutyHour">Duty Hour</label>
                                            <select class="form-control" name="dutyHour">
                                                <option value="">Select Duty Hour</option>
                                                <option value="Morning">Morning</option>
                                                <option value="Noon">Noon</option>
                                                <option value="Evening">Evening</option>
                                                <option value="Night">Night</option>
                                                <option value="Rotational">Rotational</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="deptId">Department</label>
                                            <select class="form-control" name="deptId">
                                                <option value="">Select Department</option>
                                                <?php
                                                $sqlDepartments = "SELECT dept_id, dept_name FROM Department";
                                                $resultDepartments = $con->query($sqlDepartments);
                                                if ($resultDepartments && $resultDepartments->num_rows > 0) {
                                                    while ($rowDepartment = $resultDepartments->fetch_assoc()) {
                                                        echo "<option value='" . $rowDepartment['dept_id'] . "'>" . htmlspecialchars($rowDepartment['dept_name']) . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary" name="editStaff">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function showAddStaffModal() {
                            $('#addStaffModal').modal('show');
                        }

                        function showDeleteStaffModal() {
                            $('#deleteStaffModal').modal('show');
                        }

                        function showEditStaffModal(userId) {
                            document.getElementById('editUserId').value = userId;
                            if (userId.startsWith('d')) {
                                document.getElementById('dutyHourGroup').style.display = 'none';
                            } else {
                                document.getElementById('dutyHourGroup').style.display = 'block';
                            }
                            $('#editStaffModal').modal('show');
                        }
                        document.addEventListener('DOMContentLoaded', function() {
                            const nameFilter = document.getElementById('staffNameFilter');
                            const genderFilter = document.getElementById('staffGenderFilter');

                            nameFilter.addEventListener('keyup', filterStaffTable);
                            genderFilter.addEventListener('change', filterStaffTable);
                        });

                        function filterStaffTable() {
                            const nameFilterValue = document.getElementById('staffNameFilter').value.toLowerCase();
                            const genderFilterValue = document.getElementById('staffGenderFilter').value.toLowerCase();
                            const rows = document.querySelectorAll('#staffViewTable tbody tr');

                            rows.forEach(row => {
                                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                                const gender = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                                let showRow = true;

                                if (nameFilterValue && !name.includes(nameFilterValue)) {
                                    showRow = false;
                                }

                                if (genderFilterValue && genderFilterValue !== 'all genders' && genderFilterValue !== gender) {
                                    showRow = false;
                                }

                                if (showRow) {
                                    row.style.display = ''; // Show the row
                                } else {
                                    row.style.display = 'none'; // Hide the row
                                }
                            });
                        }
                    </script>

                    <!-- Patient -->
                    <div class="tab-pane fade show" id="list-patient">
                        <table class="table table-hover" id="patientViewTable">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" id="patientNameFilter" class="form-control" placeholder="Search by Name">
                                </div>
                                <div class="col-md-4">
                                    <select id="patientGenderFilter" class="form-control">
                                        <option value="">All Genders</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                </div>
                            </div>
                            <thead>
                                <tr>
                                    <th style="width: 10%;">Patient ID</th>
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
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const nameFilter = document.getElementById('patientNameFilter');
                            const genderFilter = document.getElementById('patientGenderFilter');

                            nameFilter.addEventListener('keyup', filterPatientTable);
                            genderFilter.addEventListener('change', filterPatientTable);
                        });

                        function filterPatientTable() {
                            const nameFilterValue = document.getElementById('patientNameFilter').value.toLowerCase();
                            const genderFilterValue = document.getElementById('patientGenderFilter').value.toLowerCase();
                            const rows = document.querySelectorAll('#patientViewTable tbody tr');

                            rows.forEach(row => {
                                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                                const gender = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                                let showRow = true;

                                if (nameFilterValue && !name.includes(nameFilterValue)) {
                                    showRow = false;
                                }

                                if (genderFilterValue && genderFilterValue !== 'all genders' && genderFilterValue !== gender) {
                                    showRow = false;
                                }

                                if (showRow) {
                                    row.style.display = ''; // Show the row
                                } else {
                                    row.style.display = 'none'; // Hide the row
                                }
                            });
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>