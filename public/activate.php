<?php
include('../config/db.php');
session_start();

if (!isset($_GET['token'])) {
  // Jika belum klik link aktivasi, tampilkan link dulu
  if (!isset($_SESSION['activation_token'])) {
    header("Location: register.php");
    exit();
  }

  $token = $_SESSION['activation_token'];
  $email = $_SESSION['registered_email'];
  $activation_link = "http://localhost/user_management/public/activate.php?token=$token";

  // Hapus session agar tidak bisa diakses ulang
  unset($_SESSION['activation_token']);
  unset($_SESSION['registered_email']);
  ?>
  <!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun</title>
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
      a.link-activate {
        color: #007bff;
        font-weight: bold;
        text-decoration: none;
        word-break: break-all;
      }
      a.link-activate:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <div class="form-container">
      <h2>Registrasi Berhasil ✅</h2>
      <p>Terima kasih, <strong><?= htmlspecialchars($email) ?></strong> sudah mendaftar.</p>
      <p>Klik tautan di bawah ini untuk mengaktifkan akun Anda:</p>
      <p><a href="<?= htmlspecialchars($activation_link) ?>" class="link-activate"><?= htmlspecialchars($activation_link) ?></a></p>
      <hr>
      <p><a href="index.php">Kembali ke Halaman Login</a></p>
    </div>
  </body>
  </html>
  <?php
  exit();
}

// Kalau sudah klik link aktivasi
$token = $_GET['token'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE activation_token='$token' AND status='inactive'");

if (mysqli_num_rows($result) > 0) {
  mysqli_query($conn, "UPDATE users SET status='active', activation_token=NULL WHERE activation_token='$token'");
  header("Location: index.php?success=Akun berhasil diaktifkan! Silakan login.");
  exit();
} else {
  header("Location: index.php?error=Token tidak valid atau akun sudah aktif.");
  exit();
}
?>
