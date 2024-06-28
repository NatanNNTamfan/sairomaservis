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
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Product not found'
                }).then(function() {
                    window.location = 'inventory.php';
                });
              </script>";
        exit();
    }
}

if (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $hargabeli = str_replace(['Rp ', '.'], '', $_POST['hargabeli']);
    $hargabeli = intval($hargabeli); // Ensure hargabeli is an integer
    $stock = $_POST['stock'];
    $kategori = $_POST['kategori'];
    $merk = $_POST['merk'];

    // Update product
    $sql = "UPDATE products SET name='$name', hargabeli='$hargabeli', stock='$stock', kategori='$kategori', merk='$merk' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Product updated successfully'
                }).then(function() {
                    window.location = 'inventory.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: " . $sql . "<br>" . $conn->error . "'
                }).then(function() {
                    window.location = 'inventory.php';
                });
              </script>";
    }
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
    <form method="post" action="edit_product.php?id=<?php echo $product['id']; ?>">
        <input type="hidden" name="id" value="<?php echo isset($product['id']) ? $product['id'] : ''; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($product['name']) ? $product['name'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="hargabeli">Harga Beli:</label>
            <input type="text" class="form-control" id="edit_hargabeli" name="hargabeli" value="<?php echo isset($product['hargabeli']) ? number_format($product['hargabeli'], 0, ',', '.') : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?php echo isset($product['stock']) ? $product['stock'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="kategori">Kategori:</label>
            <select class="form-control" id="kategori" name="kategori" required>
                <option value="Connector" <?php if (isset($product['kategori']) && $product['kategori'] == 'Connector') echo 'selected'; ?>>Connector</option>
                <option value="Backdoor" <?php if (isset($product['kategori']) && $product['kategori'] == 'Backdoor') echo 'selected'; ?>>Backdoor</option>
                <option value="LCD" <?php if (isset($product['kategori']) && $product['kategori'] == 'LCD') echo 'selected'; ?>>LCD</option>
                <option value="Baterai" <?php if (isset($product['kategori']) && $product['kategori'] == 'Baterai') echo 'selected'; ?>>Baterai</option>
                <option value="Kamera" <?php if (isset($product['kategori']) && $product['kategori'] == 'Kamera') echo 'selected'; ?>>Kamera</option>
                <option value="Speaker" <?php if (isset($product['kategori']) && $product['kategori'] == 'Speaker') echo 'selected'; ?>>Speaker</option>
                <option value="Mikrofon" <?php if (isset($product['kategori']) && $product['kategori'] == 'Mikrofon') echo 'selected'; ?>>Mikrofon</option>
                <option value="Charger" <?php if (isset($product['kategori']) && $product['kategori'] == 'Charger') echo 'selected'; ?>>Charger</option>
                <option value="Headset" <?php if (isset($product['kategori']) && $product['kategori'] == 'Headset') echo 'selected'; ?>>Headset</option>
                <option value="Casing" <?php if (isset($product['kategori']) && $product['kategori'] == 'Casing') echo 'selected'; ?>>Casing</option>
            </select>
        </div>
        <div class="form-group">
            <label for="merk">Merk:</label>
            <select class="form-control" id="merk" name="merk" required>
                <option value="Samsung" <?php if (isset($product['merk']) && $product['merk'] == 'Samsung') echo 'selected'; ?>>Samsung</option>
                <option value="Apple" <?php if (isset($product['merk']) && $product['merk'] == 'Apple') echo 'selected'; ?>>Apple</option>
                <option value="Xiaomi" <?php if (isset($product['merk']) && $product['merk'] == 'Xiaomi') echo 'selected'; ?>>Xiaomi</option>
                <option value="Oppo" <?php if (isset($product['merk']) && $product['merk'] == 'Oppo') echo 'selected'; ?>>Oppo</option>
                <option value="Vivo" <?php if (isset($product['merk']) && $product['merk'] == 'Vivo') echo 'selected'; ?>>Vivo</option>
                <option value="Realme" <?php if (isset($product['merk']) && $product['merk'] == 'Realme') echo 'selected'; ?>>Realme</option>
                <option value="Asus" <?php if (isset($product['merk']) && $product['merk'] == 'Asus') echo 'selected'; ?>>Asus</option>
                <option value="Huawei" <?php if (isset($product['merk']) && $product['merk'] == 'Huawei') echo 'selected'; ?>>Huawei</option>
                <option value="Nokia" <?php if (isset($product['merk']) && $product['merk'] == 'Nokia') echo 'selected'; ?>>Nokia</option>
                <option value="Sony" <?php if (isset($product['merk']) && $product['merk'] == 'Sony') echo 'selected'; ?>>Sony</option>
                <option value="LG" <?php if (isset($product['merk']) && $product['merk'] == 'LG') echo 'selected'; ?>>LG</option>
                <option value="OnePlus" <?php if (isset($product['merk']) && $product['merk'] == 'OnePlus') echo 'selected'; ?>>OnePlus</option>
                <option value="Lenovo" <?php if (isset($product['merk']) && $product['merk'] == 'Lenovo') echo 'selected'; ?>>Lenovo</option>
                <option value="Motorola" <?php if (isset($product['merk']) && $product['merk'] == 'Motorola') echo 'selected'; ?>>Motorola</option>
                <option value="Infinix" <?php if (isset($product['merk']) && $product['merk'] == 'Infinix') echo 'selected'; ?>>Infinix</option>
                <option value="Tecno" <?php if (isset($product['merk']) && $product['merk'] == 'Tecno') echo 'selected'; ?>>Tecno</option>
                <option value="Advan" <?php if (isset($product['merk']) && $product['merk'] == 'Advan') echo 'selected'; ?>>Advan</option>
                <option value="Evercoss" <?php if (isset($product['merk']) && $product['merk'] == 'Evercoss') echo 'selected'; ?>>Evercoss</option>
                <option value="Mito" <?php if (isset($product['merk']) && $product['merk'] == 'Mito') echo 'selected'; ?>>Mito</option>
                <option value="Polytron" <?php if (isset($product['merk']) && $product['merk'] == 'Polytron') echo 'selected'; ?>>Polytron</option>
                <option value="Sharp" <?php if (isset($product['merk']) && $product['merk'] == 'Sharp') echo 'selected'; ?>>Sharp</option>
                <option value="ZTE" <?php if (isset($product['merk']) && $product['merk'] == 'ZTE') echo 'selected'; ?>>ZTE</option>
                <option value="Meizu" <?php if (isset($product['merk']) && $product['merk'] == 'Meizu') echo 'selected'; ?>>Meizu</option>
                <option value="Google" <?php if (isset($product['merk']) && $product['merk'] == 'Google') echo 'selected'; ?>>Google</option>
                <option value="Honor" <?php if (isset($product['merk']) && $product['merk'] == 'Honor') echo 'selected'; ?>>Honor</option>
                <option value="iQOO" <?php if (isset($product['merk']) && $product['merk'] == 'iQOO') echo 'selected'; ?>>iQOO</option>
                <option value="Itel" <?php if (isset($product['merk']) && $product['merk'] == 'Itel') echo 'selected'; ?>>Itel</option>
                <option value="Luna" <?php if (isset($product['merk']) && $product['merk'] == 'Luna') echo 'selected'; ?>>Luna</option>
                <option value="Maxtron" <?php if (isset($product['merk']) && $product['merk'] == 'Maxtron') echo 'selected'; ?>>Maxtron</option>
                <option value="Nexian" <?php if (isset($product['merk']) && $product['merk'] == 'Nexian') echo 'selected'; ?>>Nexian</option>
                <option value="Treq" <?php if (isset($product['merk']) && $product['merk'] == 'Treq') echo 'selected'; ?>>Treq</option>
                <option value="Wiko" <?php if (isset($product['merk']) && $product['merk'] == 'Wiko') echo 'selected'; ?>>Wiko</option>
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
        // Ensure the initial value is formatted correctly only if it's not already formatted
        if (!editHargabeli.value.startsWith('Rp ')) {
            editHargabeli.value = formatRupiah(editHargabeli.value, 'Rp ');
        }
    }
</script>
</body>
</html>
