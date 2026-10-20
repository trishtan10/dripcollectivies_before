<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php?redirect=checkout.php");
  exit;
}

if (empty($_SESSION['cart'])) {
  header("Location: cart.php");
  exit();
}

$user_id = $_SESSION['user_id'] ?? NULL;
$user_name = $_SESSION['user_name'] ?? '';
$user_email = $_SESSION['user_email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $address = trim($_POST['address']);
  $payment = trim($_POST['payment']);
  $order_date = date("Y-m-d H:i:s");
  $status = "Pending";
  $total = 0;

  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }

  $promo_code = trim($_POST['promo_code'] ?? '');
  if (strtoupper($promo_code) === "DRIP10") {
    $discount_amount = $total * 0.10;
    $total -= $discount_amount;
  }

  $proof_path = null;
  if ($payment === 'GCash') {
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
      $target_dir = "uploads/proofs/";
      if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
      $file_name = time() . "_" . basename($_FILES['payment_proof']['name']);
      $target_file = $target_dir . $file_name;
      move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_file);
      $proof_path = $target_file;
    } else {
      echo "<script>alert('Please upload your GCash payment proof before placing the order.'); window.history.back();</script>";
      exit;
    }
  }

  $card_name = $_POST['card_name'] ?? null;
  $card_number = $_POST['card_number'] ?? null;
  $card_expiry = $_POST['card_expiry'] ?? null;
  $card_cvv = $_POST['card_cvv'] ?? null;

  if ($payment === 'Credit/Debit Card' && (empty($card_name) || empty($card_number) || empty($card_expiry) || empty($card_cvv))) {
    echo "<script>alert('Please add your card details before placing the order.'); window.history.back();</script>";
    exit;
  }

  if ($user_id) {
    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, email, address, payment_method, total_amount, order_date, status, payment_proof, card_name, card_number, card_expiry, card_cvv)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssisssssss", $user_id, $name, $email, $address, $payment, $total, $order_date, $status,
      $proof_path, $card_name, $card_number, $card_expiry, $card_cvv);
  } else {
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, email, address, payment_method, total_amount, order_date, status, payment_proof, card_name, card_number, card_expiry, card_cvv)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisssssss", $name, $email, $address, $payment, $total, $order_date, $status,
      $proof_path, $card_name, $card_number, $card_expiry, $card_cvv);
  }

  if (!$stmt->execute()) {
    die("Order insert failed: " . $stmt->error);
  }

  $order_id = $stmt->insert_id;

  foreach ($_SESSION['cart'] as $item) {
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, size, quantity, price)
                                 VALUES (?, ?, ?, ?, ?)");
    $stmt_item->bind_param("iisid", $order_id, $item['id'], $item['size'], $item['quantity'], $item['price']);
    $stmt_item->execute();
  }

  $_SESSION['cart'] = [];
  header("Location: thankyou.php?id=$order_id");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout | Drip Collectives</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">

  <style>
    .container.my-5.pt-5 {
      min-height: 63vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .payment-section { display: none; margin-top: 15px; }
    .card-box {
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 12px;
      margin-top: 10px;
      background: #f8f9fa;
    }
    .btn-secondary { background-color: #0a1bffff; color: #fff; }
    .btn-secondary:hover { background-color: #000; color: #fff; }
    .promo-box {
      background: #f9f9f9;
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="container my-5 pt-5">
    <h2 class="text-center mb-4 font-weight-bold">Checkout</h2>

    <form method="POST" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6">
          <h4>Customer Details</h4>

          <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user_name) ?>" required>
          </div>

          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user_email) ?>" required>
          </div>

          <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" rows="3" placeholder="Enter delivery address" required></textarea>
          </div>

          <div class="form-group">
            <label>Payment Method</label>
            <select name="payment" id="payment" class="form-control" required>
              <option value="">Select Payment</option>
              <option value="Cash on Delivery">Cash on Delivery</option>
              <option value="GCash">GCash</option>
              <option value="Credit/Debit Card">Credit/Debit Card</option>
            </select>
          </div>

          <div id="gcash-section" class="payment-section">
            <h5>Pay via GCash</h5>
            <img src="images/gcashqr.jpg" alt="GCash QR" class="img-fluid mb-3" style="max-width:200px;">
            <div class="form-group">
              <label>Upload Proof of Payment</label>
              <input type="file" name="payment_proof" id="payment_proof" class="form-control-file" accept="image/*">
            </div>
          </div>

          <div id="card-section" class="payment-section">
            <h5>Credit / Debit Card Details</h5>

            <div id="add-card-form">
              <div class="form-group">
                <label>Cardholder Name</label>
                <input type="text" name="card_name" id="card_name" class="form-control">
              </div>
              <div class="form-group">
                <label>Card Number</label>
                <input type="text" name="card_number" id="card_number" class="form-control" maxlength="19" placeholder="XXXX XXXX XXXX XXXX">
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Expiry Date</label>
                  <input type="text" name="card_expiry" id="card_expiry" class="form-control" placeholder="MM/YY">
                </div>
                <div class="form-group col-md-6">
                  <label>CVV</label>
                  <input type="text" name="card_cvv" id="card_cvv" class="form-control" maxlength="4">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <h4>Order Summary</h4>

          <ul class="list-group mb-3">
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
              $subtotal = $item['price'] * $item['quantity'];
              $total += $subtotal;
            ?>
              <li class="list-group-item d-flex justify-content-between">
                <span><?= htmlspecialchars($item['name']) ?> (<?= $item['quantity'] ?>x)</span>
                <strong>₱<?= number_format($subtotal, 2) ?></strong>
              </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between font-weight-bold">
              <span>Total:</span>
              <strong id="order-total">₱<?= number_format($total, 2) ?></strong>
            </li>
          </ul>

          <div class="promo-box">
            <label for="promo_code"><strong>Promo Code</strong></label>
            <div class="input-group">
              <input type="text" name="promo_code" id="promo_code" class="form-control" placeholder="Enter code">
              <div class="input-group-append">
                <button type="button" id="applyPromo" class="btn btn-outline-dark">Apply</button>
              </div>
            </div>
            <small id="promoMessage" class="text-success mt-2 d-block"></small>
          </div>

          <button type="submit" class="btn btn-dark btn-block">Place Order</button>
        </div>
      </div>
    </form>
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
  <script>
    $('#payment').on('change', function() {
      $('.payment-section').hide();
      const method = $(this).val();
      if (method === 'GCash') $('#gcash-section').show();
      if (method === 'Credit/Debit Card') $('#card-section').show();
    });


    const originalTotal = <?= $total ?>;
    $('#applyPromo').on('click', function() {
      const code = $('#promo_code').val().trim().toUpperCase();
      let newTotal = originalTotal;

      if (code === 'DRIP10') {
        newTotal = originalTotal * 0.9;
        $('#promoMessage').text('Promo applied! 10% discount has been applied.');
      } else if (code === '') {
        $('#promoMessage').text('Please enter a promo code.').css('color', 'red');
      } else {
        $('#promoMessage').text('Invalid promo code.').css('color', 'red');
      }

      $('#order-total').text('₱' + newTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    });

    $('#card_number').on('input', function() {
      this.value = this.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim().slice(0, 19);
    });
  </script>
</body>
</html>
