<?php
session_start();
include('../config/db.php');

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';

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
    // Generate token aktivasi
    $token = bin2hex(random_bytes(16));

    $query = "INSERT INTO users (name,email,password,activation_token,status)
              VALUES ('$name','$email','$password','$token','inactive')";
    if (mysqli_query($conn, $query)) {

      // Kirim email aktivasi
      $mail = new PHPMailer(true);
      try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bagusdzakwan1021@gmail.com'; // ganti dengan email kamu
        $mail->Password = 'fqmd aqjt euya tppn';    // ganti dengan app password (bukan password Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Penerima
        $mail->setFrom('EMAIL_KAMU@gmail.com', 'Admin Gudang');
        $mail->addAddress($email, $name);

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = 'Aktivasi Akun Anda — Admin Gudang';
        $activation_link = "http://localhost/user_management/public/activate.php?token=$token";
        $mail->Body = "
          <h3>Halo, $name 👋</h3>
          <p>Terima kasih telah mendaftar. Silakan klik tautan di bawah ini untuk mengaktifkan akun Anda:</p>
          <p><a href='$activation_link' style='display:inline-block;padding:10px 15px;background:#4a90e2;color:white;text-decoration:none;border-radius:5px;'>Aktifkan Akun</a></p>
          <p>Jika tombol tidak berfungsi, salin dan buka tautan ini di browser:</p>
          <p>$activation_link</p>
          <br><small>Jangan balas email ini.</small>
        ";

        $mail->send();
        $msg = "<p class='success'>Registrasi berhasil! Silakan cek email Anda untuk aktivasi akun.</p>";
      } catch (Exception $e) {
        $msg = "<p class='error'>Gagal mengirim email aktivasi. Error: {$mail->ErrorInfo}</p>";
      }
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
