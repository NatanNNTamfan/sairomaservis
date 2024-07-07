<?php include 'config.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Financial Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Service Report</h2>
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
            <div class="form-group col-md-5">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status">
                    <option value="all" <?php if (isset($_GET['status']) && $_GET['status'] == 'all') echo 'selected'; ?>>All</option>
                    <option value="Completed" <?php if (isset($_GET['status']) && $_GET['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                    <option value="Pending" <?php if (isset($_GET['status']) && $_GET['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Cancel" <?php if (isset($_GET['status']) && $_GET['status'] == 'Cancel') echo 'selected'; ?>>Cancel</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
            <div class="form-group col-md-2">
                <label>&nbsp;</label>
                <a href="export_to_excel.php?start_date=<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>&end_date=<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>&start_time=<?php echo isset($_GET['start_time']) ? $_GET['start_time'] : '00:00'; ?>&end_time=<?php echo isset($_GET['end_time']) ? $_GET['end_time'] : '23:59'; ?>&search=<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>&status=<?php echo isset($_GET['status']) ? $_GET['status'] : 'all'; ?>" class="btn btn-success btn-block">Export to Excel</a>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Service ID</th>
                <th>Customer Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Cost</th>
                <th>Used Products</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Profit</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? $_GET['start_date'] : '1970-01-01';
            $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
            $start_time = isset($_GET['start_time']) && !empty($_GET['start_time']) ? $_GET['start_time'] : '00:00:00';
            $end_time = isset($_GET['end_time']) && !empty($_GET['end_time']) ? $_GET['end_time'] : '23:59:59';
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $search = str_replace(' ', '', $search);
            $status = isset($_GET['status']) ? $_GET['status'] : 'all';
            $status_condition = $status !== 'all' ? "AND s.status = '$status'" : '';

            $sql = "SELECT s.id, c.name as customer_name, s.description, s.status, s.cost, s.created_at, s.updated_at, 
                           GROUP_CONCAT(p.name SEPARATOR ', ') as used_products,
                           (s.cost - IFNULL(SUM(p.hargabeli), 0)) as profit
                    FROM services s
                    JOIN customers c ON s.customer_id = c.id
                    LEFT JOIN service_products sp ON s.id = sp.service_id
                    LEFT JOIN products p ON sp.product_id = p.id
                    WHERE (s.created_at BETWEEN '$start_date $start_time' AND '$end_date $end_time')
                    AND (REPLACE(c.name, ' ', '') LIKE '%$search%' OR REPLACE(s.description, ' ', '') LIKE '%$search%')
                    $status_condition
                    GROUP BY s.id
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
                    echo "<td>" . $row["used_products"] . "</td>";
                    echo "<td>" . $row["created_at"] . "</td>";
                    echo "<td>" . $row["updated_at"] . "</td>";
                    echo "<td>Rp " . number_format($row["profit"], 0, ',', '.') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'info',
                            title: 'No Data',
                            text: 'No sales found for the selected criteria.'
                        }).then(function() {
                            window.location = 'report.php';
                        });
                      </script>";
            }
            ?>
            <?php
            $conn->close();
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
