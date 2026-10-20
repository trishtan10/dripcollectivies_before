<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);
  $newSize = $_POST['size'];
  $newQuantity = intval($_POST['quantity']);

  if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['id'] == $id) {
        if (!empty($newSize)) $item['size'] = $newSize;
        if ($newQuantity > 0) $item['quantity'] = $newQuantity;
        break;
      }
    }
  }

  echo json_encode(['status' => 'success']);
}
?>