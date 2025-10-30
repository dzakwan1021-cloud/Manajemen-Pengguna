<?php
include('../config/db.php');

$msg = "";

// Cek token
if (!isset($_GET['token'])) {
  $msg = "<p class='error'>Token tidak ditemukan.</p>";
} else {
  $token = $_GET['token'];

  // Cek apakah token valid
  $result = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token'");
  if (mysqli_num_rows($result) === 0) {
    $msg = "<p class='error'>Token tidak valid atau sudah digunakan.</p>";
  } else {
    // Jika form disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $update = mysqli_query($conn, "UPDATE users SET password='$password', reset_token=NULL WHERE reset_token='$token'");

      if ($update) {
        $msg = "<p class='success'>Password berhasil direset!<br><a href='index.php'>Login Sekarang</a></p>";
      } else {
        $msg = "<p class='error'>Terjadi kesalahan saat menyimpan password baru.</p>";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password — Admin Gudang</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="form-container">
    <h2>Reset Password</h2>
    <?= $msg; ?>

    <?php if (isset($token) && mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token'")) > 0): ?>
      <form method="POST">
        <div class="input-group">
          <label>Password Baru</label>
          <input type="password" name="password" placeholder="Masukkan password baru" required>
        </div>
        <button type="submit" class="btn">Reset Password</button>
      </form>
    <?php endif; ?>

    <div class="links">
      <p><a href="index.php">Kembali ke Login</a></p>
    </div>
  </div>
</body>
</html>
