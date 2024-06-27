<?php include 'config.php'; ?>

<!-- Process payment -->
<?php
if (isset($_POST['process_payment'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];

    $sql = "SELECT stock FROM products WHERE id='$product_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stock = $row['stock'];

        if ($stock >= $quantity) {
            $total_price = ($price * $quantity) - $discount;
            $new_stock = $stock - $quantity;

            $sql = "INSERT INTO sales (product_id, quantity, total_price, discount) VALUES ('$product_id', '$quantity', '$total_price', '$discount')";
            if ($conn->query($sql) === TRUE) {
                $sql = "UPDATE products SET stock='$new_stock' WHERE id='$product_id'";
                $conn->query($sql);
                echo "<div class='alert alert-success'>Payment processed successfully. Total Price: Rp " . number_format($total_price, 0, ',', '.') . "</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Not enough stock</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Product not found</div>";
    }
}
$conn->close();
?>

<div class="container mt-4">
    <h2>Process Payment</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="product_id">Product:</label>
            <select class="form-control" id="product_id" name="product_id" required>
                <?php
                $sql = "SELECT id, name FROM products";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
        </div>
        <div class="form-group">
            <label for="price">Harga Jual:</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="discount">Discount:</label>
            <input type="number" class="form-control" id="discount" name="discount" value="0" required>
        </div>
        <button type="submit" class="btn btn-primary" name="process_payment">Process Payment</button>
    </form>
</div>
