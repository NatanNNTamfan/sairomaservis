<?php include 'config.php'; ?>

<!-- Add product -->
<?php
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO products (name, price, stock) VALUES ('$name', '$price', '$stock')";
    if ($conn->query($sql) === TRUE) {
        echo "New product added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!-- Display products -->
<?php
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - Price: " . $row["price"]. " - Stock: " . $row["stock"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<div class="container">
<form method="post" action="">
    Name: <input type="text" name="name"><br>
    Price: <input type="text" name="price"><br>
    Stock: <input type="text" name="stock"><br>
    <input type="submit" name="add_product" value="Add Product">
</form>
</div>
