<?php
session_start();
include 'connection.php';

$alert = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $message = trim($_POST['message']);

  if ($name && $email && $message) {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    if ($stmt->execute()) {
      $alert = "<div class='alert alert-success'>Thank you, your message has been sent successfully!</div>";
    } else {
      $alert = "<div class='alert alert-danger'>Something went wrong. Please try again later.</div>";
    }
    $stmt->close();
  } else {
    $alert = "<div class='alert alert-warning'>Please fill in all fields.</div>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Drip Collectives</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php include 'navbar.php'; ?>

  <section class="container my-5 pt-5">
    <div class="row">
      <div class="col-md-6">
        <h2 class="font-weight-bold mb-4">Send Us a Message</h2>

        <?= $alert ?>

        <form action="contact.php" method="POST">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Your Email" required>
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" class="form-control" id="message" rows="5" placeholder="Your Message" required></textarea>
          </div>
          <button type="submit" class="btn btn-dark">Send Message</button>
        </form>
      </div>

      <div class="col-md-6">
        <h2 class="font-weight-bold mb-4">Contact Information</h2>
        <p><i class="fas fa-map-marker-alt mr-2"></i>24 Ligaya Street, Manila, Philippines</p>
        <p><i class="fas fa-phone mr-2"></i>+63 912 345 6789</p>
        <p><i class="fas fa-envelope mr-2"></i>dripcollectives@gmail.com</p>
        <p><i class="fas fa-clock mr-2"></i>Mon - Fri: 9AM - 6PM | Sat - Sun: 10AM - 8PM</p>
        <div class="mt-4">
          <a href="https://www.instagram.com" class="text-black mr-3"><i class="fab fa-instagram fa-2x"></i></a>
          <a href="https://www.tiktok.com" class="text-black mr-3"><i class="fab fa-tiktok fa-2x"></i></a>
          <a href="https://www.facebook.com" class="text-black"><i class="fab fa-facebook fa-2x"></i></a>
        </div>
      </div>
    </div>
  </section>

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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
</body>
</html>
