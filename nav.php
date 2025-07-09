<nav class="navbar">
    <div>
        <a href="index.php"><img src="images/images.png" alt="Logo" style="height: 50px;"></a>
        <a href="index.php">Home</a>
        <a href="Shop.php">Shop</a>
        <a href="Cart.php">Cart</a>
        <a href="Checkout.php">Checkout</a>
    </div>
    <div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Hello, <?= $_SESSION['user_name'] ?>!</span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a> | <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>
