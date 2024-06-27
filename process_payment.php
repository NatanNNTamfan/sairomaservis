<?php
include 'config.php';

if (isset($_POST['products'])) {
    $products = json_decode($_POST['products'], true);

    foreach ($products as $product) {
        $product_id = $product['productId'];
        $quantity = $product['quantity'];
        $price = $product['price'];
        $discount = $product['discount'];
        $total = $product['total'];

        $sql = "SELECT stock FROM products WHERE id='$product_id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stock = $row['stock'];

            if ($stock >= $quantity) {
                $new_stock = $stock - $quantity;

                $sql = "INSERT INTO sales (product_id, quantity, price, discount, total, date) VALUES ('$product_id', '$quantity', '$price', '$discount', '$total', NOW())";
                if ($conn->query($sql) === TRUE) {
                    $sql = "UPDATE products SET stock='$new_stock' WHERE id='$product_id'";
                    $conn->query($sql);
                } else {
                    echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-warning'>Not enough stock for product ID: $product_id</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Product not found for product ID: $product_id</div>";
        }
    }

    echo "<div class='alert alert-success'>Payment processed successfully.</div>";
}
$conn->close();
header("Location: cashier.php");
exit();
?>
