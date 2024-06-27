<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch service details
    $sql = "SELECT * FROM services WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Service not found'
                }).then(function() {
                    window.location = 'index.php';
                });
              </script>";
        exit();
    }
}

if (isset($_POST['edit_service'])) {
    $id = $_POST['id'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $cost = $_POST['cost'];

    // Update service
    $sql = "UPDATE services SET description='$description', status='$status', cost='$cost', updated_at=NOW() WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        // Update used products
        $sql = "DELETE FROM service_products WHERE service_id='$id'";
        $conn->query($sql);
        if (!empty($_POST['used_products'])) {
            foreach ($_POST['used_products'] as $product_id) {
                $sql = "INSERT INTO service_products (service_id, product_id) VALUES ('$id', '$product_id')";
                $conn->query($sql);
                $sql = "UPDATE products SET stock = stock - 1 WHERE id='$product_id'";
                $conn->query($sql);
            }
        }
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Service updated successfully'
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
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Service</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Service</h2>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
        <div class="form-group">
            <label for="used_products">Used Products:</label>
            <select class="form-control" id="used_products" name="used_products[]" multiple>
                <?php
                $sql = "SELECT id, name FROM products";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No products available</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending" <?php if ($service['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="In Progress" <?php if ($service['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                <option value="Completed" <?php if ($service['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" required><?php echo $service['description']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="used_products">Used Products:</label>
            <select class="form-control" id="used_products" name="used_products[]" multiple>
                <?php
                $sql = "SELECT id, name FROM products";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No products available</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending" <?php if ($service['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="In Progress" <?php if ($service['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                <option value="Completed" <?php if ($service['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
            </select>
        </div>
        <div class="form-group">
            <label for="cost">Cost:</label>
            <input type="number" class="form-control" id="cost" name="cost" value="<?php echo $service['cost']; ?>" step="0.01">
        </div>
        <button type="submit" class="btn btn-primary" name="edit_service">Save Changes</button>
    </form>
</div>
</body>
</html>
