<?php
include('../config/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    if ($user['status'] !== 'active') {
      $error = "Akun belum diaktivasi. Silakan cek email Anda untuk aktivasi.";
    } elseif (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['name'] = $user['name'];
      header('Location: dashboard.php');
      exit;
    } else {
      $error = "Password salah.";
    }
  } else {
    $error = "Email tidak ditemukan.";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — Admin Gudang</title>
  <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
  <div class="form-container">
    <h2>Login Admin Gudang</h2>

    <?php if(isset($error)): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <?php if(isset($_GET['success'])): ?>
      <p class="success"><?= $_GET['success'] ?></p>
    <?php endif; ?>

    <form method="POST">
      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan email" required />
      </div>

      <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required />
      </div>

      <button type="submit" class="btn">Login</button>
    </form>

    <div class="links">
      <p>Belum punya akun? <a href="register.php">Registrasi</a></p>
      <p><a href="forgot_password.php">Lupa Password?</a></p>
    </div>
  </div>
</body>
</html>
