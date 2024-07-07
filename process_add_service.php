<?php
include 'config.php';

if (!empty($_POST['customer_id']) && !empty($_POST['description']) && !empty($_POST['status'])) {
    $customer_id = $_POST['customer_id'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $cost = !empty($_POST['cost']) ? $_POST['cost'] : null;

    $sql = "INSERT INTO services (customer_id, description, status, cost, created_at, updated_at) VALUES ('$customer_id', '$description', '$status', '$cost', NOW(), NOW())";
    if ($conn->query($sql) === TRUE) {
        $service_id = $conn->insert_id;
        if (!empty($_POST['product_cart'])) {
            $productCart = json_decode($_POST['product_cart'], true);
            foreach ($productCart as $product_id) {
                $sql = "INSERT INTO service_products (service_id, product_id) VALUES ('$service_id', '$product_id')";
                $conn->query($sql);
                $sql = "UPDATE products SET stock = stock - 1 WHERE id='$product_id'";
                $conn->query($sql);
            }
        }
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
include 'config.php';

if (!empty($_POST['customer_id']) && !empty($_POST['description']) && !empty($_POST['status'])) {
    $customer_id = $_POST['customer_id'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $cost = !empty($_POST['cost']) ? $_POST['cost'] : 0;

    $sql = "INSERT INTO services (customer_id, description, status, cost, created_at, updated_at) VALUES ('$customer_id', '$description', '$status', '$cost', NOW(), NOW())";
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
                    text: 'Error: " . $sql . "<br>" . $conn->error . "'
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
?>
