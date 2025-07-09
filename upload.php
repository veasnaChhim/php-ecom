<?php
// DB connection (change to your real credentials)
$conn = new mysqli("localhost", "root", "", "ecommerce");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Initialize message
$message = "";
$success = false;

// Handle file upload
if (isset($_FILES["customer_file"])) {
  $file_name = basename($_FILES["customer_file"]["name"]);
  $target_dir = "uploads/";
  $target_file = $target_dir . $file_name;

  if (move_uploaded_file($_FILES["customer_file"]["tmp_name"], $target_file)) {
    // Insert to database
    $stmt = $conn->prepare("INSERT INTO uploads (customer_name, file_path) VALUES (?, ?)");
    $stmt->bind_param("ss", $customer_name, $target_file);
    $stmt->execute();
    $message = "File uploaded successfully!";
    $success = true;
  } else {
    $message = "Upload failed!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Upload Status</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <nav class="navbar">
    <a href="index.php">Home</a>
  </nav>

  <div class="message-box">
    <h2><?= $message ?></h2>
    <a class="back-link" href="pay.php">Go Back</a>
  </div>

</body>
</html>
