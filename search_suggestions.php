<?php
include 'connection.php';

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['term'])) {
  $term = trim($_GET['term']);
  if ($term === '') {
    echo json_encode([]);
    exit;
  }

  $term = $conn->real_escape_string($term);
  $query = "
    SELECT id, name, price, image1 
    FROM products 
    WHERE name LIKE '%$term%'
    ORDER BY name ASC
    LIMIT 20
  ";

  $result = $conn->query($query);

  $data = [];
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $data[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'image' => $row['image1']
      ];
    }
  }

  echo json_encode($data);
} else {
  echo json_encode([]);
}
?>