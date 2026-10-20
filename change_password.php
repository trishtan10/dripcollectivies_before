<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $current_password = $_POST['current_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if (!$user || !password_verify($current_password, $user['password'])) {
    $error = "❌ Current password is incorrect.";
  } elseif (strlen($new_password) < 6) {
    $error = "⚠️ New password must be at least 6 characters long.";
  } elseif ($new_password !== $confirm_password) {
    $error = "⚠️ New passwords do not match.";
  } else {

    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update->bind_param("si", $hashed, $user_id);

    if ($update->execute()) {
      $success = "✅ Password changed successfully!";
    } else {
      $error = "⚠️ Something went wrong. Please try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password | Drip Collectives</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
    .password-container {
      max-width: 600px;
      margin: 100px auto;
      background: #fff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    h2 { font-weight: 700; margin-bottom: 25px; }
    .btn-dark { border-radius: 8px; }
    .alert { border-radius: 8px; }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="container">
    <div class="password-container">
      <h2 class="text-center mb-4">🔒 Change Password</h2>

      <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
      <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="form-group">
          <label>Current Password</label>
          <input type="password" name="current_password" class="form-control" required>
        </div>

        <div class="form-group">
          <label>New Password</label>
          <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Confirm New Password</label>
          <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <a href="profile.php" class="text-dark font-weight-bold">← Back to Profile</a>
          <button type="submit" class="btn btn-dark px-4">Update Password</button>
        </div>
      </form>
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
</body>
</html>
