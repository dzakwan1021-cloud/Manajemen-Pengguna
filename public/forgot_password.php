<?php
include('../config/db.php');
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

  if (mysqli_num_rows($check) === 1) {
    // Generate token baru
    $token = bin2hex(random_bytes(16));
    mysqli_query($conn, "UPDATE users SET reset_token='$token' WHERE email='$email'");

    // Buat tautan reset password
    $reset_link = "http://localhost/user_management/public/reset_password.php?token=$token";

    // --- Kirim Email Reset Password ---
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'bagusdzakwan1021@gmail.com'; // Ganti dengan email kamu
      $mail->Password   = 'fqmd aqjt euya tppn';        // Ganti dengan App Password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = 587;

      $mail->setFrom('bagusdzakwan1021@gmail.com', 'Admin Gudang');
      $mail->addAddress($email);

      $mail->isHTML(true);
      $mail->Subject = 'Reset Password - Admin Gudang';
      $mail->Body    = "
        <h3>Permintaan Reset Password</h3>
        <p>Kami menerima permintaan untuk mereset password akun Anda.</p>
        <p>Klik tautan di bawah ini untuk membuat password baru:</p>
        <p><a href='$reset_link' style='background:#4a90e2;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;'>Reset Password</a></p>
        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
      ";

      $mail->send();
      $msg = "<p class='success'>Tautan reset password telah dikirim ke email Anda.</p>";
    } catch (Exception $e) {
      $msg = "<p class='error'>Gagal mengirim email. Error: {$mail->ErrorInfo}</p>";
    }

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
