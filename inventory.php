<?php include 'config.php';
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', 'import_error.log');

// Log semua input
file_put_contents('debug.log', print_r($_POST, true) . "\n" . print_r($_FILES, true), FILE_APPEND);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Inventory</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        #dropArea {
            border: 2px dashed #ccc;
            border-radius: 20px;
            width: 100%;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }
        #dropArea.highlight {
            border-color: #007bff;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2>Product Inventory</h2>

    <!-- Export to Excel Button -->
    <a href="export_inventory_to_excel.php?search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="btn btn-success mb-3">Export to Excel</a>

    <!-- Import from Excel Form -->
    <form id="importForm" method="post" enctype="multipart/form-data" class="mb-3">
        <div class="form-group">
            <label for="import_file">Import from Excel:</label>
            <div id="dropArea">
                <p>Drag and drop a file here or click to select a file</p>
                <input type="file" class="form-control-file" id="import_file" name="import_file" required style="display: none;">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>

    <!-- Search Form -->
    <form method="get" action="" class="mb-3">
        <div class="form-group">
            <label for="search">Search:</label>
            <input type="text" class="form-control" id="search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Products Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Harga Beli</th>
                <th>Stock</th>
                <th>Kategori</th>
                <th>Merk</th>
                <th>Action</th>
                <th>Tambah Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $sql = "SELECT * FROM products WHERE name LIKE '%$search%' OR kategori LIKE '%$search%' OR merk LIKE '%$search%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>Rp " . number_format($row["hargabeli"], 0, ',', '.') . "</td>";
                    echo "<td>" . htmlspecialchars($row["stock"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["kategori"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["merk"]) . "</td>";
                    echo "<td>
                            <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>Delete</button>
                            <a href='edit_product.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a>
                          </td>
                          <td>
                            <form class='add-stock-form' data-id='" . $row['id'] . "'>
                                <input type='number' name='add_stock' min='1' required>
                                <button type='submit' class='btn btn-success btn-sm'>Tambah</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No results found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Add New Product Form -->
    <div class="mt-4">
        <h2>Add New Product</h2>
        <form id="addProductForm">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="hargabeli">Harga Beli:</label>
                <input type="text" class="form-control" id="hargabeli" name="hargabeli" required>
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
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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

    const hargabeli = document.getElementById('hargabeli');

    if (hargabeli) {
        hargabeli.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value, 'Rp ');
        });

        hargabeli.addEventListener('blur', function(e) {
            this.value = formatRupiah(this.value, 'Rp ');
        });

        hargabeli.value = formatRupiah(hargabeli.value, 'Rp ');
    }

    function validateForm() {
        const name = document.getElementById('name').value;
        const hargabeli = document.getElementById('hargabeli').value;
        const stock = document.getElementById('stock').value;
        const kategori = document.getElementById('kategori').value;
        const merk = document.getElementById('merk').value;

        if (!name || !hargabeli || !stock || !kategori || !merk) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'All fields are required'
            });
            return false;
        }
        return true;
    }

    document.getElementById('addProductForm').addEventListener('submit', function(event) {
        event.preventDefault();
        if (!validateForm()) return;

        const formData = new FormData(this);

        fetch('process_add_product.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            try {
                const result = JSON.parse(text);
                Swal.fire({
                    icon: result.icon,
                    title: result.title,
                    text: result.text
                }).then(function() {
                    if (result.icon === 'success') {
                        window.location.reload();
                    }
                });
            } catch (error) {
                console.error('Error parsing JSON:', error);
                console.error('Response text:', text);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please check the console for details.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred'
            });
        });
    });

    document.getElementById('importForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    fetch('import_inventory_from_excel.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        try {
            const result = JSON.parse(text);
            Swal.fire({
                icon: result.status,
                title: result.status === 'success' ? 'Import Successful' : 'Import Failed',
                text: result.message
            }).then(function() {
                if (result.status === 'success') {
                    window.location.reload();
                }
            });
        } catch (error) {
            console.error('Error parsing JSON:', error);
            console.error('Response text:', text);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred during import. Please check the console for details.'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred during import. Please check the console for details.'
        });
    });
});

    const dropArea = document.getElementById('dropArea');
    const importFileInput = document.getElementById('import_file');

    dropArea.addEventListener('click', () => importFileInput.click());

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropArea.classList.add('highlight');
    }

    function unhighlight(e) {
        dropArea.classList.remove('highlight');
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        importFileInput.files = files;
    }

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('delete_product.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'id=' + id
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            Swal.fire(
                                'Deleted!',
                                'Product has been deleted.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to delete product.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'An unexpected error occurred.',
                            'error'
                        );
                    });
                }
            });
        });
    });

    document.querySelectorAll('.add-stock-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const addStock = this.querySelector('input[name="add_stock"]').value;

            fetch('add_stock.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}&add_stock=${addStock}`
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    Swal.fire(
                        'Success!',
                        'Stock has been added.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'Failed to add stock.',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error!',
                    'An unexpected error occurred.',
                    'error'
                );
            });
        });
    });
</script>

</body>
</html>
