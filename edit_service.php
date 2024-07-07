<?php
include 'config.php';

$service_products = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch service details
    $stmt = $conn->prepare("SELECT * FROM services WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Service not found'
                }).then(function() {
                    window.location = 'index.php';
                });
              </script>";
        exit();
    }
    // Fetch used products for the service
    $stmt = $conn->prepare("SELECT product_id FROM service_products WHERE service_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $service_products[] = $row['product_id'];
    }
}

if (isset($_POST['edit_service'])) {
    $id = $_POST['id'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $cost = $_POST['cost'];

    // Fetch current used products from product_cart
    $service_products = [];
    if (!empty($_POST['product_cart'])) {
        $service_products = json_decode($_POST['product_cart'], true);
    } else {
        $service_products = [];
    }

    // Update service
    $stmt = $conn->prepare("UPDATE services SET description=?, status=?, cost=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("ssdi", $description, $status, $cost, $id);
    if ($stmt->execute() === TRUE) {
        // Update used products
        $stmt = $conn->prepare("DELETE FROM service_products WHERE service_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        foreach ($service_products as $product) {
            $product_id = $product['id'];
            $stmt = $conn->prepare("INSERT INTO service_products (service_id, product_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $id, $product_id);
            $stmt->execute();
            $stmt = $conn->prepare("UPDATE products SET stock = stock - 1 WHERE id=?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
        }
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Service updated successfully'
                }).then(function() {
                    window.location = 'index.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error: " . $stmt->error . "'
                }).then(function() {
                    window.location = 'index.php';
                });
              </script>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Service</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Edit Service</h2>
    <form method="post" action="edit_service.php" class="needs-validation" novalidate>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($service['id']); ?>">
        <div class="form-group was-validated">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" required><?php echo htmlspecialchars($service['description']); ?></textarea>
        </div>
        <div class="form-group was-validated">
            <label for="cost">Cost:</label>
            <input type="number" class="form-control" id="cost" name="cost" value="<?php echo htmlspecialchars($service['cost']); ?>" step="0.01">
        </div>
        <div class="form-group was-validated">
            <label for="used_products">Used Products:</label>
            <select class="form-control" id="used_products" name="used_products[]" multiple>
                <option value="">Select Product</option>
                <?php
                $sql = "SELECT id, name, hargabeli FROM products";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "' data-price='" . $row['hargabeli'] . "'>" . htmlspecialchars($row['name']) . " - Rp " . number_format($row['hargabeli'], 0, ',', '') . "</option>";
                    }
                } else {
                    echo "<option value=''>No products available</option>";
                }
                ?>
            </select>
            <button type="button" class="btn btn-success mt-2" onclick="addProduct()">Add Product</button>
        </div>
        <div class="form-group">
            <label for="product_cart">Product Cart:</label>
            <table class="table table-bordered" id="product_cart">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($service_products)): ?>
                        <?php foreach ($service_products as $product_id): 
                            $stmt = $conn->prepare("SELECT name, hargabeli FROM products WHERE id=?");
                            $stmt->bind_param("i", $product_id);
                            $stmt->execute();
                            $product_result = $stmt->get_result();
                            if ($product_result->num_rows > 0) {
                                $product = $product_result->fetch_assoc();
                        ?>
                        <?php
                        $stmt = $conn->prepare("SELECT name, hargabeli FROM products WHERE id=?");
                        $stmt->bind_param("i", $product_id);
                        $stmt->execute();
                        $product_result = $stmt->get_result();
                        if ($product_result->num_rows > 0) {
                            $product = $product_result->fetch_assoc();
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>Rp <?php echo number_format($product['hargabeli'], 0, ',', ''); ?></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeProduct(<?php echo $product_id; ?>)">Remove</button></td>
                        </tr>
                        <?php } ?>
                        <?php } endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="form-group was-validated">
            <label for="total_cost">Total Cost of Used Products:</label>
            <input type="text" class="form-control" id="total_cost" name="total_cost" readonly>
        </div>
        <div class="form-group">
            <label for="profit">Profit (Cost - Total Cost of Used Products):</label>
            <input type="text" class="form-control" id="profit" name="profit" readonly>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending" <?php if ($service['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="In Progress" <?php if ($service['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                <option value="Completed" <?php if ($service['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
            </select>
        </div>
        <input type="hidden" name="product_cart" id="product_cart_input" value='<?php echo json_encode($service_products); ?>'>
        <button type="submit" class="btn btn-primary" name="edit_service">Save Changes</button>
    </form>

    <script>
        let productCart = <?php echo json_encode(array_map(function($product_id) use ($conn) {
            $stmt = $conn->prepare("SELECT id, name, hargabeli FROM products WHERE id=?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
            return null;
        }, $service_products)); ?>;
    function addProduct() {
        const productSelect = document.getElementById('used_products');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productId = selectedOption.value;
        const productName = selectedOption.text;
        const productPrice = selectedOption.getAttribute('data-price');

        if (productId && !productCart.some(product => product.id == productId)) {
            productCart.push({ id: productId, name: productName, price: parseFloat(productPrice) });
            updateProductCart();
        }
    }

    function removeProduct(productId) {
        productCart = productCart.filter(product => product.id !== productId);
        updateProductCart();
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateProductCart();
    });

    function updateProductCart() {
        const productCartTable = document.getElementById('product_cart').getElementsByTagName('tbody')[0];
        productCartTable.innerHTML = '';

        productCart.forEach(product => {
            const row = productCartTable.insertRow();
            row.innerHTML = `
                <td>${product.name}</td>
                <td>Rp ${parseFloat(product.price).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}</td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="removeProduct('${product.id}')">Remove</button></td>
            `;
        });

        let totalCost = productCart.reduce((total, product) => total + parseFloat(product.price.replace(/[^\d.-]/g, '')), 0);
        document.getElementById('total_cost').value = 'Rp ' + totalCost.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        calculateProfit();
    }

    function calculateProfit() {
        let totalCost = parseFloat(document.getElementById('total_cost').value.replace(/[^\d.-]/g, '')) || 0;
        let serviceCost = parseFloat(document.getElementById('cost').value.replace(/[^\d.-]/g, '')) || 0;
        let profit = serviceCost - totalCost;
        document.getElementById('profit').value = 'Rp ' + profit.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    document.getElementById('cost').addEventListener('input', calculateProfit);
</script>
<?php $conn->close(); ?>
</body>
</html>
