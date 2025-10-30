<?php
include('../includes/auth_check.php');
include('../config/db.php');

$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);

// Ubah profil (hanya nama)
if (isset($_POST['update_profile'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  mysqli_query($conn, "UPDATE users SET name='$name' WHERE id='$user_id'");
  $_SESSION['name'] = $name; // ✅ update session biar dashboard ikut berubah
  $success = "Profil berhasil diperbarui.";
}

// Ubah password
if (isset($_POST['update_password'])) {
  $old_password = $_POST['old_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  if (!password_verify($old_password, $user['password'])) {
    $error = "Password lama salah.";
  } elseif ($new_password !== $confirm_password) {
    $error = "Konfirmasi password baru tidak cocok.";
  } else {
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE id='$user_id'");
    $success = "Password berhasil diperbarui.";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil — Admin Gudang</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .container {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      width: 95%;
      max-width: 700px;
      margin: 30px auto;
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    form {
      margin-bottom: 25px;
    }

    label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
    }

    input {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    input[readonly] {
      background: #f5f5f5;
      color: #777;
    }

    button {
      margin-top: 15px;
      padding: 8px 15px;
      background: #4a90e2;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    button:hover {
      background: #357ABD;
    }

    .success {
      color: green;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .error {
      color: red;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .back {
      text-decoration: none;
      color: white;
      background: #555;
      padding: 8px 12px;
      border-radius: 6px;
    }

    .back:hover {
      background: #333;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="dashboard.php" class="back">← Kembali ke Dashboard</a>
    <h2>Profil Pengguna</h2>

    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <!-- Form Ubah Profil -->
    <form method="POST">
      <label>Nama</label>
      <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

      <label>Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>

      <button type="submit" name="update_profile">Perbarui Profil</button>
    </form>

    <!-- Form Ganti Password -->
    <form method="POST">
      <h3>Ubah Password</h3>
      <label>Password Lama</label>
      <input type="password" name="old_password" required>

      <label>Password Baru</label>
      <input type="password" name="new_password" required>

      <label>Konfirmasi Password Baru</label>
      <input type="password" name="confirm_password" required>

      <button type="submit" name="update_password">Ganti Password</button>
    </form>
  </div>
</body>
</html>
