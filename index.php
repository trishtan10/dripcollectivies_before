<?php
session_start();
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Drip Collectives | Streetwear Essentials</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">

<style>
.hero-img {
  width: 100%;
  height: 100vh;
  object-fit: cover;
}

@media (max-width: 992px) {
  .hero-img {
    height: 80vh;
    object-fit: cover;
  }
}

@media (max-width: 576px) {
  .hero-img {
    height: 60vh;
    object-fit: cover; 
    background-color: transparent;
  }

  .carousel-caption {
    bottom: 10%;
  }

  .carousel-caption h1 {
    font-size: 1.8rem;
  }

  .carousel-caption p {
    font-size: 1rem;
  }

  .carousel-caption .btn {
    padding: 6px 14px;
    font-size: 0.9rem;
  }
}
</style>
</head>

<body>

  <?php include 'navbar.php'; ?>

  <div id="heroCarousel" class="carousel slide carousel-fade" data-ride="carousel">
    <div class="carousel-inner">

      <div class="carousel-item active">
        <img src="images/dripmain.jpg" class="d-block w-100 hero-img" alt="Slide 1">
        <div class="carousel-caption">
          <h1 class="display-2 font-weight-bold text-white">DRIP COLLECTIVES</h1>
          <p class="lead text-white">Streetwear For Everyone</p>
          <a href="shop.php" class="btn btn-dark btn-lg">Shop Now</a>
        </div>
      </div>

      <div class="carousel-item">
        <img src="images/carouselsecond.jpg" class="d-block w-100 hero-img" alt="Slide 2">
        <div class="carousel-caption">
          <h1 class="display-2 font-weight-bold text-white">New Arrivals</h1>
          <p class="lead text-white">Discover Fresh Drops</p>
          <a href="shop.php" class="btn btn-dark btn-lg">Explore</a>
        </div>
      </div>

      <div class="carousel-item">
        <img src="images/allfunda.jpg" class="d-block w-100 hero-img" alt="Slide 3">
        <div class="carousel-caption">
          <a href="funda.php" class="btn btn-dark btn-lg">View Collection</a>
        </div>
      </div>

    </div>

    <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <section class="container my-5">
    <h2 class="text-center mb-5 font-weight-bold">Featured Collection</h2>
    <div class="row">
      <?php
      $featuredQuery = "
        SELECT * FROM products 
        WHERE name IN (
          'Haliburton Choke Tee (Unisex)', 
          'Open Collar Polo (Men)', 
          'Inked Overall Sweatpants (Unisex)', 
          'Grey Oversized Hoodie (Unisex)'
        )
      ";
      $result = $conn->query($featuredQuery);

      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '<div class="col-md-3 col-sm-6 mb-4">';
          echo '  <div class="card h-100">';
          echo '    <img src="images/' . $row['image1'] . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '">';
          echo '    <div class="card-body text-center">';
          echo '      <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
          echo '      <p class="card-text">₱' . number_format($row['price'], 2) . ' PHP</p>';
          echo '      <a href="product.php?id=' . $row['id'] . '" class="btn btn-outline-dark btn-sm">View Item</a>';
          echo '    </div>';
          echo '  </div>';
          echo '</div>';
        }
      } else {
        echo '<div class="col-12 text-center"><p>No featured products found.</p></div>';
      }
      ?>
    </div>
    <div class="text-center">
      <a href="shop.php" class="btn btn-dark">View All Products</a>
    </div>
  </section>

  <section class="container-fluid bg-light py-5">
    <h2 class="text-center mb-5 font-weight-bold">2025 Releases</h2>
    <div class="row justify-content-center">
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card shadow text-center">
          <img src="images/funda.jpg" class="card-img-top" alt="Fundamentals Drop" style="height: 250px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">Fundamentals Drop</h5>
            <a href="funda.php" class="btn btn-outline-dark">Shop</a>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 mb-3">
        <div class="card shadow text-center">
          <img src="images/about.jpg" class="card-img-top" alt="Street Essentials" style="height: 250px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">Street Essentials</h5>
            <a href="shop.php" class="btn btn-outline-dark">Shop</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="container my-5">
    <h2 class="text-center mb-5 font-weight-bold">Just In</h2>
    <div class="row">
      <?php
      $justIn = $conn->query("
        SELECT * FROM products
        WHERE name IN (
          'Blue Snapback Cap',
          'NYC Mesh Shorts (Men)',
          'Boxy Jersey Tee (Men)',
          'Oversized Polo Shirt (Women)'
        )
      ");
      
      if ($justIn && $justIn->num_rows > 0) {
        while ($prod = $justIn->fetch_assoc()) {
          echo '<div class="col-md-3 col-sm-6 mb-4">';
          echo '  <div class="card h-100">';
          echo '    <img src="images/'.$prod['image1'].'" class="card-img-top" alt="'.htmlspecialchars($prod['name']).'">';
          echo '    <div class="card-body text-center">';
          echo '      <h5 class="card-title">'.htmlspecialchars($prod['name']).'</h5>';
          echo '      <p class="card-text">₱'.number_format($prod['price'], 2).' PHP</p>';
          echo '      <a href="product.php?id='.$prod['id'].'" class="btn btn-outline-dark btn-sm">View Item</a>';
          echo '    </div>';
          echo '  </div>';
          echo '</div>';
        }
      } else {
        echo '<div class="col-12 text-center"><p>No new products found.</p></div>';
      }
      ?>
    </div>
    <div class="text-center">
      <a href="shop.php" class="btn btn-dark">View All Products</a>
    </div>
  </section>

  <section class="container-fluid bg-light py-5">
    <h2 class="text-center mb-5 font-weight-bold">Collections</h2>
    <div class="row justify-content-center">
      <div class="col-md-2 col-sm-6 mb-3 text-center">
        <img src="images/top.jpg" alt="Tops" class="img-fluid mb-2 rounded shadow" style="width: 100%; height: 250px; object-fit: cover;">
        <h5>Tops</h5>
        <a href="tops.php" class="btn btn-outline-dark">Shop</a>
      </div>
      <div class="col-md-2 col-sm-6 mb-3 text-center">
        <img src="images/bottoms.jpg" alt="Bottoms" class="img-fluid mb-2 rounded shadow" style="width: 100%; height: 250px; object-fit: cover;">
        <h5>Bottoms</h5>
        <a href="bottoms.php" class="btn btn-outline-dark">Shop</a>
      </div>
      <div class="col-md-2 col-sm-6 mb-3 text-center">
        <img src="images/outer.jpg" alt="Outerwear" class="img-fluid mb-2 rounded shadow" style="width: 100%; height: 250px; object-fit: cover;">
        <h5>Outerwear</h5>
        <a href="outerwear.php" class="btn btn-outline-dark">Shop</a>
      </div>
      <div class="col-md-2 col-sm-6 mb-3 text-center">
        <img src="images/mergecap1.jpg" alt="Accessories" class="img-fluid mb-2 rounded shadow" style="width: 100%; height: 250px; object-fit: cover;">
        <h5>Accessories</h5>
        <a href="accessories.php" class="btn btn-outline-dark">Shop</a>
      </div>
    </div>
    <div class="text-center mt-4">
      <p>Can't find what you're looking for?</p>
      <a href="shop.php" class="btn btn-dark">Shop All Products</a>
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

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
  <script src="script.js"></script>
</body>
</html>
