<?php
session_start();

// ABA SANDBOX CREDENTIALS
$merchant_id     = "ec450030";
$api_key         = "ecb31e3b52b848dcbd04fd12124d0462";
$checkout_url    = "https://checkout-sandbox.payway.com.kh/checkout";

// Get order info from session
$order_id   = $_SESSION['order_id'] ?? rand(1000, 9999);
$amount     = $_SESSION['order_total'] ?? 0;

// URLs to return after payment
$return_url = "http://localhost/your_project/confirmation.php"; // Update this
$cancel_url = "http://localhost/your_project/checkout.php";     // Update this

// Prepare data for ABA Hosted Checkout
$data = [
    "merchant_id"      => $merchant_id,
    "order_id"         => $order_id,
    "amount"           => number_format($amount, 2, '.', ''),
    "return_url"       => $return_url,
    "cancel_url"       => $cancel_url,
    "transaction_type" => "sale"
];

// Create signature hash
ksort($data);
$query = http_build_query($data);
$data["hash"] = hash_hmac('sha256', $query, $api_key);

// Auto-redirect form
?>
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to ABA PayWay</title>
</head>
<body onload="document.forms['payForm'].submit();">
    <h2 style="text-align:center; color:#ff69b4;">Redirecting to ABA Payment...</h2>
    <form name="payForm" method="POST" action="<?= htmlspecialchars($checkout_url) ?>">
        <?php foreach ($data as $key => $value): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
        <?php endforeach; ?>
    </form>
</body>
</html>
