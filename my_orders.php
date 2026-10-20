<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders | Drip Collectives</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">

  <style>
    body { background-color: #f8f9fa; }
    .order-card {
      border-radius: 12px;
      padding: 25px;
      margin-bottom: 30px;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .order-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
      margin-bottom: 15px;
    }
    .status-badge {
      padding: 6px 12px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 14px;
      text-transform: capitalize;
    }
    .status-pending { background: #ffc107; color: #000; }
    .status-shipped { background: #17a2b8; color: #fff; }
    .status-delivered { background: #28a745; color: #fff; }
    .status-cancelled { background: #dc3545; color: #fff; }

    .product-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 8px;
    }
    .product-name {
      font-weight: 600;
      color: #000;
    }
    table th, table td {
      vertical-align: middle !important;
    }
    .btn-dark {
      border-radius: 8px;
      font-weight: 500;
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="container my-5 pt-5">
    <h2 class="text-center mb-5 font-weight-bold">My Orders</h2>

    <?php if ($orders->num_rows > 0): ?>
      <?php while ($order = $orders->fetch_assoc()): ?>
        <div class="order-card">
          <div class="order-header">
            <h5>Order #<?= $order['id']; ?> — ₱<?= number_format($order['total_amount'], 2); ?></h5>
            <div class="text-right">
              <span class="status-badge status-<?= strtolower($order['status']); ?>">
                <?= htmlspecialchars($order['status']); ?>
              </span>
              <br>
              <small class="text-muted"><?= date("F j, Y, g:i a", strtotime($order['order_date'])); ?></small>
            </div>
          </div>

          <div class="mb-3">
            <p class="mb-1"><strong>Payment:</strong> <?= htmlspecialchars($order['payment_method']); ?></p>
            <p class="mb-1"><strong>Address:</strong> <?= htmlspecialchars($order['address']); ?></p>
          </div>

          <?php
          $items = $conn->prepare("
            SELECT oi.*, p.name AS product_name, p.image1 AS product_image 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
          ");
          $items->bind_param("i", $order['id']);
          $items->execute();
          $result_items = $items->get_result();
          ?>
          
          <table class="table table-bordered text-center bg-white">
            <thead class="thead-dark">
              <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $total = 0;
              while ($item = $result_items->fetch_assoc()): 
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
              ?>
                <tr>
                  <td><img src="images/<?= htmlspecialchars($item['product_image']); ?>" class="product-img"></td>
                  <td class="product-name"><?= htmlspecialchars($item['product_name']); ?></td>
                  <td><?= htmlspecialchars($item['size']); ?></td>
                  <td><?= $item['quantity']; ?></td>
                  <td>₱<?= number_format($item['price'], 2); ?></td>
                  <td>₱<?= number_format($subtotal, 2); ?></td>
                </tr>
              <?php endwhile; ?>
              <tr class="font-weight-bold">
                <td colspan="5" class="text-right">Total:</td>
                <td>₱<?= number_format($total, 2); ?></td>
              </tr>
            </tbody>
          </table>

          <div class="text-right">
            <a href="thankyou.php?id=<?= $order['id']; ?>" class="btn btn-dark btn-sm">
              View Details
            </a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="text-center mt-5">
        <h4>You have no orders yet 🛍️</h4>
        <a href="shop.php" class="btn btn-dark mt-3">Shop Now</a>
      </div>
    <?php endif; ?>
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
