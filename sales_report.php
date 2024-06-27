<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Sales Report</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Harga Jual</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT p.merk, p.name, s.quantity, s.price, s.discount, s.total, s.date 
                    FROM sales s 
                    JOIN products p ON s.product_id = p.id 
                    ORDER BY s.date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["merk"] . " " . $row["name"] . "</td>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>Rp " . number_format($row["price"], 0, ',', '.') . "</td>";
                    echo "<td>Rp " . number_format($row["discount"], 0, ',', '.') . "</td>";
                    echo "<td>Rp " . number_format($row["total"], 0, ',', '.') . "</td>";
                    echo "<td>" . $row["date"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No sales found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
