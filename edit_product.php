<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch product details
    $sql = "SELECT * FROM products WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found";
        exit();
    }
}

if (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $hargabeli = str_replace(['Rp ', '.'], '', $_POST['hargabeli']);
    $stock = $_POST['stock'];
    $kategori = $_POST['kategori'];
    $merk = $_POST['merk'];

    // Update product
    $sql = "UPDATE products SET name='$name', hargabeli='$hargabeli', stock='$stock', kategori='$kategori', merk='$merk' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Product updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Redirect back to the inventory page
    header("Location: inventory.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Edit Product</h2>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="hargabeli">Harga Beli:</label>
            <input type="text" class="form-control" id="edit_hargabeli" name="hargabeli" value="<?php echo $product['hargabeli']; ?>" required>
        </div>
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>
        </div>
        <div class="form-group">
            <label for="kategori">Kategori:</label>
            <select class="form-control" id="kategori" name="kategori" required>
                <option value="Connector" <?php if ($product['kategori'] == 'Connector') echo 'selected'; ?>>Connector</option>
                <option value="Backdoor" <?php if ($product['kategori'] == 'Backdoor') echo 'selected'; ?>>Backdoor</option>
                <option value="LCD" <?php if ($product['kategori'] == 'LCD') echo 'selected'; ?>>LCD</option>
                <option value="Baterai" <?php if ($product['kategori'] == 'Baterai') echo 'selected'; ?>>Baterai</option>
                <option value="Kamera" <?php if ($product['kategori'] == 'Kamera') echo 'selected'; ?>>Kamera</option>
                <option value="Speaker" <?php if ($product['kategori'] == 'Speaker') echo 'selected'; ?>>Speaker</option>
                <option value="Mikrofon" <?php if ($product['kategori'] == 'Mikrofon') echo 'selected'; ?>>Mikrofon</option>
                <option value="Charger" <?php if ($product['kategori'] == 'Charger') echo 'selected'; ?>>Charger</option>
                <option value="Headset" <?php if ($product['kategori'] == 'Headset') echo 'selected'; ?>>Headset</option>
                <option value="Casing" <?php if ($product['kategori'] == 'Casing') echo 'selected'; ?>>Casing</option>
            </select>
        </div>
        <div class="form-group">
            <label for="merk">Merk:</label>
            <select class="form-control" id="merk" name="merk" required>
                <option value="Samsung" <?php if ($product['merk'] == 'Samsung') echo 'selected'; ?>>Samsung</option>
                <option value="Apple" <?php if ($product['merk'] == 'Apple') echo 'selected'; ?>>Apple</option>
                <option value="Xiaomi" <?php if ($product['merk'] == 'Xiaomi') echo 'selected'; ?>>Xiaomi</option>
                <option value="Oppo" <?php if ($product['merk'] == 'Oppo') echo 'selected'; ?>>Oppo</option>
                <option value="Vivo" <?php if ($product['merk'] == 'Vivo') echo 'selected'; ?>>Vivo</option>
                <option value="Realme" <?php if ($product['merk'] == 'Realme') echo 'selected'; ?>>Realme</option>
                <option value="Asus" <?php if ($product['merk'] == 'Asus') echo 'selected'; ?>>Asus</option>
                <option value="Huawei" <?php if ($product['merk'] == 'Huawei') echo 'selected'; ?>>Huawei</option>
                <option value="Nokia" <?php if ($product['merk'] == 'Nokia') echo 'selected'; ?>>Nokia</option>
                <option value="Sony" <?php if ($product['merk'] == 'Sony') echo 'selected'; ?>>Sony</option>
                <option value="LG" <?php if ($product['merk'] == 'LG') echo 'selected'; ?>>LG</option>
                <option value="OnePlus" <?php if ($product['merk'] == 'OnePlus') echo 'selected'; ?>>OnePlus</option>
                <option value="Lenovo" <?php if ($product['merk'] == 'Lenovo') echo 'selected'; ?>>Lenovo</option>
                <option value="Motorola" <?php if ($product['merk'] == 'Motorola') echo 'selected'; ?>>Motorola</option>
                <option value="Infinix" <?php if ($product['merk'] == 'Infinix') echo 'selected'; ?>>Infinix</option>
                <option value="Tecno" <?php if ($product['merk'] == 'Tecno') echo 'selected'; ?>>Tecno</option>
                <option value="Advan" <?php if ($product['merk'] == 'Advan') echo 'selected'; ?>>Advan</option>
                <option value="Evercoss" <?php if ($product['merk'] == 'Evercoss') echo 'selected'; ?>>Evercoss</option>
                <option value="Mito" <?php if ($product['merk'] == 'Mito') echo 'selected'; ?>>Mito</option>
                <option value="Polytron" <?php if ($product['merk'] == 'Polytron') echo 'selected'; ?>>Polytron</option>
                <option value="Sharp" <?php if ($product['merk'] == 'Sharp') echo 'selected'; ?>>Sharp</option>
                <option value="ZTE" <?php if ($product['merk'] == 'ZTE') echo 'selected'; ?>>ZTE</option>
                <option value="Meizu" <?php if ($product['merk'] == 'Meizu') echo 'selected'; ?>>Meizu</option>
                <option value="Google" <?php if ($product['merk'] == 'Google') echo 'selected'; ?>>Google</option>
                <option value="Honor" <?php if ($product['merk'] == 'Honor') echo 'selected'; ?>>Honor</option>
                <option value="iQOO" <?php if ($product['merk'] == 'iQOO') echo 'selected'; ?>>iQOO</option>
                <option value="Itel" <?php if ($product['merk'] == 'Itel') echo 'selected'; ?>>Itel</option>
                <option value="Luna" <?php if ($product['merk'] == 'Luna') echo 'selected'; ?>>Luna</option>
                <option value="Maxtron" <?php if ($product['merk'] == 'Maxtron') echo 'selected'; ?>>Maxtron</option>
                <option value="Nexian" <?php if ($product['merk'] == 'Nexian') echo 'selected'; ?>>Nexian</option>
                <option value="Treq" <?php if ($product['merk'] == 'Treq') echo 'selected'; ?>>Treq</option>
                <option value="Wiko" <?php if ($product['merk'] == 'Wiko') echo 'selected'; ?>>Wiko</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="edit_product">Save Changes</button>
    </form>
</div>
<script>
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    const editHargabeli = document.getElementById('edit_hargabeli');

    if (editHargabeli) {
        editHargabeli.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value, 'Rp ');
        });

        editHargabeli.addEventListener('blur', function(e) {
            this.value = formatRupiah(this.value, 'Rp ');
        });

        // Ensure the initial value is formatted correctly
        editHargabeli.value = formatRupiah(editHargabeli.value, 'Rp ');
    }
</script>
</body>
</html>
