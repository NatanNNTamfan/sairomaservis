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

$conn->close();
?>
<div class="container">
    <h1>Dashboard Overview</h1>
    <p>Total Stock: <?php echo $total_stock; ?></p>
    <p>Total Transactions: <?php echo $total_transactions; ?></p>
    <p>Total Sales: <?php echo $total_sales; ?></p>
    <form method="post" action="clear_data.php">
        <button type="submit" class="btn btn-danger mt-4">Clear All Data</button>
    </form>
</div>
</body>
</html>
