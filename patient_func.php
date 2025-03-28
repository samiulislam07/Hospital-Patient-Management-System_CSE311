<?php
session_start();
include 'config.php';

// Check if the user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: index.php");
    exit();
}

$current_session = session_id();
$user_id = $_SESSION['user_id'];

// Fetch the stored session id from the Patient table
$sql = "SELECT session_id FROM Patient WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row || $row['session_id'] !== $current_session) {
    // Session mismatch: possibly logged in from another device
    session_destroy();
    header("Location: index.php?error=Your account was logged in from another device. Please log in again.");
    exit();
}

$patient_id = $_SESSION['user_id'];
$patient = [];
$medHistory = [];

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

//fetching medical history
$sqlMedHis = "SELECT * FROM MedicalHistory WHERE patient_user_id = ?";
$stmtMedHis = $con->prepare($sqlMedHis);
if ($stmtMedHis !== false) {
    $stmtMedHis->bind_param("s", $patient_id);
    if ($stmtMedHis->execute()) {
        $resultMedHis = $stmtMedHis->get_result();
        if ($resultMedHis !== false && $resultMedHis->num_rows > 0) {
            $medHistory = $resultMedHis->fetch_assoc();
        } else {
            $medHistory = null;
        }
    } else {
        $medHistory = null;
    }
    $stmtMedHis->close();
} else {
    $medHistory = null;
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

  // Get medical histories from the form
  $allergies = trim($_POST['allergies']);
  $preconditions = trim($_POST['preconditions']);

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
            // Update Medical History: try to update first
            $update_med_sql = "UPDATE MedicalHistory SET allergies = ?, pre_conditions = ? WHERE patient_user_id = ?";
            $stmt_med = $con->prepare($update_med_sql);
            if ($stmt_med) {
                $stmt_med->bind_param("sss", $allergies, $preconditions, $patient_id);
                $stmt_med->execute();
                // If no rows were updated, insert a new record.
                if ($stmt_med->affected_rows == 0) {
                    $stmt_med->close();
                    $insert_med_sql = "INSERT INTO MedicalHistory (patient_user_id, allergies, pre_conditions) VALUES (?, ?, ?)";
                    $stmt_med = $con->prepare($insert_med_sql);
                    if ($stmt_med) {
                        $stmt_med->bind_param("sss", $patient_id, $allergies, $preconditions);
                        $stmt_med->execute();
                        $stmt_med->close();
                    } else {
                        echo "<script>alert('Database error while inserting medical history.');</script>";
                    }
                } else {
                    $stmt_med->close();
                }
            } else {
                echo "<script>alert('Database error while updating medical history.');</script>";
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

//Fetch Appointment History
function display_appointment_history()
{
    global $con;

    // Ensure the patient is logged in
    if (!isset($_SESSION['user_id'])) {
        echo '<tr><td colspan="7" class="text-center text-danger">Session expired. Please log in again.</td></tr>';
        return;
    }

    $patient_id = $_SESSION['user_id'];

    // Query to get all appointments for this patient
    $sql = "SELECT 
                A.appt_id,
                A.appt_date,
                A.appt_time,
                C.appt_status,
                CONCAT(D.first_name, ' ', D.last_name) AS doctor_name,
                D.specialization
            FROM checkup C
            JOIN appointment A ON A.appt_id = C.appt_id
            JOIN doctor D ON D.user_id = C.doctor_user_id
            WHERE C.patient_user_id = ?
            ORDER BY A.appt_date ASC, A.appt_time ASC";

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        echo '<tr><td colspan="7" class="text-center text-danger">Database error: Unable to prepare statement.</td></tr>';
        return;
    }

    // Bind the parameter (assuming patient_user_id is a string/varchar)
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        echo '<tr><td colspan="7" class="text-center text-danger">Error retrieving data.</td></tr>';
        $stmt->close();
        return;
    }

    if ($result->num_rows === 0) {
        echo '<tr><td colspan="7" class="text-center">No appointments found.</td></tr>';
    } else {
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            $appt_id   = $row['appt_id'];
            $doctor    = $row['doctor_name'];
            $spec      = $row['specialization'];
            $date      = $row['appt_date'];
            $time      = $row['appt_time'];
            $status    = $row['appt_status'];

            // If the appointment is already completed/cancelled/missed, disable the Cancel button
            if (in_array($status, ['Completed', 'Cancelled', 'Missed'])) {
                $actionBtn = '<button class="btn btn-secondary btn-sm" disabled>Cancel</button>';
            } else {
                // Otherwise, allow cancellation. You might pass appt_id to an AJAX or separate script.
                $actionBtn = '<button class="btn btn-danger btn-sm" onclick="cancelAppointment('.$appt_id.')">Cancel</button>';
            }

            echo '<tr>';
            echo '<td>'.$count++.'</td>';
            echo '<td>'.htmlspecialchars($doctor).'</td>';
            echo '<td>'.htmlspecialchars($spec).'</td>';
            echo '<td>'.htmlspecialchars($date).'</td>';
            echo '<td>'.htmlspecialchars($time).'</td>';
            echo '<td>'.htmlspecialchars($status).'</td>';
            echo '<td>'.$actionBtn.'</td>';
            echo '</tr>';
        }
    }
    $stmt->close();
}

