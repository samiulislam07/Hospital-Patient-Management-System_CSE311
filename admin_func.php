<?php
session_start();
include 'config.php';



function displayDepartmentsTable()
{
    global $con;

    $sql = "SELECT d.dept_id, d.dept_name, 
                   CONCAT(h.first_name, ' ', h.last_name) AS dept_head, 
                   COUNT(s.user_id) AS staff_count
            FROM Department d
            LEFT JOIN Staff s ON d.dept_id = s.dept_id
            LEFT JOIN Staff h ON d.dept_head = h.user_id
            GROUP BY d.dept_id";

    $result = $con->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['dept_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dept_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dept_head'] ? $row['dept_head'] : 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['staff_count'] ? $row['staff_count'] : '0') . "</td>";
                echo "<td><button onclick='showEditModal(" . $row['dept_id'] . ")'>Edit</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No departments found.</td></tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Error: " . mysqli_error($con) . "</td></tr>";
    }
}


// Function to add a department
function addDepartment($deptName)
{
    global $con;

    $deptName = mysqli_real_escape_string($con, $deptName);

    $sql = "INSERT INTO Department (dept_name) VALUES ('$deptName')";

    if ($con->query($sql) === TRUE) {
        return "Department added successfully";
    } else {
        return "Error: " . $sql . "<br>" . $con->error;
    }
}

// Function to delete a department
function deleteDepartment($deptName)
{
    global $con;

    $deptName = mysqli_real_escape_string($con, $deptName);

    $sql = "DELETE FROM Department WHERE dept_name = '$deptName'";

    if ($con->query($sql) === TRUE) {
        return "Department deleted successfully";
    } else {
        return "Error: " . $sql . "<br>" . $con->error;
    }
}

// Handle add department request
if (isset($_POST['addDeptName'])) {
    $deptName = $_POST['addDeptName'];
    $message = addDepartment($deptName);
    echo "<script>alert('$message'); window.location.href = #list-dept;</script>"; // Alert and refresh
}

// Handle delete department request
if (isset($_POST['deleteDeptName'])) {
    $deptName = $_POST['deleteDeptName'];
    $message = deleteDepartment($deptName);
    echo "<script>alert('$message'); window.location.href = #list-dept;</script>"; // Alert and refresh
}

// Function to handle edit department request
function editDepartment($deptId, $docId)
{
    global $con;

    $deptId = mysqli_real_escape_string($con, $deptId);
    $docId = mysqli_real_escape_string($con, $docId);

    // Get staff ID based on doctor ID
    $sqlStaff = "SELECT user_id FROM Staff WHERE user_id = '$docId' AND user_id IN (SELECT user_id from Doctor)";
    $resultStaff = $con->query($sqlStaff);

    if ($resultStaff && $resultStaff->num_rows > 0) {
        $rowStaff = $resultStaff->fetch_assoc();
        $headId = $rowStaff['user_id'];

        // Get current date
        $startDate = date('Y-m-d');

        // Get start date of next edit if it exists
        $sqlNextEdit = "SELECT start_date, head_id FROM HoD WHERE head_id = (SELECT dept_head FROM Department WHERE dept_id = '$deptId') AND end_date IS NULL";
        $resultNextEdit = $con->query($sqlNextEdit);

        if ($resultNextEdit && $resultNextEdit->num_rows > 0) {
            $rowNextEdit = $resultNextEdit->fetch_assoc();
            $endDate = $startDate; // Use the current date as the end date

            // Update the previous head's end date
            $sqlUpdatePrevious = "UPDATE HoD SET end_date = '$endDate' WHERE head_id = '" . $rowNextEdit['head_id'] . "' AND end_date IS NULL";
            $con->query($sqlUpdatePrevious);
        }

        // Insert new head history
        $sqlInsertHistory = "INSERT INTO HoD (doc_id, head_id, start_date) VALUES ('$docId', '$headId', '$startDate')";
        if ($con->query($sqlInsertHistory) === TRUE) {

            // Update the department table with the new head
            $sqlUpdateDept = "UPDATE Department SET dept_head = '$headId' WHERE dept_id = '$deptId'";
            if ($con->query($sqlUpdateDept) === TRUE) {
                return "Department head updated successfully";
            } else {
                return "Error updating Department table: " . $con->error;
            }
        } else {
            return "Error adding head history: " . $con->error;
        }
    } else {
        return "Error: Doctor not found in Staff table.";
    }
}

