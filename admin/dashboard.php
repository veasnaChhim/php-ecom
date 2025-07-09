<?php
session_start();
include("db.php");
include("nav.php");

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Count total users
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// Count total products
$productCount = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];

// Count total orders
$orderCount = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}
.container {
    padding: 40px;
    max-width: 1000px;
    margin: auto;
}
h2 {
    text-align: center;
    color: #008080; /* changed from pink */
    margin-bottom: 30px;
}
.stats {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}
.card {
    flex: 1 1 30%;
    background-color: white;
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.card h3 {
    margin: 0 0 10px;
    color: #333;
}
.card p {
    font-size: 28px;
    color: #008080; /* changed from pink */
    margin: 0;
}
@media (max-width: 768px) {
    .card {
        flex: 1 1 100%;
    }
}
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome to Admin Dashboard</h2>
    <div class="stats">
        <div class="card">
            <h3>Total Users</h3>
            <p><?= $userCount ?></p>
        </div>
        <div class="card">
            <h3>Total Products</h3>
            <p><?= $productCount ?></p>
        </div>
        <div class="card">
            <h3>Total Orders</h3>
            <p><?= $orderCount ?></p>
        </div>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
