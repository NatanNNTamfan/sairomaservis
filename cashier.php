
<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Cashier</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Process Payment</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="product_id">Product:</label>
            <select class="form-control" id="product_id" name="product_id" required>
                <option value="">Select Product</option>
                <?php
                $sql = "SELECT id, merk, name, kategori FROM products";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . $row['merk'] . ' ' . $row['name'] . ' ' . $row['kategori'] . '</option>';
                    }
                } else {
                    echo '<option value="">No products available</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
        </div>
        <div class="form-group">
            <label for="price">Harga Jual:</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="discount">Discount:</label>
            <input type="number" class="form-control" id="discount" name="discount" value="0" required>
        </div>
        <button type="button" class="btn btn-success" onclick="addProduct()">Add Product</button>
    </form>
    <h3 class="mt-4">Selected Products</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Harga Jual</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="productTable">
            <!-- Products will be added here dynamically -->
        </tbody>
    </table>
    <form method="post" action="process_payment.php">
        <input type="hidden" name="products" id="productsInput">
        <button type="submit" class="btn btn-primary">Process Payment</button>
    </form>
</div>
<script>
</script>
</body>
</html>
<script>
    let products = [];

    function addProduct() {
        const productId = document.getElementById('product_id').value;
        const productName = document.getElementById('product_id').options[document.getElementById('product_id').selectedIndex].text;
        const quantity = document.getElementById('quantity').value;
        const price = document.getElementById('price').value;
        const discount = document.getElementById('discount').value;
        const total = (price * quantity) - discount;

        const product = {
            productId,
            productName,
            quantity,
            price,
            discount,
            total
        };

        products.push(product);
        updateProductTable();
    }

    function updateProductTable() {
        const productTable = document.getElementById('productTable');
        productTable.innerHTML = '';

        products.forEach((product, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${product.productName}</td>
                <td>${product.quantity}</td>
                <td>Rp ${number_format(product.price, 0, ',', '.')}</td>
                <td>Rp ${number_format(product.discount, 0, ',', '.')}</td>
                <td>Rp ${number_format(product.total, 0, ',', '.')}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeProduct(${index})">Remove</button></td>
            `;
            productTable.appendChild(row);
        });

        document.getElementById('productsInput').value = JSON.stringify(products);
    }

    function removeProduct(index) {
        products.splice(index, 1);
        updateProductTable();
    }

    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        const n = !isFinite(+number) ? 0 : +number;
        const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
        const sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
        const dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
        let s = '';
        const toFixedFix = function (n, prec) {
            const k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k).toFixed(prec);
        };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
