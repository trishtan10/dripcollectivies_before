<?php
session_start();
include 'connection.php';

if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['full_name'];
      $_SESSION['user_email'] = $user['email'];

      header("Location: " . $redirect);
      exit();
    } else {
      $error = "❌ Invalid password. Please try again.";
    }
  } else {
    $error = "⚠️ No account found with that email.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Drip Collectives</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .login-container {
      max-width: 420px;
      margin: 100px auto;
      padding: 40px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      min-height: 55vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .btn-dark {
      border-radius: 25px;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    .alert {
      font-size: 14px;
    }
    .login-container h3 {
      font-weight: 700;
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>

  <div class="login-container">
    <h3 class="text-center mb-4">User Login</h3>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php?redirect=<?= htmlspecialchars($redirect) ?>">
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
      </div>

      <button type="submit" class="btn btn-dark btn-block mt-3">Login</button>

      <p class="text-center mt-3">
        Don’t have an account?
        <a href="register.php" class="font-weight-bold text-dark">Register here</a>
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

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
</body>
</html>
