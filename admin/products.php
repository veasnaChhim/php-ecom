<?php
include('db.php');
include("nav.php");

$desc = htmlspecialchars($row['description'] ?? '');
// Handle product deletion
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $sql = "DELETE FROM products WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Product deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: auto;
    padding-top: 60px;
}

h2 {
    color: #008080;  /* changed from #ff4081 (pink) to teal */
    text-align: center;
    margin-bottom: 30px;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 25px;
}

.product-card {
    background: #e6f3ff;  /* light blue background */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.2s;
}

.product-card:hover {
    transform: translateY(-8px);
}

.product-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.product-info {
    margin-top: 15px;
}

.product-name {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

.product-price {
    color: #008080; /* changed from #ff4081 to teal */
    margin: 8px 0;
    font-size: 16px;
}

.product-stock {
    font-size: 14px;
    color: #444;
    margin-bottom: 10px;
}

.product-desc {
    font-size: 14px;
    color: #777;
}

.actions {
    margin-top: 15px;
}

.actions a {
    text-decoration: none;
    color: #008080; /* changed from #ff4081 to teal */
    margin: 0 5px;
    font-weight: bold;
}

.actions a:hover {
    text-decoration: underline;
}

    </style>
</head>

<body>
    <div class="container">
        <h2>Product List</h2>

        <div class="product-grid">
            <?php
            $sql = "SELECT id, name, image, price, description, stock FROM products ORDER BY name ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $id    = (int) $row['id'];
                    $name  = htmlspecialchars($row['name'] ?? '');
                    $price = htmlspecialchars($row['price'] ?? '0.00');
                    $desc  = htmlspecialchars($row['description'] ?? '');  // ← FIXED HERE
                    $image = htmlspecialchars($row['image'] ?? 'default.jpg');
                    $stock = (int) $row['stock'];

            ?>
                    <div class="product-card">
                        <img class="product-img" src="../images/<?= $image ?>" alt="<?= $name ?>">
                        <div class="product-info">
                            <div class="product-name"><?= $name ?></div>
                            <div class="product-price">$<?= $price ?></div>
                            <div class="product-stock">Stock: <?= $stock ?></div>
                            <div class="product-desc"><?= $desc ?></div>
                        </div>
                        <div class="actions">
                            <a href="edit_product.php?id=<?= $id ?>">Edit</a>
                            <a href="products.php?delete_id=<?= $id ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </div>
                    </div>
                <?php endwhile;
            else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>