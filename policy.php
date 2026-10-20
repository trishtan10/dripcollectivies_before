<?php
session_start();
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Policies | Drip Collectives</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="policy.css">
</head>

<body>
  <?php include 'navbar.php'; ?>


  <div class="policy-header mt-5">
    <div class="container">
      <h1>Store & Website Policies</h1>
      <p class="text-light">Learn about our company, returns, and website policies below.</p>
    </div>
  </div>

  <div class="container my-5 policy-container">
    <section>
      <h2>Company Policies</h2>
      <p>
        Drip Collectives is committed to delivering high-quality products and services. We uphold ethical business practices, 
        ensure transparency in pricing, and value customer satisfaction above all. We reserve the right to modify product availability 
        and pricing as needed without prior notice.
      </p>
    </section>

    <section>
      <h2>Return & Exchange Policy</h2>
      <p>
        Returns and exchanges are accepted within <strong>7 days</strong> of receiving your order, provided items are unused, 
        unwashed, and in their original packaging with tags attached. Shipping costs are non-refundable, and customers are 
        responsible for return shipping unless the item arrived damaged or incorrect.
      </p>
      <ul>
        <li>Proof of purchase (order receipt) is required for all returns.</li>
        <li>Sale items and accessories are non-refundable.</li>
        <li>Refunds will be processed within 3–7 business days after inspection.</li>
      </ul>
    </section>

    <section>
      <h2>Website Terms & Privacy</h2>
      <p>
        By accessing our website, you agree not to misuse our content or attempt unauthorized access. 
        Drip Collectives values your privacy — any personal information collected (e.g., for orders or subscriptions) 
        is securely stored and never shared with third parties without consent.
      </p>
      <p>
        Cookies may be used to enhance your browsing experience. You can manage cookie settings anytime through your browser.
      </p>
    </section>

    <section>
      <h2>Shipping Policy</h2>
      <p>
        Orders are processed within <strong>2–3 business days</strong> and shipped via trusted couriers. Delivery times 
        may vary depending on your location. You will receive a tracking number once your order ships.
      </p>
    </section>
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
