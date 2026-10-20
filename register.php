<?php
session_start();
include 'connection.php';


if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$error = "";
$success = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $full_name = trim($_POST['full_name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $confirm = trim($_POST['confirm']);

  if ($password !== $confirm) {
    $error = "⚠️ Passwords do not match.";
  } else {

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $error = "⚠️ Email is already registered.";
    } else {

      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, created_at) VALUES (?, ?, ?, NOW())");
      $stmt->bind_param("sss", $full_name, $email, $hashed_password);

      if ($stmt->execute()) {
        $success = "✅ Account created successfully! You can now log in.";
      } else {
        $error = "❌ Something went wrong. Please try again later.";
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
  <title>Register | Drip Collectives</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .register-container {
      max-width: 420px;
      margin: 100px auto;
      padding: 40px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .btn-dark {
      border-radius: 25px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    .alert {
      font-size: 14px;
    }
    .register-container h3 {
      font-weight: 700;
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="register-container">
    <h3 class="text-center mb-4">📝 Create Account</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php elseif (!empty($success)): ?>
      <div class="alert alert-success text-center"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="full_name" class="form-control" placeholder="Enter your full name" required>
      </div>

      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
      </div>

      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm" class="form-control" placeholder="Confirm password" required>
      </div>

      <button type="submit" class="btn btn-dark btn-block mt-3">Register</button>

      <p class="text-center mt-3">
        Already have an account?
        <a href="login.php" class="font-weight-bold text-dark">Login here</a>
      </p>
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
</body>
</html>
