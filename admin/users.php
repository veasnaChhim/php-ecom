<?php
session_start();
include("db.php");
include("nav.php");

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch users
$query = "SELECT id, name, email, phone, address FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users - Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container {
    margin: 20px auto;
    width: 90%;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #005f73; /* subtle dark teal */
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table th, .users-table td {
    border: 1px solid #7bb3c9; /* soft blue border */
    padding: 10px;
    text-align: left;
}

.users-table th {
    background-color: #cce7f0; /* light blue background */
    color: #003d4c; /* dark blue text */
}

.users-table tr:hover {
    background-color: #e6f2f8; /* lighter blue hover */
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Registered Users</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
