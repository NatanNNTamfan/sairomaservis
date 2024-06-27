<?php
include 'config.php';

// Display sales
$sql = "SELECT sales.id, products.name, sales.quantity, sales.total_price, sales.sale_date FROM sales JOIN products ON sales.product_id = products.id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "Sale ID: " . $row["id"]. " - Product: " . $row["name"]. " - Quantity: " . $row["quantity"]. " - Total Price: " . $row["total_price"]. " - Date: " . $row["sale_date"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
