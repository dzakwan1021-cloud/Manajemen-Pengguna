<?php
include('../config/db.php');
session_start();

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Cek apakah email sudah digunakan
  $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
  if (mysqli_num_rows($check) > 0) {
    $msg = "<p class='error'>Email sudah digunakan. Gunakan email lain.</p>";
  } else {
    // Generate token aktivasi unik
    $token = bin2hex(random_bytes(16));

    $query = "INSERT INTO users (name,email,password,activation_token,status)
              VALUES ('$name','$email','$password','$token','inactive')";
    if (mysqli_query($conn, $query)) {
      // Simpan token dan email untuk activate.php
      $_SESSION['activation_token'] = $token;
      $_SESSION['registered_email'] = $email;

      // Arahkan ke halaman activate.php
      header("Location: activate.php");
      exit();
    } else {
      $msg = "<p class='error'>Terjadi kesalahan saat registrasi.</p>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi — Admin Gudang</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="form-container">
    <h2>Registrasi Admin Gudang</h2>
    <?= $msg ?>

    <form method="POST">
      <div class="input-group">
        <label for="name">Nama Lengkap</label>
        <input type="text" name="name" placeholder="Masukkan nama lengkap" required>
      </div>

      <div class="input-group">
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Masukkan email" required>
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>
      </div>

      <button type="submit" class="btn">Daftar</button>
    </form>

    <div class="links">
      <p>Sudah punya akun? <a href="index.php">Login</a></p>
    </div>
  </div>
</body>
</html>
