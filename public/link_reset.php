<?php
session_start();
if (!isset($_SESSION['reset_token'])) {
  header("Location: forgot_password.php");
  exit();
}

$token = $_SESSION['reset_token'];
$email = $_SESSION['reset_email'];
$reset_link = "http://localhost/user_management/public/reset_password.php?token=$token";

// Hapus session agar halaman tidak bisa diakses ulang tanpa request baru
unset($_SESSION['reset_token']);
unset($_SESSION['reset_email']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tautan Reset Password</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .form-container {
      max-width: 500px;
      margin: 50px auto;
      background: #fff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
      font-family: Arial, sans-serif;
    }
    a.link-reset {
      color: #007bff;
      font-weight: bold;
      text-decoration: none;
      word-break: break-all;
    }
    a.link-reset:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Tautan Reset Password 🔐</h2>
    <p>Tautan reset untuk <strong><?= htmlspecialchars($email) ?></strong> telah dibuat.</p>
    <p>Klik tautan di bawah ini untuk mengatur ulang password Anda:</p>
    <p><a href="<?= htmlspecialchars($reset_link) ?>" class="link-reset"><?= htmlspecialchars($reset_link) ?></a></p>
    <hr>
    <p><a href="index.php">Kembali ke Halaman Login</a></p>
  </div>
</body>
</html>
