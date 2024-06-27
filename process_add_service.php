<?php
 include 'config.php';

 if (!empty($_POST['customer_id']) && !empty($_POST['description']) && !empty($_POST['status'])) {        
     $customer_id = $_POST['customer_id'];
     $description = $_POST['description'];
     $status = $_POST['status'];
     $cost = !empty($_POST['cost']) ? $_POST['cost'] : null;

     $sql = "INSERT INTO services (customer_id, description, status, cost) VALUES ('$customer_id',        
 '$description', '$status', '$cost')";
     if ($conn->query($sql) === TRUE) {
         echo "<script>
                 Swal.fire({
                     icon: 'success',
                     title: 'Success',
                     text: 'Service added successfully'
                 }).then(function() {
                     window.location = 'index.php';
                 });
               </script>";
     } else {
         echo "<script>
                 Swal.fire({
                     icon: 'error',
                     title: 'Error',
                     text: 'Error: " . $conn->error . "'
                 }).then(function() {
                     window.location = 'index.php';
                 });
               </script>";
     }
 } else {
     echo "<script>
             Swal.fire({
                 icon: 'warning',
                 title: 'Warning',
                 text: 'All fields are required'
             }).then(function() {
                 window.location = 'index.php';
             });
           </script>";
 }

 $conn->close();
 exit();