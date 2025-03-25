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
                        <div class="col-md-8" style="margin-top: 3%;">
                            <div class="tab-content" id="nav-tabContent" style="width: 1200px;">
                                <div class="tab-pane fade show active" id="list-dept">
                                    <div class="tab-pane fade show active" id="list-dept">
                                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addDepartmentModal">Add Department</button>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Department Name</th>
                                                    <th>Department Head</th>
                                                    <th>Staff Count</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="departmentsTableBody">
                                                <tr>
                                                    <td>1</td>
                                                    <td>Cardiology</td>
                                                    <td>Dr. Smith</td>
                                                    <td>10</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info edit-dept" data-toggle="modal" data-target="#editDepartmentModal">Edit</button>
                                                        <button class="btn btn-sm btn-danger delete-dept">Delete</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>Neurology</td>
                                                    <td>Dr. Jones</td>
                                                    <td>8</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info edit-dept" data-toggle="modal" data-target="#editDepartmentModal">Edit</button>
                                                        <button class="btn btn-sm btn-danger delete-dept">Delete</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="addDepartmentModalLabel">Add Department</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="addDepartmentForm">
                                                            <div class="form-group">
                                                                <label for="deptName">Department Name</label>
                                                                <input type="text" class="form-control" id="deptName" required>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Add</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="editDepartmentForm">
                                                            <input type="hidden" id="editDeptId" value="1">
                                                            <div class="form-group">
                                                                <label for="editDeptName">Department Name</label>
                                                                <input type="text" class="form-control" id="editDeptName" value="Cardiology" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="editDeptHead">Department Head</label>
                                                                <select class="form-control" id="editDeptHead">
                                                                    <option value="1">Dr. Smith</option>
                                                                    <option value="2">Dr. Jones</option>
                                                                </select>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                <h5> Patient Operations </h5>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
<script> document.addEventListener('DOMContentLoaded', function () {
    function loadDepartments() {
        fetch('get_departments.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('departmentsTableBody');
                tableBody.innerHTML = '';
                data.forEach(department => {
                    const row = `
                        <tr>
                            <td>${department.dept_id}</td>
                            <td>${department.dept_name}</td>
                            <td>${department.dept_head || 'N/A'}</td>
                            <td>${department.staff_count}</td>
                            <td>
                                <button class="btn btn-sm btn-info edit-dept" data-id="${department.dept_id}" data-name="${department.dept_name}" data-head="${department.dept_head}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-dept" data-id="${department.dept_id}">Delete</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });

                // Add event listeners for edit and delete buttons
                document.querySelectorAll('.edit-dept').forEach(button => {
                    button.addEventListener('click', editDepartment);
                });
                document.querySelectorAll('.delete-dept').forEach(button => {
                    button.addEventListener('click', deleteDepartment);
                });
            });
    }

    function addDepartment(event) {
        event.preventDefault();
        const deptName = document.getElementById('deptName').value;
        fetch('add_department.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ deptName: deptName })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadDepartments();
                    $('#addDepartmentModal').modal('hide');
                    document.getElementById('deptName').value = ''; // Clear the input field
                } else {
                    alert('Failed to add department.');
                }
            });
    }

    function populateDoctorDropdown(deptId) {
        fetch('crud_dept.php')
            .then(response => response.json())
            .then(doctors => {
                const dropdown = document.getElementById('editDeptHead');
                dropdown.innerHTML = '<option value="">Select Doctor</option>';
                doctors.forEach(doctor => {
                    const option = `<option value="${doctor.user_id}" data-name="${doctor.first_name} ${doctor.last_name}">${doctor.first_name} ${doctor.last_name}</option>`;
                    dropdown.innerHTML += option;
                });
            });
    }

    function updateDepartmentHead(event) {
        event.preventDefault();

        const deptId = document.getElementById('editDeptId').value;
        const doctorId = document.getElementById('editDeptHead').value;
        const doctorName = document.getElementById('editDeptHead').options[document.getElementById('editDeptHead').selectedIndex].dataset.name;

        fetch('crud_dept.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `deptId=${deptId}&doctorId=${doctorId}&doctorName=${doctorName}`,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadDepartments();
                    $('#editDepartmentModal').modal('hide');
                } else {
                    alert('Failed to update department head.');
                }
            });
    }

    function editDepartment(event) {
        const button = event.target;
        const deptId = button.dataset.id;
        const deptName = button.dataset.name;
        const deptHead = button.dataset.head;

        document.getElementById('editDeptId').value = deptId;
        document.getElementById('editDeptName').value = deptName;

        populateDoctorDropdown(deptId);
        $('#editDepartmentModal').modal('show');
    }

    function deleteDepartment(event) {
        const button = event.target;
        const deptId = button.dataset.id;
        if (confirm('Are you sure you want to delete this department?')) {
            fetch('crud_dept.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ deptId: deptId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadDepartments();
                    } else {
                        alert('Failed to delete department.');
                    }
                });
        }
    }

    loadDepartments();

    document.getElementById('addDepartmentForm').addEventListener('submit', addDepartment);
    document.getElementById('editDepartmentForm').addEventListener('submit', updateDepartmentHead);

});</script>
                <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>