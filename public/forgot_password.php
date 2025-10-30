<?php
include('../config/db.php');
session_start();

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

  if (mysqli_num_rows($check) === 1) {
    $token = bin2hex(random_bytes(16));
    mysqli_query($conn, "UPDATE users SET reset_token='$token' WHERE email='$email'");

    // Simpan token dan email ke session
    $_SESSION['reset_token'] = $token;
    $_SESSION['reset_email'] = $email;

    // Arahkan ke link_reset.php
    header("Location: link_reset.php");
    exit();
  } else {
    $msg = "<p class='error'>Email tidak ditemukan.</p>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password — Admin Gudang</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="form-container">
    <h2>Lupa Password</h2>
    <?= $msg; ?>
    <form method="POST">
      <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan email Anda" required>
      </div>
      <button type="submit" class="btn">Kirim Tautan Reset</button>
    </form>
    <div class="links">
      <p><a href="index.php">Kembali ke Login</a></p>
    </div>
  </div>
</body>
</html>
