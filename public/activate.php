<?php
include('../config/db.php');
session_start();

$message = "";

// Pastikan token tersedia di URL
if (isset($_GET['token'])) {
  $token = mysqli_real_escape_string($conn, $_GET['token']);

  // Cek token di database
  $result = mysqli_query($conn, "SELECT * FROM users WHERE activation_token='$token' LIMIT 1");
  
  if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    if ($user['status'] === 'active') {
      $message = "<div class='message success'>
                    <h2>Akun Sudah Aktif ✅</h2>
                    <p>Akun Anda sudah diaktivasi sebelumnya. Silakan login sekarang.</p>
                    <a href='index.php' class='btn'>Login Sekarang</a>
                  </div>";
    } else {
      // Aktivasi akun
      $update = mysqli_query($conn, "UPDATE users SET status='active', activation_token=NULL WHERE id='{$user['id']}'");

      if ($update) {
        $message = "<div class='message success'>
                      <h2>Aktivasi Berhasil 🎉</h2>
                      <p>Akun Anda telah aktif. Sekarang Anda bisa login.</p>
                      <a href='index.php' class='btn'>Login Sekarang</a>
                    </div>";
      } else {
        $message = "<div class='message error'>
                      <h2>Aktivasi Gagal ❌</h2>
                      <p>Terjadi kesalahan saat mengaktifkan akun Anda. Silakan coba lagi nanti.</p>
                    </div>";
      }
    }
  } else {
    $message = "<div class='message error'>
                  <h2>Token Tidak Valid ❗</h2>
                  <p>Tautan aktivasi sudah kedaluwarsa atau tidak valid.</p>
                </div>";
  }
} else {
  $message = "<div class='message error'>
                <h2>Token Tidak Ditemukan 🚫</h2>
                <p>Anda tidak memiliki token aktivasi yang valid.</p>
              </div>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aktivasi Akun — Admin Gudang</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #4a90e2, #50e3c2);
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .message {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.2);
      width: 400px;
      text-align: center;
      animation: fadeIn 0.5s ease-in-out;
    }

    h2 {
      color: #333;
      margin-bottom: 15px;
    }

    .message.success { border-left: 5px solid #27ae60; }
    .message.error { border-left: 5px solid #e74c3c; }

    .btn {
      display: inline-block;
      margin-top: 20px;
      background: #4a90e2;
      color: white;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }

    .btn:hover { background: #357ABD; }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <?= $message ?>
</body>
</html>
