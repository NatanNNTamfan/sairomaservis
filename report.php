<?php include 'config.php'; ?>

<!-- Display financial report -->
<?php
$sql = "SELECT SUM(total_price) as total_revenue FROM sales";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "Total Revenue: " . $row["total_revenue"]. "<br>";
} else {
    echo "0 results";
}
$conn->close();
?>
?>
