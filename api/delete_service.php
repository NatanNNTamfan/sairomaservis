<?php
 include 'config.php';
 include 'navbar.html';


 if (isset($_POST['id'])) {
     $id = $conn->real_escape_string($_POST['id']);

     // Delete the service from the database
     $sql = "DELETE FROM services WHERE id = ?";
     $stmt = $conn->prepare($sql);
     $stmt->bind_param("i", $id);

     if ($stmt->execute()) {
         echo json_encode(['status' => 'success']);
     } else {
         echo json_encode(['status' => 'error', 'message' => 'Failed to delete service']);
     }

     $stmt->close();
 } else {
     echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
 }

 $conn->close();
 ?>