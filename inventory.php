<?php include 'config.php'; ?>

<!-- Add product -->
<?php
if (isset($_POST['add_product'])) {
    $name = $_POST['merk'] . ' ' . $_POST['name'] . ' ' . $_POST['kategori'];
    $hargajual = $_POST['hargajual'];
    $hargabeli = $_POST['hargabeli'];
    $stock = $_POST['stock'];
    $kategori = $_POST['kategori'];
    $merk = $_POST['merk'];

    // Check for duplicate product
    $check_sql = "SELECT * FROM products WHERE name='$name' AND kategori='$kategori' AND merk='$merk'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "Product already exists";
    } else {
        $sql = "INSERT INTO products (name, hargajual, hargabeli, stock, kategori, merk) VALUES ('$name', '$hargajual', '$hargabeli', '$stock', '$kategori', '$merk')";
        if ($conn->query($sql) === TRUE) {
            echo "New product added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!-- Search form -->
<div class="container mt-4">
    <h2>Product Inventory</h2>
    <form method="get" action="">
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

            document.getElementById('hargajual').addEventListener('keyup', function(e) {
                this.value = formatRupiah(this.value, 'Rp ');
            });

            document.getElementById('hargabeli').addEventListener('keyup', function(e) {
                this.value = formatRupiah(this.value, 'Rp ');
            });

            document.getElementById('hargajual').addEventListener('blur', function(e) {
                this.value = formatRupiah(this.value, 'Rp ');
            });

            document.getElementById('hargabeli').addEventListener('blur', function(e) {
                this.value = formatRupiah(this.value, 'Rp ');
            });

            // Ensure the initial value is formatted correctly
            document.getElementById('hargajual').value = formatRupiah(document.getElementById('hargajual').value, 'Rp ');
            document.getElementById('hargabeli').value = formatRupiah(document.getElementById('hargabeli').value, 'Rp ');
        </script>
        <div class="form-group">
            <label for="search">Search:</label>
            <input type="text" class="form-control" id="search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Harga Jual</th>
                <th>Harga Beli</th>
                <th>Stock</th>
                <th>Kategori</th>
                <th>Merk</th>
                <th>Action</th>
            </tr>

        </thead>
        <tbody>
            <?php
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $search = str_replace(' ', '', $search);
            $sql = "SELECT * FROM products WHERE REPLACE(name, ' ', '') LIKE '%$search%' OR REPLACE(kategori, ' ', '') LIKE '%$search%' OR REPLACE(merk, ' ', '') LIKE '%$search%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>Rp " . number_format($row["hargajual"], 0, ',', '.') . "</td>";
                    echo "<td>Rp " . number_format($row["hargabeli"], 0, ',', '.') . "</td>";
                    echo "<td>" . $row["stock"] . "</td>";
                    echo "<td>" . $row["kategori"] . "</td>";
                    echo "<td>" . $row["merk"] . "</td>";
                    echo "<td>
                            <form method='post' action='delete_product.php' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
                            </form>
                            <a href='edit_product.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>0 results</td></tr>";
            }
            $conn->close();
            ?>

            <script>
                function editProduct(id) {
                    // Fetch product data and populate the form
                    fetch('get_product.php?id=' + id)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('edit_id').value = data.id;
                            document.getElementById('edit_name').value = data.name;
                            document.getElementById('edit_hargajual').value = data.hargajual;
                            document.getElementById('edit_hargabeli').value = data.hargabeli;
                            document.getElementById('edit_stock').value = data.stock;
                            document.getElementById('edit_kategori').value = data.kategori;
                            document.getElementById('edit_merk').value = data.merk;
                            $('#editModal').modal('show');
                        });
                }
            </script>

            ?>
        </tbody>
    </table>
</div>

<div class="container">
<div class="container mt-4">
    <h2>Add New Product</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="hargajual">Harga Jual:</label>
            <input type="number" class="form-control" id="hargajual" name="hargajual" required>
        </div>
        <div class="form-group">
            <label for="hargabeli">Harga Beli:</label>
            <input type="number" class="form-control" id="hargabeli" name="hargabeli" required>
        </div>
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>
        <div class="form-group">
            <label for="kategori">Kategori:</label>
            <select class="form-control" id="kategori" name="kategori" required>
                <option value="Connector">Connector</option>
                <option value="Backdoor">Backdoor</option>
                <option value="LCD">LCD</option>
                <option value="Baterai">Baterai</option>
                <option value="Kamera">Kamera</option>
                <option value="Speaker">Speaker</option>
                <option value="Mikrofon">Mikrofon</option>
                <option value="Charger">Charger</option>
                <option value="Headset">Headset</option>
                <option value="Casing">Casing</option>
            </select>
        </div>
        <div class="form-group">
            <label for="merk">Merk:</label>
            <select class="form-control" id="merk" name="merk" required>
                <option value="Samsung">Samsung</option>
                <option value="Apple">Apple</option>
                <option value="Xiaomi">Xiaomi</option>
                <option value="Oppo">Oppo</option>
                <option value="Vivo">Vivo</option>
                <option value="Realme">Realme</option>
                <option value="Asus">Asus</option>
                <option value="Huawei">Huawei</option>
                <option value="Nokia">Nokia</option>
                <option value="Sony">Sony</option>
                <option value="LG">LG</option>
                <option value="OnePlus">OnePlus</option>
                <option value="Lenovo">Lenovo</option>
                <option value="Motorola">Motorola</option>
                <option value="Infinix">Infinix</option>
                <option value="Tecno">Tecno</option>
                <option value="Advan">Advan</option>
                <option value="Evercoss">Evercoss</option>
                <option value="Mito">Mito</option>
                <option value="Polytron">Polytron</option>
                <option value="Sharp">Sharp</option>
                <option value="ZTE">ZTE</option>
                <option value="Meizu">Meizu</option>
                <option value="Google">Google</option>
                <option value="Honor">Honor</option>
                <option value="iQOO">iQOO</option>
                <option value="Itel">Itel</option>
                <option value="Luna">Luna</option>
                <option value="Maxtron">Maxtron</option>
                <option value="Nexian">Nexian</option>
                <option value="Treq">Treq</option>
                <option value="Wiko">Wiko</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
    </form>
</div>
</div>
