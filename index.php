<?php
include 'config.php';

// Get total stock
$sql = "SELECT SUM(stock) as total_stock FROM products";
$result = $conn->query($sql);
$total_stock = $result->fetch_assoc()['total_stock'];

// Get total transactions
$sql = "SELECT COUNT(*) as total_transactions FROM sales";
$result = $conn->query($sql);
$total_transactions = $result->fetch_assoc()['total_transactions'];

$sql = "SELECT SUM(total) as total_sales FROM sales";
$result = $conn->query($sql);
$total_sales = $result->fetch_assoc()['total_sales'];

?>
<div class="container">
    <h1>Dashboard Overview</h1>
    <p>Total Stock: <?php echo $total_stock; ?></p>
    <p>Total Transactions: <?php echo $total_transactions; ?></p>
    <p>Total Sales: <?php echo $total_sales; ?></p>
    <form method="post" action="clear_data.php">
        <button type="submit" class="btn btn-danger mt-4">Clear All Data</button>
    </form>

    <h2>Add Customer</h2>
    <form method="post" action="process_add_customer.php">
        <div class="form-group">
            <label for="customer_name">Name:</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
        </div>
        <div class="form-group">
            <label for="customer_phone">Phone:</label>
            <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
        </div>
        <div class="form-group">
            <label for="customer_email">Email:</label>
            <input type="email" class="form-control" id="customer_email" name="customer_email">
        </div>
        <button type="submit" class="btn btn-primary">Add Customer</button>
    </form>

    <h2>Add Service</h2>
    <form method="post" action="process_add_service.php">
        <div class="form-group">
            <label for="customer_id">Customer:</label>
            <select class="form-control" id="customer_id" name="customer_id" required>
                <?php
                $sql = "SELECT id, name FROM customers";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No customers available</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status" required>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
        <div class="form-group">
            <label for="cost">Cost:</label>
            <input type="number" class="form-control" id="cost" name="cost" step="0.01">
        </div>
        <button type="submit" class="btn btn-primary">Add Service</button>
    </form>
    <h2>Customer List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Action</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM customers";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["phone"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["created_at"] . "</td>";
                    echo "<td><a href='edit_service.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a></td>";
                    echo "<td><a href='edit_service.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a></td>";
                    echo "<td><a href='edit_service.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a></td>";
                    echo "<td><a href='edit_service.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No customers found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h2>Service List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Description</th>
                <th>Status</th>
                <th>Cost</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT s.id, c.name as customer_name, s.description, s.status, s.cost, s.created_at, s.updated_at 
                    FROM services s 
                    JOIN customers c ON s.customer_id = c.id
                    ORDER BY s.created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["customer_name"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>Rp " . number_format($row["cost"], 0, ',', '.') . "</td>";
                    echo "<td>" . $row["created_at"] . "</td>";
                    echo "<td>" . $row["updated_at"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No services found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
