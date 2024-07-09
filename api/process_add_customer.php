<?php
 include 'config.php';
 include 'navbar.html';


 if (!empty($_POST['customer_name']) && !empty($_POST['customer_phone'])) {
     $name = $_POST['customer_name'];
     $phone = $_POST['customer_phone'];
     $email = !empty($_POST['customer_email']) ? $_POST['customer_email'] : null;

     $sql = "INSERT INTO customers (name, phone, email) VALUES ('$name', '$phone', '$email')";
     if ($conn->query($sql) === TRUE) {
         echo "<script>
                 Swal.fire({
                     icon: 'success',
                     title: 'Success',
                     text: 'Customer added successfully'
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