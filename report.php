<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Financial Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Financial Report</h2>
    <form method="get" action="">
        <div class="form-row">
            <div class="form-group col-md-5">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>">
            </div>
            <div class="form-group col-md-5">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="start_time">Start Time:</label>
                <input type="time" class="form-control" id="start_time" name="start_time" value="<?php echo isset($_GET['start_time']) ? $_GET['start_time'] : '00:00'; ?>">
            </div>
            <div class="form-group col-md-3">
                <label for="end_time">End Time:</label>
                <input type="time" class="form-control" id="end_time" name="end_time" value="<?php echo isset($_GET['end_time']) ? $_GET['end_time'] : '23:59'; ?>">
            </div>
            <div class="form-group col-md-5">
                <label for="search">Search:</label>
                <input type="text" class="form-control" id="search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            </div>
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Harga Jual</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Profit</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '1970-01-01';
            $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
            $start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '00:00:00';
            $end_time = isset($_GET['end_time']) ? $_GET['end_time'] : '23:59:59';
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $search = str_replace(' ', '', $search);
            $sql = "SELECT p.merk, p.name, s.quantity, s.price, s.discount, s.total, DATE(s.date) as date, TIME(s.date) as time, (s.price - p.hargabeli) * s.quantity - s.discount as profit 
                    FROM sales s 
                    JOIN products p ON s.product_id = p.id 
                    WHERE (s.date BETWEEN '$start_date $start_time' AND '$end_date $end_time')
                    AND (REPLACE(p.name, ' ', '') LIKE '%$search%' OR REPLACE(p.merk, ' ', '') LIKE '%$search%')
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
                    echo "<td>Rp " . number_format($row["profit"], 0, ',', '.') . "</td>";
                    echo "<td>" . $row["date"] . "</td>";
                    echo "<td>" . $row["time"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No sales found</td></tr>";
            }
            $conn->close();
            ?>
            <?php
            $sql_profit = "SELECT SUM((s.price - p.hargabeli) * s.quantity - s.discount) as total_profit 
                           FROM sales s 
                           JOIN products p ON s.product_id = p.id 
                           WHERE (s.date BETWEEN '$start_date $start_time' AND '$end_date $end_time')
                           AND (REPLACE(p.name, ' ', '') LIKE '%$search%' OR REPLACE(p.merk, ' ', '') LIKE '%$search%')";
            $result_profit = $conn->query($sql_profit);
            $total_profit = $result_profit->fetch_assoc()['total_profit'];
            ?>
        </tbody>
    </table>
    <h3>Total Profit: Rp <?php echo number_format($total_profit, 0, ',', '.'); ?></h3>
</div>
</body>
</html>
