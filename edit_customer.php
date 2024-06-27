<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch customer details
    $sql = "SELECT * FROM customers WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Customer not found'
                }).then(function() {
                    window.location = 'index.php';
                });
              </script>";
        exit();
    }
}

if (isset($_POST['edit_customer'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Update customer
    $sql = "UPDATE customers SET name='$name', phone='$phone', email='$email' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Customer updated successfully'
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
    <title>Edit Customer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Customer</h2>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $customer['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $customer['phone']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $customer['email']; ?>">
        </div>
        <button type="submit" class="btn btn-primary" name="edit_customer">Save Changes</button>
    </form>
</div>
</body>
</html>
