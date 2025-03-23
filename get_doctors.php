<?php
include 'config.php';

if(isset($_POST['dept_id'])) {
    $dept_id = $_POST['dept_id'];
    
    $sql = "SELECT d.user_id, d.first_name, d.last_name, d.specialization, 
                   d.availability, d.doc_fee, dept.dept_name
            FROM Doctor d
            JOIN Department dept ON d.dept_id = dept.dept_id
            WHERE d.dept_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dept_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $output = '';
    
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $output .= '
            <tr>
                <td>'.$row['first_name'].' '.$row['last_name'].'</td>
                <td>'.$row['specialization'].'</td>
                <td>'.$row['availability'].'</td>
                <td>$'.$row['doc_fee'].'</td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm book-btn" 
                            data-doctor-id="'.$row['user_id'].'">
                        Book Appointment
                    </button>
                </td>
            </tr>';
        }
    } else {
        $output = '<tr><td colspan="5">No doctors found in this department</td></tr>';
    }
    
    echo $output;
    $stmt->close();
}
$conn->close();
?>