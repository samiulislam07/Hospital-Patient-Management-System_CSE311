<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$patient_id = $_SESSION['user_id'];
$patient = [];

// Fetch patient details
$sql = "SELECT user_id, first_name, last_name, email, gender, blood_group, dob, hno, street, city, zip, country FROM Patient WHERE user_id = ?";
$stmt = $con->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $patient = $result->fetch_assoc();
    }
    $stmt->close();
}

// Fetch patient's phone numbers (up to 2) from Patient_Mobile table
$sqlPhones = "SELECT mobile FROM Patient_Mobile WHERE patient_user_id = ? LIMIT 2";
$stmtPhones = $con->prepare($sqlPhones);
if ($stmtPhones) {
    $stmtPhones->bind_param("s", $patient_id);
    $stmtPhones->execute();
    $resultPhones = $stmtPhones->get_result();
    $phoneNumbers = [];
    while ($row = $resultPhones->fetch_assoc()) {
        $phoneNumbers[] = $row['mobile'];
    }
    $stmtPhones->close();
    $patient['phno1'] = isset($phoneNumbers[0]) ? $phoneNumbers[0] : "";
    $patient['phno2'] = isset($phoneNumbers[1]) ? $phoneNumbers[1] : "";
} else {
    // If the phone query fails, default to empty strings
    $patient['phno1'] = "";
    $patient['phno2'] = "";
}

// Update Patient Profile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_patient'])) {
  $email = $_POST['email'];
  $gender = $_POST['gender'];
  $blood_group = $_POST['blood_group'];
  $dob = $_POST['dob'];
  $hno = $_POST['hno'];
  $street = $_POST['street'];
  $city = $_POST['city'];
  $zip = $_POST['zip'];
  $country = $_POST['country'];

  // Get phone numbers from the form
  $phno1 = trim($_POST['phno1']);
  $phno2 = trim($_POST['phno2']);

  // Ensure patient ID is set
  if (!empty($patient_id)) {
    // Update Patient table
      $update_sql = "UPDATE Patient SET email = ?, gender = ?, blood_group = ?, dob = ?, hno = ?, street = ?, city = ?, zip = ?, country = ? WHERE user_id = ?";
      $stmt = $con->prepare($update_sql);
      if ($stmt) {
          $stmt->bind_param("ssssssssss", $email, $gender, $blood_group, $dob, $hno, $street, $city, $zip, $country, $patient_id);
          if ($stmt->execute()) {
            // Now update the phone numbers.
            // Delete existing phone numbers for this patient
            $delete_sql = "DELETE FROM Patient_Mobile WHERE patient_user_id = ?";
            $stmt_del = $con->prepare($delete_sql);
            if ($stmt_del) {
                $stmt_del->bind_param("s", $patient_id);
                $stmt_del->execute();
                $stmt_del->close();
            }

            // Insert new phone numbers (if provided)
            $insert_sql = "INSERT INTO Patient_Mobile (patient_user_id, mobile) VALUES (?, ?)";
            $stmt_ins = $con->prepare($insert_sql);
            if ($stmt_ins) {
                if (!empty($phno1)) {
                    $stmt_ins->bind_param("ss", $patient_id, $phno1);
                    $stmt_ins->execute();
                }
                if (!empty($phno2)) {
                    $stmt_ins->bind_param("ss", $patient_id, $phno2);
                    $stmt_ins->execute();
                }
                $stmt_ins->close();
            }
              echo "<script>alert('Profile updated successfully!'); window.location.href='patient_dashboard.php';</script>";
          } else {
              echo "<script>alert('Error updating profile. Please try again.');</script>";
          }
          $stmt->close();
      } else {
          echo "<script>alert('Database error. Please try again.');</script>";
      }
  } else {
      echo "<script>alert('Doctor ID missing. Cannot update profile.');</script>";
  }
}

//fetching doctors
function display_specs() {
  global $con;
  $query="select distinct(specialization) from doctor";
  $result=mysqli_query($con,$query);
  while($row=mysqli_fetch_array($result))
  {
    $spec=$row['specialization'];
    echo '<option value="'.$spec.'">'.$spec.'</option>';
  }
}

function display_docs()
{
 global $con;
 $query = "select * from doctor";
 $result = mysqli_query($con,$query);
 while( $row = mysqli_fetch_array($result) )
 {
  $userid = $row['user_id'];
  $doctor_name = $row['first_name'] . ' ' . $row['last_name'];
  $spec=$row['specialization'];
  $docFee = $row['doc_fee'];
  $availability = $row['availability'];
  echo '<option data-name="' .$doctor_name. '" data-spec="' .$spec. '" data-fee="'.$docFee.'" data-availability="' . $availability . '" data-id="' .$userid. '">'.$doctor_name.'</option>';
 }
}

