<?php
session_start();
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Details | Drip Collectives</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    .low-stock {
      color: #ff0800ff;
      font-weight: bold;
      animation: pulse 1.5s infinite;
    }
  </style>
</head>
<body>

  <?php include 'navbar.php'; ?>

  <div class="container my-5 pt-5 pb-5" style="margin-top:250px; padding-top:120px; padding-bottom:250px;">
    <?php
    if (isset($_GET['id'])) {
      $id = intval($_GET['id']);
      $result = $conn->query("SELECT * FROM products WHERE id = $id");

      if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    ?>
        <div class="row">
          <div class="col-md-6 text-center">
            <img id="mainImage"
                 src="images/<?php echo htmlspecialchars($row['image1']); ?>"
                 class="img-fluid mb-3 border rounded shadow-sm"
                 alt="<?php echo htmlspecialchars($row['name']); ?>"
                 style="cursor:pointer;max-height:500px;object-fit:cover;"
                 onclick="openImageModal(this.src)">
            <div class="d-flex justify-content-center">
              <img src="images/<?php echo htmlspecialchars($row['image1']); ?>" class="img-thumbnail mr-2" style="width:90px;height:90px;object-fit:cover;" onclick="changeMainImage(this)">
              <img src="images/<?php echo htmlspecialchars($row['image2']); ?>" class="img-thumbnail mr-2" style="width:90px;height:90px;object-fit:cover;" onclick="changeMainImage(this)">
              <img src="images/<?php echo htmlspecialchars($row['image3']); ?>" class="img-thumbnail" style="width:90px;height:90px;object-fit:cover;" onclick="changeMainImage(this)">
            </div>
          </div>

          <div class="col-md-6">
            <h2><?php echo htmlspecialchars($row['name']); ?></h2>
            <p class="text-muted"><?php echo htmlspecialchars($row['category']); ?></p>

            <h4 class="font-weight-bold text-dark mb-3">
              <?php
                $price = $row['price'];
                $discount = $row['discount'];

                if ($discount > 0) {
                  $discountedPrice = $price - ($price * $discount / 100);
                  echo "<del class='text-muted'>₱" . number_format($price, 2) . "</del><br>";
                  echo "<span class='text-danger font-weight-bold'>₱" . number_format($discountedPrice, 2) . "</span> ";
                  echo "<small class='text-success'>($discount% OFF)</small>";
                } else {
                  $discountedPrice = $price;
                  echo "₱" . number_format($price, 2) . " PHP";
                }
              ?>
            </h4>

            <p><?php echo htmlspecialchars($row['description']); ?></p>
            
            <p>
              <strong>Stock:</strong> 
              <?php 
                if ($row['stock'] <= 5 && $row['stock'] > 0) {
                  echo "<span class='low-stock'>Only {$row['stock']} left in stock!</span>";
                } elseif ($row['stock'] == 0) {
                  echo "<span class='text-danger font-weight-bold'>Out of Stock</span>";
                } else {
                  echo $row['stock'];
                }
              ?>
            </p>

            <form id="addToCartForm">
              <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
              <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['name']); ?>">
              <input type="hidden" name="product_price" value="<?php echo $discountedPrice; ?>">
              <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($row['image1']); ?>">

              <?php if (strtolower($row['category']) !== 'accessories') { ?>
                <div class="form-group">
                  <label for="size">Size:</label>
                  <select id="size" name="size" class="form-control w-50" required>
                    <option value="" disabled selected>Select size</option>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="2XL">2XL</option>
                  </select>
                </div>
              <?php } else { ?>
                <input type="hidden" name="size" value="N/A">
              <?php } ?>

              <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="form-control w-25" value="1" min="1" max="<?php echo $row['stock']; ?>">
              </div>

              <?php if ($row['stock'] > 0): ?>
                <button type="submit" class="btn btn-dark">Add to Cart</button>
              <?php else: ?>
                <button type="button" class="btn btn-secondary" disabled>Out of Stock</button>
              <?php endif; ?>
            </form>

            <a href="shop.php" class="btn btn-outline-secondary mt-3">Back to Shop</a>
          </div>
        </div>

        <div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content bg-transparent border-0 text-center">
              <img id="modalImage" src="" class="img-fluid rounded shadow" alt="Enlarged Product" style="max-height:90vh;">
            </div>
          </div>
        </div>

        <div class="modal fade" id="cartModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content text-center p-5" style="border-radius: 15px;">
              <div class="d-flex flex-column align-items-center">
                <img id="cartModalImage" src="" alt="Product Image" class="rounded shadow mb-4" style="width:200px; height:auto; object-fit:cover;">
                <h5 id="cartModalTitle" class="font-weight-bold mb-2"></h5>
                <p id="cartModalSize" class="text-muted mb-4"></p>
                <div class="d-flex justify-content-center gap-3">
                  <a href="shop.php" class="btn btn-dark mr-2">Continue Shopping</a>
                  <a href="cart.php" class="btn btn-dark mr-2">View Cart</a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="limitModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content text-center p-3">
              <h5 class="text-danger mb-2"><i class="fas fa-exclamation-triangle"></i> Limit Reached</h5>
              <p class="mb-0">Value must be less than or equal to the available stock.</p>
              <button type="button" class="btn btn-dark mt-3" data-dismiss="modal">OK</button>
            </div>
          </div>
        </div>

    <?php
      } else {
        echo "<p class='text-center mt-5'>Product not found.</p>";
      }
    } else {
      echo "<p class='text-center mt-5'>Invalid product selection.</p>";
    }
    ?>
  </div>

  <footer class="custom-footer py-4">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5>Drip Collectives</h5>
          <p>Founded in 2025, Drip Collectives was born from the energy of urban culture and the evolving world of street fashion. Our goal is to create timeless pieces that blend comfort, style, and individuality — streetwear designed for everyone, from the daily grind to the late-night scene.</p>
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

  <script>
    function changeMainImage(el) {
      document.getElementById('mainImage').src = el.src;
    }

    function openImageModal(src) {
      document.getElementById('modalImage').src = src;
      $('#imageModal').modal('show');
    }

    $(document).ready(function() {
      $('#addToCartForm').on('submit', function(e) {
        e.preventDefault();

        const formData = $(this).serializeArray();
        const productName = formData.find(f => f.name === 'product_name').value;
        const productSize = $('#size').val();
        const productImage = 'images/' + formData.find(f => f.name === 'product_image').value;

        $.ajax({
          type: 'POST',
          url: 'add_to_cart.php',
          data: $(this).serialize(),
          dataType: 'json',
          success: function(response) {
            if (response.status === 'success') {
              $('#cartModalImage').attr('src', productImage);
              $('#cartModalTitle').text('✅ ' + productName + ' added to cart!');
              $('#cartModalSize').text('Size: ' + productSize);
              $('#cartModal').modal('show');
            } else {
              alert('Error adding to cart.');
            }
          }
        });
      });

      $('#quantity').on('input', function() {
        const maxStock = parseInt($(this).attr('max'));
        const value = parseInt($(this).val());
        if (value > maxStock) {
          $(this).val(maxStock);
          $('#limitModal').modal('show');
        }
      });
    });
  </script>
</body>
</html>
