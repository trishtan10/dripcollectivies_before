<?php
session_start();
include 'connection.php';

$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$order = $conn->query("SELECT * FROM orders WHERE id = $orderId")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Success | Drip Collectives</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
</head>

<body class="bg-light">
  <div class="container text-center py-5">
    <div class="card p-5 shadow-lg">
      <h2 class="text-success font-weight-bold mb-3">✅ Order Placed Successfully!</h2>
      <p class="mb-4">Thank you for shopping with Drip Collectives, <strong><?= htmlspecialchars($order['customer_name']) ?></strong>!</p>
      <p>Your Order ID: <strong>#<?= $orderId ?></strong></p>
      <p>We’ll notify you once your order has been shipped.</p>
      <a href="shop.php" class="btn btn-dark mt-3">Back to Shop</a>
    </div>
  </div>
</body>
</html>