//Appointment booking
if (isset($_POST['app-submit'])) {
    $appt_date = mysqli_real_escape_string($con, $_POST['appdate']);
    $appt_time = mysqli_real_escape_string($con, $_POST['appointmentTime']);
    $doctor_user_id = mysqli_real_escape_string($con, $_POST['docId']);
  
    if (isset($_SESSION['user_id'])) {
        $patient_user_id = mysqli_real_escape_string($con, $_SESSION['user_id']);
    } else {
        echo "<script>alert('Patient user ID not found. Please log in.');</script>";
        exit();
    }
  
    $insert_appointment_query = "INSERT INTO Appointment (appt_date, appt_time) VALUES ('$appt_date', '$appt_time')";
    if (mysqli_query($con, $insert_appointment_query)) {
        $appt_id = mysqli_insert_id($con);
  
        $insert_checkup_query = "INSERT INTO Checkup (appt_id, patient_user_id, doctor_user_id, appt_status) VALUES (?, ?, ?, 'scheduled')";
        $stmt = mysqli_prepare($con, $insert_checkup_query);
        mysqli_stmt_bind_param($stmt, "iss", $appt_id, $patient_user_id, $doctor_user_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Appointment created successfully!'); window.location.href='patient_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error creating appointment (Checkup): " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Error creating appointment (Appointment): " . mysqli_error($con) . "');</script>";
    }
  }

//fetch Pending Tests
function display_pending_tests()
{
    global $con;

    // Assuming patient is logged in
    $patient_id = $_SESSION['user_id'];

    $query = "SELECT 
                t.test_name,
                CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
                d.specialization,
                dtp.pres_date
              FROM doc_test_patient dtp
              JOIN test t ON t.test_id = dtp.test_id
              JOIN doctor d ON d.user_id = dtp.doctor_user_id
              WHERE dtp.patient_user_id = '$patient_id' AND dtp.test_date IS NULL";

    $result = mysqli_query($con, $query);

    if (!$result) {
        echo '<tr><td colspan="5">Error retrieving data.</td></tr>';
        return;
    }

    if (mysqli_num_rows($result) === 0) {
        echo '<tr><td colspan="5" class="text-center">No pending tests found.</td></tr>';
        return;
    }

    $counter = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $counter++ . '</td>';
        echo '<td>' . htmlspecialchars($row['test_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['doctor_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['specialization']) . '</td>';
        echo '<td>' . htmlspecialchars($row['pres_date']) . '</td>';
        echo '</tr>';
    }
}

//fetch Test Results
function display_test_results()
{
    global $con;
    if (!isset($_SESSION['user_id'])) {
        echo "<tr><td colspan='7' class='text-center text-danger'>Session expired. Please log in again.</td></tr>";
        return;
    }

    $patient_id = $_SESSION['user_id'];

    // Query: fetch test_name, test_date, result, doctor name, specialization, pres_date
    // Only rows with test_date and result not null
    // Sort by test_date descending
    $sql = "SELECT 
                dtp.test_date,
                dtp.result,
                dtp.pres_date,
                t.test_name,
                CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
                d.specialization
            FROM doc_test_patient dtp
            JOIN test t ON dtp.test_id = t.test_id
            JOIN doctor d ON dtp.doctor_user_id = d.user_id
            WHERE dtp.patient_user_id = ?
              AND dtp.test_date IS NOT NULL
              AND dtp.result IS NOT NULL
            ORDER BY dtp.test_date DESC";

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        echo "<tr><td colspan='7' class='text-danger text-center'>Database error: Unable to prepare statement.</td></tr>";
        return;
    }

    // patient_id is varchar, so bind as string "s"
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        echo "<tr><td colspan='7' class='text-danger text-center'>Error fetching data.</td></tr>";
        $stmt->close();
        return;
    }

    if ($result->num_rows === 0) {
        echo "<tr><td colspan='7' class='text-center'>No test result found.</td></tr>";
    } else {
        $counter = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $counter++ . "</td>";
            echo "<td>" . htmlspecialchars($row['test_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['test_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['result']) . "</td>";
            echo "<td>" . htmlspecialchars($row['doctor_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['specialization']) . "</td>";
            echo "<td>" . htmlspecialchars($row['pres_date']) . "</td>";
            echo "</tr>";
        }
    }

    $stmt->close();
}


?>