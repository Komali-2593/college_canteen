<?php
include 'connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $name, $hash);
        $stmt->fetch();

        if ($id && password_verify($password, $hash)) {
            $_SESSION['user_id']   = $id;
            $_SESSION['user_name'] = $name;
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | CanteenU</title>
  <link rel="stylesheet" href="menu.css">
</head>
<body>
  <nav class="navbar">
    <a href="index.php" class="logo">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"></path>
        <path d="M7 2v20"></path>
        <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"></path>
      </svg>
      Canteen<span>U</span>
    </a>
    <ul class="nav-links">
      <li><a href="register.php">Register</a></li>
    </ul>
  </nav>

  <div class="auth-wrapper">
    <div class="auth-card animate-fade-up">
      <div class="auth-header">
        <h2>Welcome Back</h2>
        <p>Login to your CanteenU account</p>
      </div>

      <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="login.php" method="POST" class="glass-form auth-form">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="john@example.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Your password" required>
        </div>
        <button type="submit" class="btn">Login</button>
        <p class="auth-switch">Don't have an account? <a href="register.php">Register</a></p>
      </form>
    </div>
  </div>
</body>
</html>
