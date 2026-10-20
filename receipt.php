<?php
session_start();
include 'connection.php';


if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($orderId <= 0) {
  header("Location: my_orders.php");
  exit();
}

$user_id = $_SESSION['user_id'];

$orderStmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$orderStmt->bind_param("ii", $orderId, $user_id);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
  header("Location: my_orders.php");
  exit();
}

$itemStmt = $conn->prepare("
  SELECT oi.*, p.name AS product_name, p.image1 AS product_image
  FROM order_items oi
  JOIN products p ON oi.product_id = p.id
  WHERE oi.order_id = ?
");
$itemStmt->bind_param("i", $orderId);
$itemStmt->execute();
$items = $itemStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt #<?= htmlspecialchars($orderId); ?> | Drip Collectives</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
    .receipt-container {
      background: white;
      border-radius: 10px;
      padding: 40px;
      max-width: 800px;
      margin: 80px auto;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .receipt-header img { height: 60px; }
    .table th, .table td { vertical-align: middle; }
    .product-img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 5px;
    }
    .btn-print {
      background-color: black;
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 25px;
      font-weight: bold;
    }
    .btn-print:hover { background-color: #333; }
    @media print {
      .btn-print, .navbar, footer { display: none; }
      body { background: white; }
      .receipt-container { box-shadow: none; margin-top: 0; }
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="receipt-container">
    <div class="text-center receipt-header mb-4">
      <img src="images/logo.png" alt="Drip Collectives Logo">
      <h3 class="mt-3 font-weight-bold">Official Receipt</h3>
      <p class="text-muted mb-0">Drip Collectives Clothing • www.dripcollectives.com</p>
    </div>

    <div class="row mb-4">
      <div class="col-md-6">
        <h6><strong>Order ID:</strong> #<?= $order['id']; ?></h6>
        <h6><strong>Date:</strong> <?= date("F j, Y g:i a", strtotime($order['order_date'])); ?></h6>
      </div>
      <div class="col-md-6 text-md-right">
        <h6><strong>Status:</strong> 
          <span class="badge badge-<?php
            if ($order['status'] == 'Pending') echo 'warning';
            elseif ($order['status'] == 'Shipped') echo 'info';
            elseif ($order['status'] == 'Delivered') echo 'success';
            else echo 'danger';
          ?>"><?= htmlspecialchars($order['status']); ?></span>
        </h6>
        <h6><strong>Payment:</strong> <?= htmlspecialchars($order['payment_method']); ?></h6>
      </div>
    </div>

    <hr>

    <div class="mb-4">
      <h6><strong>Customer Information</strong></h6>
      <p class="mb-1"><?= htmlspecialchars($order['customer_name']); ?></p>
      <p class="mb-1"><?= htmlspecialchars($order['email']); ?></p>
      <p><?= htmlspecialchars($order['address']); ?></p>
    </div>

    <table class="table table-bordered text-center">
      <thead class="thead-dark">
        <tr>
          <th>Image</th>
          <th>Product</th>
          <th>Size</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $grandTotal = 0;
        while ($item = $items->fetch_assoc()):
          $subtotal = $item['price'] * $item['quantity'];
          $grandTotal += $subtotal;
        ?>
        <tr>
          <td><img src="images/<?= htmlspecialchars($item['product_image']); ?>" class="product-img"></td>
          <td><?= htmlspecialchars($item['product_name']); ?></td>
          <td><?= htmlspecialchars($item['size']); ?></td>
          <td><?= $item['quantity']; ?></td>
          <td>₱<?= number_format($item['price'], 2); ?></td>
          <td>₱<?= number_format($subtotal, 2); ?></td>
        </tr>
        <?php endwhile; ?>
        <tr class="font-weight-bold">
          <td colspan="5" class="text-right">Total:</td>
          <td>₱<?= number_format($grandTotal, 2); ?></td>
        </tr>
      </tbody>
    </table>

    <div class="text-center mt-4">
      <button class="btn-print" onclick="window.print()">🖨️ Print Receipt</button>
      <a href="my_orders.php" class="btn btn-secondary ml-2">← Back to Orders</a>
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
</body>
</html>
