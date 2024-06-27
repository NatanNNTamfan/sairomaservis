<?php
include 'config.php';

if (isset($_POST['id']) && isset($_POST['add_stock'])) {
    $id = $_POST['id'];
    $add_stock = $_POST['add_stock'];

    // Update stock
    $sql = "UPDATE products SET stock = stock + $add_stock WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Stock updated successfully'
                }).then(function() {
                    window.location = 'inventory.php';
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
                    window.location = 'inventory.php';
                });
              </script>";
        exit();
    }
}

$conn->close();
?>
