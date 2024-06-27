<?php
include 'config.php';

if (isset($_POST['products'])) {
    $products = json_decode($_POST['products'], true);

    foreach ($products as $product) {
        $product_id = $product['productId'];
        $quantity = $product['quantity'];
        $price = $product['price'];
        $discount = !empty($product['discount']) ? $product['discount'] : 0;
        $total = $product['total'];

        $stmt = $conn->prepare("SELECT stock FROM products WHERE id=?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stock = $row['stock'];

            if ($stock >= $quantity) {
                $new_stock = $stock - $quantity;

                $stmt = $conn->prepare("INSERT INTO sales (product_id, quantity, price, discount, total, date) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param("iiiii", $product_id, $quantity, $price, $discount, $total);
                if ($stmt->execute() === TRUE) {
                    $stmt = $conn->prepare("UPDATE products SET stock=? WHERE id=?");
                    $stmt->bind_param("ii", $new_stock, $product_id);
                    $stmt->execute();
                } else {
                    echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
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
