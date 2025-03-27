<?php
include 'config.php'; 

function displayDepartmentsTable() {
    global $con;

    // SQL query to fetch department data, including department head's name
    $sql = "SELECT dept_name, dept_head, staff_count 
            FROM Department";

    $result = $con->query($sql);

    if ($result) { // Check if the query was successful
        if ($result->num_rows > 0) {
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $counter . "</td>";
                echo "<td>" . htmlspecialchars($row['dept_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dept_head'] ? $row['dept_head'] : 'N/A') . "</td>";
                echo "<td>" . htmlspecialchars($row['staff_count'] ? $row['staff_count'] : '0') . "</td>";
                echo "</tr>";
                $counter++;
            }

        } else {
            echo "<p>No departments found.</p>";
        }
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Function to fetch patient data with mobile numbers
function displayPatientTable() {
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
            $counter = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td style='width: 10%;'>" . $counter . "</td>";
                echo "<td style='width: 15%;'>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                echo "<td style='width: 15%;'>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td style='width: 15%;'>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td style='width: 15%;'>" . htmlspecialchars($row['mobile']) . "</td>";
                echo "<td style='width: 20%;'>";
                echo htmlspecialchars($row['hno'] . ', ' . $row['street'] . ', ' . $row['city'] . ', ' . $row['zip'] . ', ' . $row['country']);
                echo "</td>";
                echo "</tr>";
                $counter++;
            }
        } else {
            echo "<tbody id='patientTableBody'><tr><td colspan='6'>No patients found.</td></tr></tbody>";
        }
    } else {
        echo "Error: " . mysqli_error($con); // Display the error message
    }
}


?>
