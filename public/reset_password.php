<?php
include('../config/db.php');

$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $token = $_POST['token'];

  $update = mysqli_query($conn, "UPDATE users SET password='$new_pass', reset_token=NULL WHERE reset_token='$token'");

  if ($update) {
    header("Location: index.php?success=Password berhasil diubah!");
    exit();
  } else {
    $msg = "<p class='error'>Gagal mengubah password. Token tidak valid.</p>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="form-container">
    <h2>Reset Password</h2>
    <?php if (!isset($msg)) $msg = ""; echo $msg; ?>
    <form method="POST">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <div class="input-group">
        <label>Password Baru</label>
        <input type="password" name="password" placeholder="Masukkan password baru" required>
      </div>
      <button type="submit" class="btn">Simpan Password</button>
    </form>
    <div class="links">
      <p><a href="index.php">Kembali ke Login</a></p>
    </div>
  </div>
</body>
</html>
