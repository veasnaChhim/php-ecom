<?php
session_start();
include("includes/db.php");
include("nav.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

    // Check if the email is already registered
    $checkUser = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        echo "Email is already registered.";
    } else {
        // Insert new user into the database
        $insertUser = $conn->prepare("INSERT INTO users (name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)");
        $insertUser->bind_param("sssss", $name, $email, $phone, $address, $password);
        $insertUser->execute();
        
        echo "Registration successful!";
        // Redirect to login page
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Create an Account</h2>
        <form action="register.php" method="post">
            <label>Full Name</label>
            <input type="text" name="name" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Phone</label>
            <input type="text" name="phone" required>

           <label>Address</label>
        <textarea name="address" required placeholder="#7, Street 269, Sangkat, Khan, Province"></textarea>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
