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
    
    // Replace comma with dot and remove non-numeric characters for cost
    $cost = str_replace(',', '.', preg_replace('/[^0-9,]/', '', $_POST['cost']));

    // Fetch current used products from product_cart
    $service_products = [];
    if (!empty($_POST['product_cart'])) {
        $service_products = json_decode($_POST['product_cart'], true);
    }

    // Update service
    $stmt = $conn->prepare("UPDATE services SET description=?, status=?, cost=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("ssdi", $description, $status, $cost, $id);
    if ($stmt->execute() === TRUE) {
        // Update used products
        $stmt = $conn->prepare("DELETE FROM service_products WHERE service_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        if (!empty($service_products)) {
            $insert_stmt = $conn->prepare("INSERT INTO service_products (service_id, product_id) VALUES (?, ?)");
            $update_stock_stmt = $conn->prepare("UPDATE products SET stock = stock - 1 WHERE id=?");
            
            foreach ($service_products as $product_id) {
                $insert_stmt->bind_param("ii", $id, $product_id);
                $insert_stmt->execute();
                
                $update_stock_stmt->bind_param("i", $product_id);
                $update_stock_stmt->execute();
            }
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
            <input type="text" class="form-control" id="cost" name="cost" value="<?php echo htmlspecialchars(number_format($service['cost'], 2, ',', '.')); ?>" step="0.01">
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
                        echo "<option value='" . htmlspecialchars($row['id']) . "' data-price='" . $row['hargabeli'] . "'>" . htmlspecialchars($row['name']) . " - Rp " . number_format($row['hargabeli'], 0, ',', '.') . "</option>";
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
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>Rp <?php echo number_format($product['hargabeli'], 0, ',', '.'); ?></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeProduct(<?php echo $product_id; ?>)">Remove</button></td>
                        </tr>
                        <?php } ?>
                        <?php endforeach; ?>
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
    console.log("addProduct function called");
    const productSelect = document.getElementById('used_products');
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    
    if (!selectedOption) {
        console.log("No product selected");
        return;
    }
    
    const productId = selectedOption.value;
    const productName = selectedOption.text;
    const productPrice = selectedOption.getAttribute('data-price');

    console.log("Selected product:", { id: productId, name: productName, price: productPrice });

    if (productId && !productCart.some(product => product && product.id == productId)) {
        productCart.push({ id: productId, name: productName, price: productPrice });
        console.log("Product added to cart:", productCart);
        updateProductCart();
        calculateProfit();
    } else {
        console.log("Product already in cart or invalid");
    }
}

function removeProduct(productId) {
    productCart = productCart.filter(product => product && product.id != productId);
    updateProductCart();
    calculateProfit();
}

function updateProductCart() {
    console.log("Updating product cart");
    const productCartTable = document.getElementById('product_cart').getElementsByTagName('tbody')[0];
    productCartTable.innerHTML = '';

    productCart.forEach(product => {
        if (product && product.id && product.name && product.price) {
            const row = productCartTable.insertRow();
            row.insertCell(0).textContent = product.name;
            row.insertCell(1).textContent = 'Rp ' + parseInt(product.price).toLocaleString('id-ID');
            const removeCell = row.insertCell(2);
            const removeButton = document.createElement('button');
            removeButton.className = 'btn btn-danger btn-sm';
            removeButton.textContent = 'Remove';
            removeButton.onclick = () => removeProduct(product.id);
            removeCell.appendChild(removeButton);
        }
    });

    const totalCost = productCart.reduce((total, product) => total + (product && product.price ? parseFloat(product.price) : 0), 0);
    document.getElementById('total_cost').value = 'Rp ' + totalCost.toLocaleString('id-ID');

    document.getElementById('product_cart_input').value = JSON.stringify(productCart.filter(product => product !== null).map(product => product.id));
    console.log("Product cart updated:", productCart);
}

function calculateProfit() {
    let totalCost = parseFloat(document.getElementById('total_cost').value.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    let serviceCost = parseFloat(document.getElementById('cost').value.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
    console.log('Total Cost:', totalCost);
    console.log('Service Cost:', serviceCost);
    let profit = serviceCost - totalCost;
    document.getElementById('profit').value = 'Rp ' + profit.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM fully loaded");
    document.getElementById('cost').addEventListener('input', calculateProfit);
    document.getElementById('cost').addEventListener('blur', function() {
        let costValue = parseFloat(this.value.replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
        this.value = 'Rp ' + costValue.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        calculateProfit();
    });

    const addProductButton = document.querySelector('button[onclick="addProduct()"]');
    if (addProductButton) {
        addProductButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent form submission if it's inside a form
            addProduct();
        });
    } else {
        console.error("Add Product button not found");
    }

    updateProductCart();
    calculateProfit();
});
    </script>
</div>
</body>
</html>
