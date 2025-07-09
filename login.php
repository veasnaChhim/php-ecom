<?php
session_start();
include("includes/db.php");
include("nav.php");

$error = "";

if (isset($_SESSION['user_id'])) {
    header("Location: shop.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Check if the email exists
    $checkUser = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $result = $checkUser->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Store user info in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        header("Location: shop.php");
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 400px; margin: auto; padding: 30px;">
        <h2 style="text-align: center;">Login to Your Account</h2>

        <?php if (!empty($error)): ?>
            <div style="color: red; text-align:center; margin-bottom: 15px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <label>Email</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; margin-bottom: 15px;">

            <label>Password</label>
            <input type="password" name="password" required style="width: 100%; padding: 10px; margin-bottom: 15px;">

            <button type="submit" class="btn" style="width: 100%;">Login</button>
        </form>

        <p style="text-align: center; margin-top: 15px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</body>
</html>
