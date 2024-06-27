<?php
include 'config.php';

if (!empty($_POST['name']) && !empty($_POST['hargabeli']) && !empty($_POST['stock']) && !empty($_POST['kategori']) && !empty($_POST['merk'])) {
    $name = $_POST['merk'] . ' ' . $_POST['name'] . ' ' . $_POST['kategori'];
    $hargabeli = str_replace(['Rp ', '.'], '', $_POST['hargabeli']);
    $stock = $_POST['stock'];
    $kategori = $_POST['kategori'];
    $merk = $_POST['merk'];

    // Check for duplicate product
    $check_sql = "SELECT * FROM products WHERE name='$name' AND kategori='$kategori' AND merk='$merk'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Product already exists'
                }).then(function() {
                    window.location = 'inventory.php';
                });
              </script>";
    } else {
        $sql = "INSERT INTO products (name, hargabeli, stock, kategori, merk) VALUES ('$name', '$hargabeli', '$stock', '$kategori', '$merk')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'New product added successfully'
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
                        text: 'Error: " . $conn->error . "'
                    }).then(function() {
                        window.location = 'inventory.php';
                    });
                  </script>";
            exit();
        }
    }
} else {
    echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'All fields are required'
            }).then(function() {
                window.history.back();
            });
          </script>";
    exit();
}

$conn->close();
?>
