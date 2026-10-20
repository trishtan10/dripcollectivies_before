<?php
session_start();
include 'connection.php';

if (isset($_GET['remove']) && isset($_GET['size'])) {
  $remove_id = intval($_GET['remove']);
  $remove_size = $_GET['size'];

  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
      if (
        isset($item['id'], $item['size']) &&
        $item['id'] == $remove_id &&
        $item['size'] == $remove_size
      ) {
        unset($_SESSION['cart'][$key]);
        break;
      }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
  }

  header("Location: cart.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart | Drip Collectives</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<style>
.container.my-5.pt-5 {
  min-height: 65vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.btn-outline-secondary {
  background-color: #fff;
  color: #000;
}
.btn-outline-secondary:hover {
  background-color: #ff0000ff;
  color: #fff;
}
.btn-checkout {
  background-color: #000;
  color: #fff;
}
.btn-checkout:hover {
  background-color: #09c929ff;
  color: #000;
}
</style>

<body>
  <?php include 'navbar.php'; ?>

  <div class="container my-5 pt-5">
    <h2 class="mb-4 text-center font-weight-bold">Your Shopping Cart</h2>

    <?php if (!empty($_SESSION['cart'])) { ?>
      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
          <thead class="thead-dark">
            <tr>
              <th>Image</th>
              <th>Product</th>
              <th>Size</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
              if (!isset($item['id'], $item['size'], $item['quantity'], $item['price'], $item['name'])) continue;

              $product_id = intval($item['id']);
              $size = htmlspecialchars($item['size']);
              $quantity = intval($item['quantity']);

              $query = $conn->query("SELECT name, price, discount, image1, stock FROM products WHERE id = $product_id");
              if ($query && $row = $query->fetch_assoc()) {
                $name = htmlspecialchars($row['name']);
                $price = (float)$row['price'];
                $discount = (int)$row['discount'];
                $stock = (int)$row['stock'];
                $discountedPrice = ($discount > 0) ? $price - ($price * $discount / 100) : $price;

                if ($quantity > $stock) {
                  $quantity = $stock;
                  $item['quantity'] = $stock;
                }

                $subtotal = $discountedPrice * $quantity;
                $total += $subtotal;
                $image = htmlspecialchars($row['image1']);
            ?>
              <tr data-id="<?php echo $product_id; ?>" data-stock="<?php echo $stock; ?>">
                <td><img src="images/<?php echo $image; ?>" width="80" class="rounded"></td>
                <td><strong><?php echo $name; ?></strong></td>

                <td>
                  <?php if (strtolower($size) !== 'n/a') { ?>
                    <select class="form-control form-control-sm size-select">
                      <?php
                      $sizes = ['XS', 'S', 'M', 'L', 'XL', '2XL'];
                      foreach ($sizes as $opt) {
                        $selected = ($opt == $size) ? 'selected' : '';
                        echo "<option value='$opt' $selected>$opt</option>";
                      }
                      ?>
                    </select>
                  <?php } else { ?>
                    <span>N/A</span>
                  <?php } ?>
                </td>

                <td>
                  <?php if ($discount > 0) { ?>
                    <del>₱<?php echo number_format($price, 2); ?></del><br>
                    <span class="text-danger font-weight-bold">₱<?php echo number_format($discountedPrice, 2); ?></span><br>
                    <small class="text-success">(<?php echo $discount; ?>% OFF)</small>
                  <?php } else { ?>
                    ₱<?php echo number_format($price, 2); ?>
                  <?php } ?>
                </td>

                <td>
                  <input type="number" class="form-control form-control-sm quantity-input text-center"
                         value="<?php echo $quantity; ?>" min="1" max="<?php echo $stock; ?>" style="width:70px;">
                  <small class="text-muted d-block">Stock: <?php echo $stock; ?></small>
                </td>

                <td class="subtotal">₱<?php echo number_format($subtotal, 2); ?></td>
                <td>
                  <button class="btn btn-sm btn-danger btn-remove" 
                          data-url="cart.php?remove=<?php echo $product_id; ?>&size=<?php echo urlencode($size); ?>">
                    <i class="fas fa-trash"></i> Remove
                  </button>
                </td>
              </tr>
            <?php
              }
            }
            ?>
          </tbody>
        </table>
      </div>

      <div class="text-right mt-4">
        <h4>Total: ₱<span id="cartTotal"><?php echo number_format($total, 2); ?></span> PHP</h4>
        <a href="shop.php" class="btn btn-outline-dark mt-2">Continue Shopping</a>
        <button class="btn btn-dark mt-2" data-toggle="modal" data-target="#checkoutConfirmModal">Proceed to Checkout</button>
      </div>
    <?php } else { ?>
      <div class="text-center mt-5">
        <h4>Your cart is empty 🛒</h4>
        <a href="shop.php" class="btn btn-dark mt-3">Shop Now</a>
      </div>
    <?php } ?>
  </div>

  <div class="modal fade" id="checkoutConfirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title">🛒 Review Your Order</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <h6 class="text-center mb-4">Here’s a quick summary before checkout:</h6>
          <div class="table-responsive">
            <table class="table table-bordered text-center">
              <thead class="thead-light">
                <tr><th>Product</th><th>Size</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
              </thead>
              <tbody>
                <?php 
                $grandTotal = 0;
                foreach ($_SESSION['cart'] as $item) {
                  if (!isset($item['price'], $item['quantity'], $item['name'], $item['size'])) continue;
                  $subtotal = $item['price'] * $item['quantity'];
                  $grandTotal += $subtotal;
                  echo "
                    <tr>
                      <td>".htmlspecialchars($item['name'])."</td>
                      <td>".htmlspecialchars($item['size'])."</td>
                      <td>{$item['quantity']}</td>
                      <td>₱".number_format($item['price'], 2)."</td>
                      <td>₱".number_format($subtotal, 2)."</td>
                    </tr>";
                }
                ?>
              </tbody>
              <tfoot class="bg-light">
                <tr>
                  <th colspan="4" class="text-right">Total:</th>
                  <th>₱<?php echo number_format($grandTotal, 2); ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
          <p class="text-center mt-3">Would you like to proceed to checkout?</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-outline-secondary px-4" data-dismiss="modal">Cancel</button>
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="checkout.php" class="btn btn-checkout px-4">Yes, Proceed</a>
          <?php else: ?>
            <a href="login.php?redirect=checkout.php" class="btn btn-checkout px-4">Login to Proceed</a>
          <?php endif; ?>
        </div>
      </div>
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
  <script src="cart.js"></script>

<div class="modal fade" id="limitModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content text-center p-3">
      <h5 class="text-danger mb-2"><i class="fas fa-exclamation-triangle"></i> Stock Limit Reached</h5>
      <p class="mb-0" id="limitMessage">You cannot exceed available stock.</p>
      <button type="button" class="btn btn-dark mt-3" data-dismiss="modal">OK</button>
    </div>
  </div>
</div>

<div class="modal fade" id="removeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content text-center p-3">
      <h5 class="text-danger mb-2"><i class="fas fa-trash-alt"></i> Remove Item</h5>
      <p id="removeMessage" class="mb-0">Are you sure you want to remove this item?</p>
      <div class="mt-3">
        <button type="button" class="btn btn-sm btn-primary btn-remove" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-sm btn-danger btn-remove" id="confirmRemoveBtn">Remove</button>
      </div>
    </div>
  </div>
</div>

<script>
let removeUrl = null;

$(document).on('click', '.btn-remove', function(e) {
  e.preventDefault();
  removeUrl = $(this).data('url');
  $('#removeModal').modal('show');
});

$('#confirmRemoveBtn').on('click', function() {
  if (removeUrl) {
    window.location.href = removeUrl;
  }
});

$(document).on('input', '.quantity-input', function() {
  const maxStock = $(this).closest('tr').data('stock');
  let value = parseInt($(this).val());
  if (value > maxStock) {
    $(this).val(maxStock);
    $('#limitMessage').text('You can only order up to ' + maxStock + ' items of this product.');
    $('#limitModal').modal('show');
  }
});
</script>

</body>
</html>
