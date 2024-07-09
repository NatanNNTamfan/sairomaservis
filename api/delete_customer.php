<?php
include 'config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Delete customer
    $sql = "DELETE FROM customers WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Customer deleted successfully'
                }).then(function() {
                    window.location = 'index.php';
                });
              </script>";
        exit();
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: " . $sql . "<br>" . $conn->error . "'
                }).then(function() {
                    window.location = 'index.php';
                });
              </script>";
        exit();
    }
}

$conn->close();
?>
