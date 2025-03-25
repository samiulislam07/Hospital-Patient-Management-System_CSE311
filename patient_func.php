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

  // Ensure patient ID is set
  if (!empty($patient_id)) {
      $update_sql = "UPDATE Patient SET email = ?, gender = ?, blood_group = ?, dob = ?, hno = ?, street = ?, city = ?, zip = ?, country = ? WHERE user_id = ?";
      $stmt = $con->prepare($update_sql);
      if ($stmt) {
          $stmt->bind_param("ssssssssss", $email, $gender, $blood_group, $dob, $hno, $street, $city, $zip, $country, $patient_id);
          if ($stmt->execute()) {
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
        echo '<tr><td colspan="4">Error retrieving data.</td></tr>';
        return;
    }

    if (mysqli_num_rows($result) === 0) {
        echo '<tr><td colspan="4" class="text-center">No pending tests found.</td></tr>';
        return;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['test_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['doctor_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['specialization']) . '</td>';
        echo '<td>' . htmlspecialchars($row['pres_date']) . '</td>';
        echo '</tr>';
    }
}

?>