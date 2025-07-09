<?php
session_start();
include("includes/db.php");
include("nav.php");

// Handle Add to Cart
if (isset($_GET['add'])) {
    $productId = (int)$_GET['add'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = 1;
    } else {
        $_SESSION['cart'][$productId]++;
    }
    echo "success";
    exit;
}

// Handle Remove from Cart
if (isset($_GET['remove'])) {
    $removeId = (int)$_GET['remove'];
    unset($_SESSION['cart'][$removeId]);
    header("Location: cart.php");
    exit;
}

// Handle Cash on Delivery Order
if (isset($_POST['place_cod_order'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('You must log in to place an order.'); window.location='login.php';</script>";
        exit;
    }

    $userId = $_SESSION['user_id'];
    $total = 0;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
        $total += $product['price'] * $qty;
    }

    // Insert Order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, total, payment_method, status) VALUES (?, NOW(), ?, 'COD', 'pending')");
    $stmt->bind_param("id", $userId, $total);
    $stmt->execute();
    $orderId = $stmt->insert_id;
    $stmt->close();

    // Insert Order Items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $id => $qty) {
        $product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
        $price = $product['price'];
        $stmt->bind_param("iiid", $orderId, $id, $qty, $price);
        $stmt->execute();
    }
    $stmt->close();

    // Clear Cart
    unset($_SESSION['cart']);

    echo "<script>alert('Order placed successfully with Cash on Delivery.'); window.location='orders.php';</script>";
    exit;
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
      
</head>
<body>
<div class="cart-container">
    <h2 style="color:srgb(53, 82, 243);">🛒 Your Shopping Cart</h2>

    <form method="post">
        <?php
        $total = 0;
        if (!empty($_SESSION['cart'])):
        ?>
        <table>
            <thead>
                <tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php
                foreach ($_SESSION['cart'] as $id => $qty):
                    $product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
                    $subtotal = $product['price'] * $qty;
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><input type="number" name="qty[<?= $id ?>]" value="<?= $qty ?>" min="1"></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                    <td><a href="cart.php?remove=<?= $id ?>" class="btn btn-danger">Remove</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        

        <div class="total">Total: $<?= number_format($total, 2) ?></div>

        <div class="btn-group">
            
            <a href="checkout.php" class="btn btn-success">Checkout</a>
        </div>

        <?php else: ?>
            <div class="empty-message">🛍️ Your cart is currently empty. <a href="shop.php" style="color:#004d99;">Go shopping!</a></div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
