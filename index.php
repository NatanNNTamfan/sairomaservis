<?php
include 'config.php';
include 'navbar.html';


// Get total stock
$sql = "SELECT SUM(stock) as total_stock FROM products";
$result = $conn->query($sql);
$total_stock = $result->fetch_assoc()['total_stock'];

// Get total transactions
$sql = "SELECT COUNT(*) as total_transactions FROM sales";
$result = $conn->query($sql);
$total_transactions = $result->fetch_assoc()['total_transactions'];

// Get total sales
$sql = "SELECT SUM(total) as total_sales FROM sales";
$result = $conn->query($sql);
$total_sales = $result->fetch_assoc()['total_sales'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Overview</title>
    <link rel="stylesheet" href="path/to/your/bootstrap.css">
</head>
<body>
<div class="container">
    <h1>Dashboard Overview</h1>
    <p>Total Stock: <?php echo htmlspecialchars($total_stock ?? ''); ?></p>
    <p>Total Transactions: <?php echo htmlspecialchars($total_transactions); ?></p>
    <p>Total Sales: <?php echo htmlspecialchars($total_sales ?? ''); ?></p>
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
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
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
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM customers";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                    echo "<td>
                            <a href='edit_customer.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary btn-sm'>Edit</a>
                            <a href='delete_customer.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No customers found</td></tr>";
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
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["customer_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                    echo "<td>Rp " . number_format(htmlspecialchars($row["cost"]), 0, ',', '.') . "</td>";
                    echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["updated_at"]) . "</td>";
                    echo "<td>
                            <a href='edit_service.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-primary btn-sm'>Edit</a>
                            <button class='btn btn-danger btn-sm delete-service-btn' data-id='" . htmlspecialchars($row['id']) . "'>Delete</button>
                            <a href='delete_service.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No services found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<script src="path/to/your/bootstrap.js"></script>
<script>
    document.querySelectorAll('.delete-service-btn').forEach(button => {
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
                    fetch('delete_service.php', {
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
                                'Service has been deleted.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to delete service.',
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
</script>
</body>
</html>
