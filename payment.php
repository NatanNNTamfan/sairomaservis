<?php include 'config.php'; ?>

<!-- Process payment -->
<?php
if (isset($_POST['process_payment'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $sql = "SELECT price, stock FROM products WHERE id='$product_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $price = $row['price'];
        $stock = $row['stock'];

        if ($stock >= $quantity) {
            $total_price = $price * $quantity;
            $new_stock = $stock - $quantity;

            $sql = "INSERT INTO sales (product_id, quantity, total_price) VALUES ('$product_id', '$quantity', '$total_price')";
            if ($conn->query($sql) === TRUE) {
                $sql = "UPDATE products SET stock='$new_stock' WHERE id='$product_id'";
                $conn->query($sql);
                echo "Payment processed successfully. Total Price: Rp " . number_format($total_price, 0, ',', '.');
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Not enough stock";
        }
    } else {
        echo "Product not found";
    }
}
$conn->close();
?>

<div class="container">
<form method="post" action="">
    Product ID: <input type="text" name="product_id"><br>
    Quantity: <input type="text" name="quantity"><br>
    <input type="submit" name="process_payment" value="Process Payment">
</form>
</div>
