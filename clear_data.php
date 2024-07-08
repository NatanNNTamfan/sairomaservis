<?php
include 'config.php';

$sql = "DELETE FROM sales";
$conn->query($sql);

// Clear all data from products table
$sql = "DELETE FROM products";
$conn->query($sql);

$conn->close();

// Redirect back to the index page
echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'All data cleared successfully'
        }).then(function() {
            window.location = 'index.php';
        });
      </script>";
      exit();
?>
