<?php include 'config.php'; ?>

<!-- Add product -->
<?php
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $hargajual = $_POST['hargajual'];
    $hargabeli = $_POST['hargabeli'];
    $stock = $_POST['stock'];
    $kategori = $_POST['kategori'];
    $merk = $_POST['merk'];

    $sql = "INSERT INTO products (name, hargajual, hargabeli, stock, kategori, merk) VALUES ('$name', '$hargajual', '$hargabeli', '$stock', '$kategori', '$merk')";
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
    while ($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - Harga Jual: " . $row["hargajual"]. " - Harga Beli: " . $row["hargabeli"]. " - Stock: " . $row["stock"]. " - Kategori: " . $row["kategori"]. " - Merk: " . $row["merk"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<div class="container">
<form method="post" action="">
    Name: <input type="text" name="name"><br>
    Harga Jual: <input type="text" name="hargajual"><br>
    Harga Beli: <input type="text" name="hargabeli"><br>
    Stock: <input type="text" name="stock"><br>
    Kategori: <input type="text" name="kategori"><br>
    Merk: <input type="text" name="merk"><br>
    <input type="submit" name="add_product" value="Add Product">
</form>
</div>
