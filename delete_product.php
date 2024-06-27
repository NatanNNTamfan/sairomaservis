<?php
include 'config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Delete associated sales records
    $sql = "DELETE FROM sales WHERE product_id='$id'";
    $conn->query($sql);

    // Delete product
    $sql = "DELETE FROM products WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Product deleted successfully'
                }).then(function() {
                    window.location = 'inventory.php';
                });
              </script>";
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
    }
}

$conn->close();
?>
