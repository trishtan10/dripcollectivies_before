<?php
session_start();
include 'connection.php';

$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($orderId <= 0) {
  header("Location: shop.php");
  exit();
}

$orderQuery = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$orderQuery->bind_param("i", $orderId);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
  header("Location: shop.php");
  exit();
}

$itemQuery = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$itemQuery->bind_param("i", $orderId);
$itemQuery->execute();
$itemResult = $itemQuery->get_result();

function getStatusBadgeClass($status) {
  switch ($status) {
    case 'Pending': return 'badge-warning text-dark';
    case 'Shipped': return 'badge-info';
    case 'Delivered': return 'badge-success';
    case 'Cancelled': return 'badge-danger';
    default: return 'badge-secondary';
  }
}

$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Success | Drip Collectives</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">

  <style>
    body { background-color: #f8f9fa; }
    .success-container {
      max-width: 750px;
      margin: 100px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      padding: 40px;
      text-align: center;
    }
    .success-icon {
      font-size: 70px;
      color: #28a745;
    }
    .table th, .table td {
      vertical-align: middle !important;
    }
    .btn-shop, .btn-orders {
      border-radius: 30px;
      padding: 10px 30px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: 0.3s;
    }
    .btn-shop {
      background-color: #000;
      color: #fff;
    }
    .btn-shop:hover {
      background-color: #ffc107;
      color: #000;
    }
    .btn-orders {
      background-color: #000;
      color: #fff;
    }
    .btn-orders:hover {
      background-color: #ffc107;
      color: #000;
    }
    .savings-text {
      color: #28a745;
      font-weight: bold;
      font-size: 15px;
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="success-container">
    <i class="fas fa-check-circle success-icon mb-3"></i>
    <h2 class="font-weight-bold mb-2">Thank You for Your Order!</h2>
    <p class="lead">Your order has been placed successfully.</p>

    <div class="mt-3 text-left">
      <p><strong>Order ID:</strong> #<?= htmlspecialchars($order['id']) ?></p>
      <p><strong>Date:</strong> <?= date("F j, Y, g:i a", strtotime($order['order_date'])) ?></p>
      <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
      <p><strong>Status:</strong>
        <span class="badge <?= getStatusBadgeClass($order['status']); ?>">
          <?= htmlspecialchars($order['status']) ?>
        </span>
      </p>
    </div>

    <hr>

    <h5 class="text-left mt-4 font-weight-bold">🧾 Order Summary</h5>
    <table class="table table-bordered text-center bg-white mt-2">
      <thead class="thead-dark">
        <tr>
          <th>Product ID</th>
          <th>Size</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $computed_total = 0;
        while ($item = $itemResult->fetch_assoc()):
          $subtotal = $item['price'] * $item['quantity'];
          $computed_total += $subtotal;
        ?>
        <tr>
          <td><?= htmlspecialchars($item['product_id']) ?></td>
          <td><?= htmlspecialchars($item['size']) ?></td>
          <td><?= htmlspecialchars($item['quantity']) ?></td>
          <td>₱<?= number_format($item['price'], 2) ?></td>
          <td>₱<?= number_format($subtotal, 2) ?></td>
        </tr>
        <?php endwhile; ?>

        <tr class="font-weight-bold">
          <td colspan="4" class="text-right">Total:</td>
          <td>₱<?= number_format($order['total_amount'], 2) ?></td>
        </tr>

        <?php
        $savings = $computed_total - $order['total_amount'];
        if ($savings > 0):
        ?>
        <tr>
          <td colspan="5" class="text-right savings-text">
            You saved ₱<?= number_format($savings, 2) ?> with promo code DRIP10 🎉
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div class="mt-4 text-left">
      <h6 class="font-weight-bold">📦 Shipping To:</h6>
      <p class="mb-0"><?= htmlspecialchars($order['customer_name']) ?></p>
      <p class="mb-0"><?= htmlspecialchars($order['address']) ?></p>
      <p><?= htmlspecialchars($order['email']) ?></p>
    </div>

    <div class="mt-4">
      <a href="shop.php" class="btn btn-shop mr-2">Continue Shopping</a>
      <?php if ($isLoggedIn): ?>
        <a href="my_orders.php" class="btn btn-orders">View My Orders</a>
      <?php endif; ?>
    </div>
  </div>

  <footer class="custom-footer py-4">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5>Drip Collectives</h5>
          <p>Founded in 2025, Drip Collectives was born from the energy of urban culture and the evolving world of
            street fashion. Our goal is to create timeless pieces that blend comfort, style, and individuality — streetwear
            designed for everyone, from the daily grind to the late-night scene.</p>
        </div>
        <div class="col-md-6 text-md-right">
          <a href="about.php" class="text-white mr-3">About</a>
          <a href="contact.php" class="text-white mr-3">Contact</a>
          <a href="policy.php" class="text-white">Policies</a>
        </div>
      </div>
    </div>
  </footer>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
</body>
</html>
