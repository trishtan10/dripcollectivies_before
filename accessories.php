<?php
session_start();
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accessories | Drip Collectives</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">

  <style>
    .badge-sale {
      position: absolute;
      top: 10px;
      left: 10px;
      background: red;
      color: #fff;
      padding: 5px 8px;
      font-size: 12px;
      border-radius: 4px;
      font-weight: bold;
    }
    .badge-out {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #000;
      color: #fff;
      padding: 5px 8px;
      font-size: 12px;
      border-radius: 4px;
      font-weight: bold;
    }
    .btn-unavailable {
      background-color: #ccc;
      color: #555;
      cursor: not-allowed;
      border: none;
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <section class="py-5 text-center bg-light mt-5">
    <div class="container">
      <h1 class="font-weight-bold">Accessories</h1>
      <p class="text-muted">Complete your look with our curated accessories collection.</p>
    </div>
  </section>

  <section class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="font-weight-bold mb-0">All Accessories</h3>

      <form method="GET" class="form-inline">
        <?php $selectedSort = $_GET['sort'] ?? ''; ?>
        <select name="sort" class="form-control form-control-sm" onchange="this.form.submit()">
          <option value="">Sort</option>
          <option value="az" <?= ($selectedSort == 'az') ? 'selected' : '' ?>>A - Z</option>
          <option value="za" <?= ($selectedSort == 'za') ? 'selected' : '' ?>>Z - A</option>
          <option value="low" <?= ($selectedSort == 'low') ? 'selected' : '' ?>>Price: Low → High</option>
          <option value="high" <?= ($selectedSort == 'high') ? 'selected' : '' ?>>Price: High → Low</option>
        </select>
      </form>
    </div>

    <div class="row">
      <?php
        $sort = $_GET['sort'] ?? '';
        $orderBy = match($sort) {
          'az' => "ORDER BY name ASC",
          'za' => "ORDER BY name DESC",
          'low' => "ORDER BY price ASC",
          'high' => "ORDER BY price DESC",
          default => "ORDER BY name ASC"
        };

        $query = "SELECT * FROM products WHERE category = 'Accessories' $orderBy";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $price = $row['price'];
            $discount = $row['discount'];
            $stock = $row['stock'];

            if ($discount > 0) {
              $discounted = $price - ($price * $discount / 100);
              $priceDisplay = "
                <p class='card-text mb-2'>
                  <del>₱" . number_format($price, 2) . "</del><br>
                  <span class='text-danger'>₱" . number_format($discounted, 2) . "</span><br>
                  <small class='text-success'>($discount% OFF)</small>
                </p>
              ";
              $sale = '<div class="badge-sale">SALE</div>';
            } else {
              $priceDisplay = "<p class='card-text font-weight-bold mb-2'>₱" . number_format($price, 2) . " PHP</p>";
              $sale = "";
            }

            echo "
            <div class='col-md-3 col-sm-6 mb-4'>
              <div class='card h-100 shadow-sm position-relative'>
                $sale";

                if ($stock <= 0) {
                  echo '<div class="badge-out">OUT OF STOCK</div>';
                }

                echo "
                <img src='images/".htmlspecialchars($row['image1'])."' class='card-img-top' alt='".htmlspecialchars($row['name'])."'>
                <div class='card-body text-center'>
                  <h5 class='card-title'>".htmlspecialchars($row['name'])."</h5>
                  $priceDisplay";

                  if ($stock <= 0) {
                    echo '<button class="btn btn-unavailable btn-sm" disabled>Unavailable</button>';
                  } else {
                    echo "<a href='product.php?id={$row['id']}' class='btn btn-outline-dark btn-sm'>View Item</a>";
                  }

                echo "
                </div>
              </div>
            </div>";
          }
        } else {
          echo "<div class='col-12 text-center'><p>No Accessories found.</p></div>";
        }
      ?>
    </div>

    <div class="text-center mt-4">
      <p>View All Products</p>
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
</body>
</html>
