<?php
session_start();
include 'connection.php';


if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";


$stmt = $conn->prepare("SELECT full_name, email, password FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $action = $_POST['action'];

  if ($action === "update_info") {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);

    $update = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
    $update->bind_param("ssi", $name, $email, $user_id);
    if ($update->execute()) {
      $_SESSION['user_name'] = $name;
      $success = "Profile updated successfully ✅";
    } else {
      $error = "Something went wrong. Try again.";
    }
  }

  if ($action === "change_password") {
    $current = trim($_POST['current_password']);
    $new = trim($_POST['new_password']);
    $confirm = trim($_POST['confirm_password']);

    if (!password_verify($current, $user['password'])) {
      $error = "Current password is incorrect.";
    } elseif ($new !== $confirm) {
      $error = "New passwords do not match.";
    } else {
      $hashed = password_hash($new, PASSWORD_DEFAULT);
      $passUpdate = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
      $passUpdate->bind_param("si", $hashed, $user_id);
      if ($passUpdate->execute()) {
        $success = "Password updated successfully ✅";
      } else {
        $error = "Failed to update password.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile | Drip Collectives</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    body { background-color: #f8f9fa; }
    .profile-card {
      max-width: 600px;
      margin: 100px auto;
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h3 { font-weight: 700; }
    .btn-dark { border-radius: 25px; }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="profile-card">
    <h3 class="text-center mb-4">My Profile</h3>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success text-center"><?= $success ?></div>
    <?php elseif (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
      <input type="hidden" name="action" value="update_info">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']); ?>" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
      </div>
      <button type="submit" class="btn btn-dark btn-block mt-3">Update Profile</button>
    </form>

    <h5 class="text-center mt-4 mb-3">🔒 Change Password</h5>
    <form method="POST">
      <input type="hidden" name="action" value="change_password">
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
      <button type="submit" class="btn btn-outline-dark btn-block">Change Password</button>
    </form>
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

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
</body>
</html>
