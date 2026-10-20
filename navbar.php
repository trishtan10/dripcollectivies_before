<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="images/logo.png" alt="Drip Collectives Logo" height="40">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item mx-2"><a class="nav-link text-black font-weight-bold" href="index.php">HOME</a></li>
        <li class="nav-item dropdown mx-2">
          <a class="nav-link dropdown-toggle text-black font-weight-bold" href="shop.php" id="shopDropdown"
             role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">SHOP</a>
          <div class="dropdown-menu" aria-labelledby="shopDropdown">
            <a class="dropdown-item" href="shop.php">Shop All</a>
            <a class="dropdown-item" href="tops.php">Tops</a>
            <a class="dropdown-item" href="bottoms.php">Bottoms</a>
            <a class="dropdown-item" href="outerwear.php">Outerwear</a>
            <a class="dropdown-item" href="accessories.php">Accessories</a>
            <a class="dropdown-item" href="funda.php">FUNDA Collection</a>
          </div>
        </li>
        <li class="nav-item mx-2"><a class="nav-link text-black font-weight-bold" href="about.php">ABOUT</a></li>
        <li class="nav-item mx-2"><a class="nav-link text-black font-weight-bold" href="contact.php">CONTACT</a></li>
        <li class="nav-item mx-2"><a class="nav-link text-black font-weight-bold" href="sale_items.php">SALE</a></li>
      </ul>
    </div>

    <div class="d-flex align-items-center">
      <form class="form-inline mr-3 position-relative" action="shop.php" method="get" style="width:200px;">
        <input 
          class="form-control form-control-sm w-100" 
          type="search" 
          name="search" 
          id="searchBar"
          placeholder="Search products..."
          aria-label="Search"
          autocomplete="off">
        <div id="suggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
      </form>

      <a href="cart.php" class="mr-3 position-relative">
        <i class="fas fa-shopping-cart text-black"></i>
        <?php if (!empty($_SESSION['cart'])): ?>
          <span class="badge badge-dark position-absolute" style="top:-8px; right:-10px; font-size:11px;">
            <?= count($_SESSION['cart']); ?>
          </span>
        <?php endif; ?>
      </a>

      <?php if (isset($_SESSION['user_id'])): ?>
        <div class="dropdown">
          <a href="#" class="dropdown-toggle text-black d-flex align-items-center" id="userDropdown"
             data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user text-black"></i>
            <span class="ml-1 font-weight-bold">
              Hi, <?= htmlspecialchars(explode(' ', $_SESSION['user_name'])[0]); ?>
            </span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <a class="dropdown-item" href="my_orders.php">My Orders</a>
            <a class="dropdown-item" href="profile.php">My Profile</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="logout.php">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="login.php" class="mr-3"><i class="fas fa-user text-black"></i></a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
  $('#searchBar').on('input', function(){
    let query = $(this).val().trim();

    if (query.length > 1) {
      $.ajax({
        url: '/dripcollectives_php/search_suggestions.php', 
        method: 'GET',
        dataType: 'json',
        data: { term: query },
        success: function(results){
          let html = '';
          if (results.length > 0) {
            results.forEach(item => {
              html += `
                <a href="product.php?id=${item.id}" 
                   class="list-group-item list-group-item-action d-flex align-items-center">
                  <img src="images/${item.image}" 
                       alt="${item.name}" 
                       style="width:40px;height:40px;object-fit:cover;border-radius:4px;margin-right:10px;">
                  <div>
                    <div class="font-weight-bold">${item.name}</div>
                    <small class="text-muted">₱${parseFloat(item.price).toFixed(2)}</small>
                  </div>
                </a>
              `;
            });
          } else {
            html = '<div class="list-group-item disabled">No products found</div>';
          }
          $('#suggestions').html(html).fadeIn(100);
        },
        error: function(xhr, status, error){
          console.error('AJAX Error:', error);
        }
      });
    } else {
      $('#suggestions').fadeOut(100);
    }
  });

  $(document).on('click', function(e){
    if (!$(e.target).closest('#searchBar, #suggestions').length) {
      $('#suggestions').fadeOut(100);
    }
  });
});
</script>

<style>
.navbar-nav .nav-link {
  margin: 0 10px;
  letter-spacing: 0.5px;
  transition: color 0.2s ease;
}
.navbar-nav .nav-link:hover {
  color: #555;
}

#suggestions {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  display: none;
  max-height: 300px;
  overflow-y: auto;
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
#suggestions .list-group-item {
  border: none;
  border-bottom: 1px solid #eee;
  padding: 8px 10px;
  transition: background 0.2s;
}
#suggestions .list-group-item:hover {
  background-color: #000;
  color: #fff;
}
</style>