// Handle edit department request
if (isset($_POST['editDeptId']) && isset($_POST['editDocId'])) {
    $deptId = $_POST['editDeptId'];
    $docId = $_POST['editDocId'];
    $message = editDepartment($deptId, $docId);
    echo "<script>alert('$message'); window.location.href = #list-dept;</script>";
}

// Function to display staff details
function displayStaffTable()
{
    global $con;

    $sql = "SELECT s.user_id, s.first_name, s.last_name, s.email, s.gender, s.phone, s.dob, s.salary, d.dept_name, 
                   doc.doc_fee, doc.specialization, doc.availability, n.duty_hour
            FROM Staff s
            LEFT JOIN Department d ON s.dept_id = d.dept_id
            LEFT JOIN Doctor doc ON s.user_id = doc.user_id
            LEFT JOIN Nurse n ON s.user_id = n.user_id";

    $result = $con->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                echo "<td>" . htmlspecialchars($row['salary']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dept_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['doc_fee']) . "</td>";
                echo "<td>" . htmlspecialchars($row['specialization']) . "</td>";
                echo "<td>" . htmlspecialchars($row['availability']) . "</td>";
                echo "<td>" . htmlspecialchars($row['duty_hour']) . "</td>";
                echo "<td><button onclick='showEditStaffModal(\"" . $row['user_id'] . "\")'>Edit</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='13'>No staff found.</td></tr>";
        }
    } else {
        echo "<tr><td colspan='13'>Error: " . mysqli_error($con) . "</td></tr>";
    }
}
// Function to generate new Doctor ID
function generateDoctorID()
{
    global $con;
    $query = "SELECT user_id FROM Users WHERE user_id LIKE 'd%' ORDER BY user_id DESC LIMIT 1";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['user_id'];
        $num = (int)substr($last_id, 1);
        $new_num = $num + 1;
        return 'd' . str_pad($new_num, 3, '0', STR_PAD_LEFT);
    } else {
        return "d001";
    }
}

// Function to generate new Nurse ID
function generateNurseID()
{
    global $con;
    $query = "SELECT user_id FROM Users WHERE user_id LIKE 'n%' ORDER BY user_id DESC LIMIT 1";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $last_id = $row['user_id'];
        $num = (int)substr($last_id, 1);
        $new_num = $num + 1;
        return 'n' . str_pad($new_num, 3, '0', STR_PAD_LEFT);
    } else {
        return "n001";
    }
}

// Function to add user, staff, doctor, or nurse
function addStaff($firstName, $lastName, $email, $password, $gender, $isDoctor)
{
    global $con;

    $firstName = mysqli_real_escape_string($con, $firstName);
    $lastName = mysqli_real_escape_string($con, $lastName);
    $email = mysqli_real_escape_string($con, $email);
    $gender = mysqli_real_escape_string($con, $gender);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($isDoctor === 1) {
        $userId = generateDoctorID();
    } else {
        $userId = generateNurseID();
    }

    $sqlUser = "INSERT INTO Users (user_id, first_name, last_name, email, password) VALUES ('$userId', '$firstName', '$lastName', '$email', '$hashedPassword')";

    if ($con->query($sqlUser) === TRUE) {
        $sqlStaff = "INSERT INTO Staff (user_id, first_name, last_name, email, password, gender) VALUES ('$userId', '$firstName', '$lastName', '$email', '$hashedPassword', '$gender')";

        if ($con->query($sqlStaff) === TRUE) {
            if ($isDoctor === 1) {
                $sqlDoctor = "INSERT INTO Doctor (user_id, first_name, last_name, email, password, gender) VALUES ('$userId', '$firstName', '$lastName', '$email', '$hashedPassword', '$gender')";

                if ($con->query($sqlDoctor) === TRUE) {
                    return "Doctor added successfully";
                } else {
                    return "Error adding doctor: " . $con->error;
                }
            } else {

                $sqlNurse = "INSERT INTO Nurse (user_id, first_name, last_name, email, password, gender) VALUES ('$userId', '$firstName', '$lastName', '$email', '$hashedPassword', '$gender')";

                if ($con->query($sqlNurse) === TRUE) {
                    return "Nurse added successfully";
                } else {
                    return "Error adding nurse: " . $con->error;
                }
            }
        } else {
            return "Error adding staff: " . $con->error;
        }
    } else {
        return "Error adding user: " . $con->error;
    }
}

