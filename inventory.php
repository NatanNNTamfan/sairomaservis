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
                <td>
                    <form method="post" action="delete_product.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="edit_product.php">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="hargajual">Harga Jual:</label>
                                    <input type="number" class="form-control" id="hargajual" name="hargajual" value="<?php echo $row['hargajual']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="hargabeli">Harga Beli:</label>
                                    <input type="number" class="form-control" id="hargabeli" name="hargabeli" value="<?php echo $row['hargabeli']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="stock">Stock:</label>
                                    <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $row['stock']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori:</label>
                                    <select class="form-control" id="kategori" name="kategori" required>
                                        <option value="Connector" <?php if($row['kategori'] == 'Connector') echo 'selected'; ?>>Connector</option>
                                        <option value="Backdoor" <?php if($row['kategori'] == 'Backdoor') echo 'selected'; ?>>Backdoor</option>
                                        <option value="LCD" <?php if($row['kategori'] == 'LCD') echo 'selected'; ?>>LCD</option>
                                        <option value="Baterai" <?php if($row['kategori'] == 'Baterai') echo 'selected'; ?>>Baterai</option>
                                        <option value="Kamera" <?php if($row['kategori'] == 'Kamera') echo 'selected'; ?>>Kamera</option>
                                        <option value="Speaker" <?php if($row['kategori'] == 'Speaker') echo 'selected'; ?>>Speaker</option>
                                        <option value="Mikrofon" <?php if($row['kategori'] == 'Mikrofon') echo 'selected'; ?>>Mikrofon</option>
                                        <option value="Charger" <?php if($row['kategori'] == 'Charger') echo 'selected'; ?>>Charger</option>
                                        <option value="Headset" <?php if($row['kategori'] == 'Headset') echo 'selected'; ?>>Headset</option>
                                        <option value="Casing" <?php if($row['kategori'] == 'Casing') echo 'selected'; ?>>Casing</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="merk">Merk:</label>
                                    <select class="form-control" id="merk" name="merk" required>
                                        <option value="Samsung" <?php if($row['merk'] == 'Samsung') echo 'selected'; ?>>Samsung</option>
                                        <option value="Apple" <?php if($row['merk'] == 'Apple') echo 'selected'; ?>>Apple</option>
                                        <option value="Xiaomi" <?php if($row['merk'] == 'Xiaomi') echo 'selected'; ?>>Xiaomi</option>
                                        <option value="Oppo" <?php if($row['merk'] == 'Oppo') echo 'selected'; ?>>Oppo</option>
                                        <option value="Vivo" <?php if($row['merk'] == 'Vivo') echo 'selected'; ?>>Vivo</option>
                                        <option value="Realme" <?php if($row['merk'] == 'Realme') echo 'selected'; ?>>Realme</option>
                                        <option value="Asus" <?php if($row['merk'] == 'Asus') echo 'selected'; ?>>Asus</option>
                                        <option value="Huawei" <?php if($row['merk'] == 'Huawei') echo 'selected'; ?>>Huawei</option>
                                        <option value="Nokia" <?php if($row['merk'] == 'Nokia') echo 'selected'; ?>>Nokia</option>
                                        <option value="Sony" <?php if($row['merk'] == 'Sony') echo 'selected'; ?>>Sony</option>
                                        <option value="LG" <?php if($row['merk'] == 'LG') echo 'selected'; ?>>LG</option>
                                        <option value="OnePlus" <?php if($row['merk'] == 'OnePlus') echo 'selected'; ?>>OnePlus</option>
                                        <option value="Lenovo" <?php if($row['merk'] == 'Lenovo') echo 'selected'; ?>>Lenovo</option>
                                        <option value="Motorola" <?php if($row['merk'] == 'Motorola') echo 'selected'; ?>>Motorola</option>
                                        <option value="Infinix" <?php if($row['merk'] == 'Infinix') echo 'selected'; ?>>Infinix</option>
                                        <option value="Tecno" <?php if($row['merk'] == 'Tecno') echo 'selected'; ?>>Tecno</option>
                                        <option value="Advan" <?php if($row['merk'] == 'Advan') echo 'selected'; ?>>Advan</option>
                                        <option value="Evercoss" <?php if($row['merk'] == 'Evercoss') echo 'selected'; ?>>Evercoss</option>
                                        <option value="Mito" <?php if($row['merk'] == 'Mito') echo 'selected'; ?>>Mito</option>
                                        <option value="Polytron" <?php if($row['merk'] == 'Polytron') echo 'selected'; ?>>Polytron</option>
                                        <option value="Sharp" <?php if($row['merk'] == 'Sharp') echo 'selected'; ?>>Sharp</option>
                                        <option value="ZTE" <?php if($row['merk'] == 'ZTE') echo 'selected'; ?>>ZTE</option>
                                        <option value="Meizu" <?php if($row['merk'] == 'Meizu') echo 'selected'; ?>>Meizu</option>
                                        <option value="Google" <?php if($row['merk'] == 'Google') echo 'selected'; ?>>Google</option>
                                        <option value="Honor" <?php if($row['merk'] == 'Honor') echo 'selected'; ?>>Honor</option>
                                        <option value="iQOO" <?php if($row['merk'] == 'iQOO') echo 'selected'; ?>>iQOO</option>
                                        <option value="Itel" <?php if($row['merk'] == 'Itel') echo 'selected'; ?>>Itel</option>
                                        <option value="Luna" <?php if($row['merk'] == 'Luna') echo 'selected'; ?>>Luna</option>
                                        <option value="Maxtron" <?php if($row['merk'] == 'Maxtron') echo 'selected'; ?>>Maxtron</option>
                                        <option value="Nexian" <?php if($row['merk'] == 'Nexian') echo 'selected'; ?>>Nexian</option>
                                        <option value="Treq" <?php if($row['merk'] == 'Treq') echo 'selected'; ?>>Treq</option>
                                        <option value="Wiko" <?php if($row['merk'] == 'Wiko') echo 'selected'; ?>>Wiko</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" name="edit_product">Save changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
                    echo "<td>" . $row["hargajual"] . "</td>";
                    echo "<td>" . $row["hargabeli"] . "</td>";
                    echo "<td>" . $row["stock"] . "</td>";
                    echo "<td>" . $row["kategori"] . "</td>";
                    echo "<td>" . $row["merk"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>0 results</td></tr>";
            }
            $conn->close();
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
