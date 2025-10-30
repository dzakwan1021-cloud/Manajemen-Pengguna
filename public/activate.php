<?php
include('../config/db.php');

$msg = "";

if (isset($_GET['token'])) {
  $token = $_GET['token'];

  // Cek apakah token valid
  $check = mysqli_query($conn, "SELECT * FROM users WHERE activation_token='$token' AND status='inactive' LIMIT 1");

  if (mysqli_num_rows($check) > 0) {
    // Update status user jadi aktif
    $update = mysqli_query($conn, "UPDATE users SET status='active', activation_token=NULL WHERE activation_token='$token'");

    if ($update) {
      $msg = "<p class='success'>Akun Anda berhasil diaktifkan! 🎉<br>
              Silakan <a href='index.php'>login sekarang</a>.</p>";
    } else {
      $msg = "<p class='error'>Terjadi kesalahan saat mengaktifkan akun.</p>";
    }

  } else {
    $msg = "<p class='error'>Token tidak valid atau akun sudah diaktifkan sebelumnya.</p>";
  }

} else {
  $msg = "<p class='error'>Token tidak ditemukan.</p>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aktivasi Akun — Admin Gudang</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="form-container">
    <h2>Aktivasi Akun</h2>
    <?= $msg ?>
  </div>
</body>
</html>
