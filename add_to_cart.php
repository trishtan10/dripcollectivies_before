<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = intval($_POST['product_id']);
  $name = $_POST['product_name'];
  $price = floatval($_POST['product_price']);
  $image = $_POST['product_image'];
  $quantity = intval($_POST['quantity']);
  $size = $_POST['size'];

  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  $found = false;
  foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $id && $item['size'] == $size) {
      $item['quantity'] += $quantity;
      $found = true;
      break;
    }
  }

  if (!$found) {
    $_SESSION['cart'][] = [
      'id' => $id,
      'name' => $name,
      'price' => $price,
      'image' => $image,
      'quantity' => $quantity,
      'size' => $size
    ];
  }

  echo json_encode(['status' => 'success']);
} else {
  echo json_encode(['status' => 'error']);
}
?> 