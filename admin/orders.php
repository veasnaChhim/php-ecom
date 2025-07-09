<?php
session_start();
include("db.php");
include("nav.php");

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch orders information
$query = "SELECT o.id, o.user_id, o.total_amount, o.status, o.order_date, u.name AS user_name
          FROM orders o
          JOIN users u ON o.user_id = u.id
          ORDER BY o.order_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders - Admin Panel</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}
.container {
    width: 80%;
    margin: auto;
    padding-top: 50px;
}
h2 {
    color: #004080; /* changed from pink to deep blue */
    text-align: center;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
th {
    background-color: #004080; /* changed from pink to deep blue */
    color: white;
}
tr:hover {
    background-color: #f1f1f1;
}
.actions a {
    color: #004080; /* changed from pink to deep blue */
    text-decoration: none;
    margin-right: 10px;
    font-weight: bold;
}
.actions a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>

<div class="container">
    <h2>Orders Information</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td>$ <?= number_format($row['total_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($row['order_date'])) ?></td>
                        <td class="actions">
                            <a href="view_order.php?id=<?= $row['id'] ?>">View</a>
                            <a href="update_order.php?id=<?= $row['id'] ?>">Update</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