function cancel_appointment($appt_id) {
    global $con;
    
    // Prepare query to update appointment status to 'Cancelled'
    $sql = "UPDATE checkup SET appt_status = 'Cancelled' 
            WHERE appt_id = ? AND appt_status NOT IN ('Completed', 'Cancelled', 'Missed')";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        return array("error" => "Database error: " . $con->error);
    }
    
    $stmt->bind_param("i", $appt_id);
    if ($stmt->execute()) {
        $stmt->close();
        return array("success" => true);
    } else {
        $stmt->close();
        return array("error" => "Failed to cancel appointment.");
    }
}

//fetch Treatment Plans
function display_treatment_plans() {
    global $con;
    
    // Check if patient is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<tr><td colspan='6' class='text-center text-danger'>Session expired. Please log in again.</td></tr>";
        return;
    }
    
    $patient_id = $_SESSION['user_id'];
    
    // Query to fetch treatment plan details along with doctor's name and specialization
    $sql = "SELECT 
                tp.trtplan_id, 
                tp.prescribe_date, 
                tp.dosage, 
                tp.suggestion,
                CONCAT(d.first_name, ' ', d.last_name) AS doctor_name,
                d.specialization
            FROM TreatmentPlan tp
            JOIN doctor d ON tp.doctor_user_id = d.user_id
            WHERE tp.patient_user_id = ?
            ORDER BY tp.prescribe_date DESC";
            
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        echo "<tr><td colspan='6' class='text-center text-danger'>Database error: Unable to prepare statement.</td></tr>";
        return;
    }
    
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        echo "<tr><td colspan='6' class='text-center text-danger'>Error fetching data.</td></tr>";
        $stmt->close();
        return;
    }
    
    if ($result->num_rows === 0) {
        echo "<tr><td colspan='6' class='text-center'>No treatment plans found.</td></tr>";
    } else {
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            $prescribe_date = htmlspecialchars($row['prescribe_date']);
            $doctor_name = htmlspecialchars($row['doctor_name']);
            $specialization = htmlspecialchars($row['specialization']);
            $dosage = htmlspecialchars($row['dosage']);
            $suggestion = htmlspecialchars($row['suggestion']);
            
            echo "<tr>";
            echo "<td>" . $count++ . "</td>";
            echo "<td>{$prescribe_date}</td>";
            echo "<td>{$doctor_name}</td>";
            echo "<td>{$specialization}</td>";
            echo "<td>{$dosage}</td>";
            echo "<td>{$suggestion}</td>";
            echo "</tr>";
        }
    }
    
    $stmt->close();
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

    // Base query
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
              AND dtp.result IS NOT NULL";

    // Collect filter parameters from GET if available
    $filters = [];
    $types = "s";  // initial type for patient_id

    // If a test name filter is provided, add it to the query.
    if (!empty($_GET['testNameFilter'])) {
        $sql .= " AND t.test_name LIKE ?";
        $filters[] = "%" . $_GET['testNameFilter'] . "%";
        $types .= "s";
    }

    // If a test date filter is provided, add it to the query.
    if (!empty($_GET['testDateFilter'])) {
        $sql .= " AND dtp.test_date = ?";
        $filters[] = $_GET['testDateFilter'];
        $types .= "s";
    }

    $sql .= " ORDER BY dtp.test_date DESC";

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        echo "<tr><td colspan='7' class='text-danger text-center'>Database error: Unable to prepare statement.</td></tr>";
        return;
    }

    // Prepare the parameters: first parameter is patient_id, then the filters
    $params = array_merge([$patient_id], $filters);

    // Bind parameters dynamically
    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        echo "<tr><td colspan='7' class='text-danger text-center'>Error fetching data.</td></tr>";
        $stmt->close();
        return;
    }

    if ($result->num_rows === 0) {
        echo "<tr><td colspan='7' class='text-center'>No test results found.</td></tr>";
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

//fetch bills
function display_due_bills_and_subtotal()
{
    global $con;
    
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo '<tr><td colspan="5" class="text-center text-danger">Session expired. Please log in again.</td></tr>';
        return 0;
    }

    $patient_id = $_SESSION['user_id'];
    
    // Fetch Due bills, left-joining doctor and test tables
    $sql = "SELECT 
                b.bill_detail_id,
                b.patient_user_id,
                b.doctor_user_id,
                b.test_id,
                b.charge_amount,
                b.status,
                d.first_name AS doc_fname,
                d.last_name AS doc_lname,
                t.test_name
            FROM Bill_detail b
            LEFT JOIN doctor d ON b.doctor_user_id = d.user_id
            LEFT JOIN test t ON b.test_id = t.test_id
            WHERE b.patient_user_id = ? AND b.status = 'Due'";

    $stmt = $con->prepare($sql);
    if (!$stmt) {
        echo '<tr><td colspan="5" class="text-danger text-center">Database error: Unable to prepare statement.</td></tr>';
        return 0;
    }

    // patient_user_id is varchar, so bind as string
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        echo '<tr><td colspan="5" class="text-danger text-center">Error fetching data.</td></tr>';
        $stmt->close();
        return 0;
    }

    // Subtotal accumulator
    $subtotal = 0;
    $counter = 1;

    if ($result->num_rows === 0) {
        // No due bills found
        echo '<tr><td colspan="5" class="text-center">No Due Bills Found.</td></tr>';
    } else {
        // Populate each row
        while ($row = $result->fetch_assoc()) {
            $billType = "";
            $doctorName = "";
            $testName = "";

            // Determine if it's Test Fee or Consultancy Fee
            if (!empty($row['test_id'])) {
                // Test Fee
                $billType = "Test Fee";
                $testName = $row['test_name'] ?? "-";
                $doctorName = "-";
            } else {
                // Consultancy Fee
                $billType = "Consultancy Fee";
                $doctorName = ($row['doc_fname'] || $row['doc_lname'])
                    ? $row['doc_fname'] . " " . $row['doc_lname']
                    : "-";
                $testName = "-";
            }

            $amount = $row['charge_amount'];
            $subtotal += (float)$amount;

            echo "<tr>
                    <td>{$counter}</td>
                    <td>{$billType}</td>
                    <td>{$doctorName}</td>
                    <td>{$testName}</td>
                    <td>\${$amount}</td>
                  </tr>";

            $counter++;
        }
    }

    $stmt->close();
    // Return the total
    return $subtotal;
}

function pay_due_bills() {
    global $con;
    
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        return array("error" => "Unauthorized");
    }
    
    $patient_id = $_SESSION['user_id'];
    
    // Update all due bills for this patient to 'Paid'
    $sql = "UPDATE Bill_detail SET status = 'Paid' WHERE patient_user_id = ? AND status = 'Due'";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        return array("error" => "Database error: Unable to prepare statement.");
    }
    
    // Since patient_user_id is varchar, we bind as a string ("s")
    $stmt->bind_param("s", $patient_id);
    if ($stmt->execute()) {
        $stmt->close();
        return array("success" => true);
    } else {
        $stmt->close();
        return array("error" => "Could not update bill status.");
    }
}



?>