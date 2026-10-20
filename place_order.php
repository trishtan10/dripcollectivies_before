<?php
session_start();
include 'connection.php';

if (empty($_SESSION['cart']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: shop.php");
  exit();
}

$fullname = htmlspecialchars($_POST['fullname']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$address = htmlspecialchars($_POST['address']);
$payment = htmlspecialchars($_POST['payment']);


$total = 0;
foreach ($_SESSION['cart'] as $item) {
  $total += $item['price'] * $item['quantity'];
}


$order_items = $_SESSION['cart'];


unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Placed | Drip Collectives</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="container my-5 pt-5 text-center">
    <div class="card shadow-lg p-5">
      <div class="text-success mb-4">
        <i class="fas fa-check-circle fa-4x"></i>
      </div>
      <h2 class="font-weight-bold">Order Successfully Placed!</h2>
      <p class="text-muted mb-4">Thank you, <?php echo $fullname; ?>. Your order has been received and is being processed.</p>

      <h4 class="mt-4 mb-3 text-left">Order Summary</h4>
      <div class="table-responsive">
        <table class="table table-bordered text-center">
          <thead class="thead-dark">
            <tr>
              <th>Product</th>
              <th>Size</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($order_items as $item) { 
              $subtotal = $item['price'] * $item['quantity'];
            ?>
              <tr>
                <td>
                  <img src="images/<?php echo htmlspecialchars($item['image']); ?>" width="60" class="rounded mr-2">
                  <?php echo htmlspecialchars($item['name']); ?>
                </td>
                <td><?php echo ($item['size'] != 'N/A') ? $item['size'] : '-'; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                <td>₱<?php echo number_format($subtotal, 2); ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <h4 class="text-right">Total: ₱<?php echo number_format($total, 2); ?> PHP</h4>
      <hr>

      <div class="text-left mt-4">
        <h5>Shipping To:</h5>
        <p>
          <?php echo $fullname; ?><br>
          <?php echo $address; ?><br>
          <?php echo $email; ?><br>
          <?php echo $phone; ?><br>
          <strong>Payment Method:</strong> <?php echo $payment; ?>
        </p>
      </div>

      <a href="shop.php" class="btn btn-dark mt-4">Continue Shopping</a>
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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
</body>
</html>
