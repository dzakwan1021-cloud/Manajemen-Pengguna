<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // pastikan path ini benar
include('../config/db.php');

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

    // Simpan user ke database dengan status inactive
    $query = "INSERT INTO users (name,email,password,activation_token,status)
              VALUES ('$name','$email','$password','$token','inactive')";
    if (mysqli_query($conn, $query)) {

      // Buat link aktivasi
      $activation_link = "http://localhost/user_management/public/activate.php?token=$token";

      // === Kirim Email Aktivasi ===
      $mail = new PHPMailer(true);
      try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'EMAIL_KAMU@gmail.com'; // Ganti dengan email pengirim
        $mail->Password = 'APP_PASSWORD_GMAIL_KAMU'; // Ganti dengan app password Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('EMAIL_KAMU@gmail.com', 'Admin Gudang');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Aktivasi Akun Anda — Admin Gudang';
        $mail->Body = "
          <div style='font-family: Arial, sans-serif; background:#f9f9f9; padding:20px; border-radius:8px;'>
            <h2 style='color:#4a90e2;'>Halo, $name 👋</h2>
            <p>Terima kasih sudah mendaftar. Klik tombol di bawah ini untuk mengaktifkan akun Anda:</p>
            <p style='margin:20px 0;'>
              <a href='$activation_link' style='background:#4a90e2; color:#fff; padding:12px 20px; border-radius:6px; text-decoration:none;'>Aktifkan Akun</a>
            </p>
            <p>Atau buka link ini secara manual jika tombol tidak berfungsi:<br>
              <a href='$activation_link'>$activation_link</a>
            </p>
            <hr>
            <p style='font-size:13px; color:#777;'>Email ini dikirim otomatis oleh sistem Admin Gudang.</p>
          </div>
        ";

        $mail->send();
        $msg = "<p class='success'>Registrasi berhasil! Silakan cek email <strong>$email</strong> untuk aktivasi akun Anda.</p>";

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
        <input type="text" name="name" id="name" placeholder="Masukkan nama lengkap" required>
      </div>

      <div class="input-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Masukkan email" required>
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Masukkan password" required>
      </div>

      <button type="submit" class="btn">Daftar</button>
    </form>

    <div class="links">
      <p>Sudah punya akun? <a href="index.php">Login</a></p>
    </div>
  </div>
</body>
</html>
