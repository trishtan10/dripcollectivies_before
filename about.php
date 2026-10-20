<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | Drip Collectives</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php include 'navbar.php'; ?>

  <section class="container my-5 mt-5">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <h2 class="text-center mb-5 font-weight-bold">Our Story</h2>
        <p class="lead text-center mb-4">
          Founded in 2025, Drip Collectives was born from the energy of urban culture and the evolving world of street fashion. 
          Our goal is to create timeless pieces that blend comfort, style, and individuality streetwear designed for everyone, 
          from the daily grind to the late-night scene. More than just clothing, we represent a lifestyle that celebrates creativity, 
          freedom, and self-expression.
        </p>
      </div>
    </div>

    <div class="row my-5">
      <div class="col-md-6">
        <img src="images/street.jpg" class="img-fluid rounded shadow" alt="Urban Street Style" style="height: 400px; object-fit: cover;">
      </div>
      <div class="col-md-6">
        <h3 class="font-weight-bold mb-3">Our Mission</h3>
        <p>
          We believe streetwear should be accessible yet premium, empowering individuals to express their unique identity. 
          From sustainable fabrics to bold designs inspired by city life, every piece is crafted with passion. 
          We're committed to inclusivity, offering unisex sizing and styles that transcend gender norms.
        </p>
        <a href="shop.php" class="btn btn-dark">Shop Now</a>
      </div>
    </div>

    <div class="row my-5">
      <div class="col-md-6 order-md-2">
        <img src="images/team.jpg" class="img-fluid rounded shadow" alt="Team Inspiration" style="height: 400px; object-fit: cover;">
      </div>
      <div class="col-md-6 order-md-1">
        <h3 class="font-weight-bold mb-3">Our Team</h3>
        <p>
          Our small but dedicated team of designers, artists, and urban enthusiasts hails from the streets of Manila. 
          Led by Midnight Devs, we draw inspiration from global trends while rooting our collections in local Filipino culture. 
          Join us in shaping the future of streetwear.
        </p>
        <a href="contact.php" class="btn btn-outline-dark">Get in Touch</a>
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
