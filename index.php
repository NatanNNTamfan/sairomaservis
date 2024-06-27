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

// Get total sales
$sql = "SELECT SUM(total_price) as total_sales FROM sales";
$result = $conn->query($sql);
$total_sales = $result->fetch_assoc()['total_sales'];

$conn->close();
?>

<?php include 'config.php'; ?>
    <h1>Dashboard Overview</h1>
    <p>Total Stock: <?php echo $total_stock; ?></p>
    <p>Total Transactions: <?php echo $total_transactions; ?></p>
    <p>Total Sales: <?php echo $total_sales; ?></p>
</body>
</html>