// Handle add user staff request
if (isset($_POST['addStaff'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $isDoctor = isset($_POST['isDoctor']) ? intval($_POST['isDoctor']) : 0;

    $message = addStaff($firstName, $lastName, $email, $password, $gender, $isDoctor);
    echo "<script>alert('$message'); window.location.href = #list-staff;</script>";
}

// Function to delete staff (placeholder)
function deleteStaff($userId)
{
    // Implement your delete staff logic here
    global $con;
    $userId = mysqli_real_escape_string($con, $userId);

    $sql = "DELETE FROM Users WHERE user_id = '$userId'";

    if ($con->query($sql) === TRUE) {
        return "Staff deleted successfully";
    } else {
        return "Error: " . $sql . "<br>" . $con->error;
    }
}

// Handle delete staff request
if (isset($_POST['UserId'])) {
    $userId = $_POST['UserId'];
    $message = deleteStaff($userId);
    echo "<script>alert('$message'); window.location.href = #list-staff ;</script>";
}

// Function to edit staff details
function editStaff($userId, $salary, $dutyHour, $deptId)
{
    global $con;

    $userId = mysqli_real_escape_string($con, $userId);
    $salary = mysqli_real_escape_string($con, $salary);
    $deptId = mysqli_real_escape_string($con, $deptId);

    if (strpos($userId, 'd') === 0) { // Check if Doctor
        $sqlDoctorUpdate = "UPDATE Doctor SET salary = '$salary', dept_id = '$deptId' WHERE user_id = '$userId'";

        if ($con->query($sqlDoctorUpdate) === TRUE) {
            $sqlStaffUpdate = "UPDATE Staff SET salary = '$salary', dept_id = '$deptId' WHERE user_id = '$userId'";
            if ($con->query($sqlStaffUpdate) === TRUE) {
                return "Doctor details updated successfully";
            } else {
                return "Error updating staff table: " . $con->error;
            }
        } else {
            return "Error updating doctor table: " . $con->error;
        }
    } else if (strpos($userId, 'n') === 0) { // Check if Nurse
        $dutyHour = mysqli_real_escape_string($con, $dutyHour);
        $validDutyHours = array('Morning', 'Noon', 'Evening', 'Night', 'Rotational');

        if (in_array($dutyHour, $validDutyHours)) {
            $sqlNurseUpdate = "UPDATE Nurse SET salary = '$salary', duty_hour = '$dutyHour', dept_id = '$deptId' WHERE user_id = '$userId'";

            if ($con->query($sqlNurseUpdate) === TRUE) {
                $sqlStaffUpdate = "UPDATE Staff SET salary = '$salary', dept_id = '$deptId' WHERE user_id = '$userId'";
                if ($con->query($sqlStaffUpdate) === TRUE) {
                    return "Nurse details updated successfully";
                } else {
                    return "Error updating staff table: " . $con->error;
                }
            } else {
                return "Error updating nurse table: " . $con->error;
            }
        } else {
            return "Invalid duty hour.";
        }
    } else {
        return "Invalid user ID.";
    }
}

// Handle edit staff request
if (isset($_POST['editStaff'])) {
    $userId = $_POST['userId'];
    $salary = $_POST['salary'];
    $dutyHour = $_POST['dutyHour'];
    $deptId = $_POST['deptId'];

    $message = editStaff($userId, $salary, $dutyHour, $deptId);
    echo "<script>alert('$message'); window.location.href = #list-staff;</script>";
}


// Function to fetch patient data with mobile numbers
function displayPatientTable()
{
    global $con;

    $sql = "SELECT p.user_id, p.first_name, p.last_name, p.gender, p.email, 
            GROUP_CONCAT(pm.mobile SEPARATOR ', ') AS mobile, 
            p.hno, p.street, p.city, p.zip, p.country 
            FROM Patient p 
            LEFT JOIN Patient_Mobile pm ON p.user_id = pm.patient_user_id";
    $sql .= " GROUP BY p.user_id";

    $result = $con->query($sql);
    if ($result) { // Check if the query was successful
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td style='width: 10%;'>" . htmlspecialchars($row['user_id']) . "</td>";
                echo "<td style='width: 15%;'>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                echo "<td style='width: 15%;'>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td style='width: 15%;'>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td style='width: 15%;'>" . htmlspecialchars($row['mobile']) . "</td>";
                echo "<td style='width: 20%;'>";
                echo htmlspecialchars($row['hno'] . ', ' . $row['street'] . ', ' . $row['city'] . ', ' . $row['zip'] . ', ' . $row['country']);
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tbody id='patientTableBody'><tr><td colspan='6'>No patients found.</td></tr></tbody>";
        }
    } else {
        echo "Error: " . mysqli_error($con); // Display the error message
    }
}
