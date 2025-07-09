<?php
$admin_email = "admin@example.com"; // change to your email
$order_id = $_SESSION['order_id'] ?? "N/A";
$subject = "New Payment Received – Order #$order_id";
$message = "A customer just paid successfully.\n\nOrder ID: $order_id";
$headers = "From: no-reply@yourdomain.com";

mail($admin_email, $subject, $message, $headers);
// confirmation.php (top)
include 'db.php';
$order_id = $_SESSION['order_id'] ?? null;

if ($order_id) {
    // Mark as paid
    $update = $conn->query("UPDATE orders SET payment_status='paid' WHERE id='$order_id'");

    // Send email
    $to = "admin@example.com";
    $subject = "New Order Paid: #$order_id";
    $message = "Customer completed payment.\nOrder ID: $order_id";
    mail($to, $subject, $message);
}
?>


<script>
alert("Payment completed! Admin has been notified.");
</script>
