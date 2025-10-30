<?php
include('../includes/auth_check.php');
include('../config/db.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Admin Gudang</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #4a90e2, #50e3c2);
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .dashboard {
      background: #fff;
      padding: 40px 50px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
      text-align: center;
      width: 90%;
      max-width: 600px;
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 {
      margin-bottom: 10px;
      color: #333;
      font-weight: 600;
    }

    p {
      color: #555;
      margin-bottom: 25px;
      font-size: 15px;
    }

    .menu {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 20px;
    }

    .menu a {
      background: #4a90e2;
      color: white;
      padding: 14px 28px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: bold;
      font-size: 15px;
      transition: background 0.3s;
      box-shadow: 0 4px 10px rgba(74,144,226,0.3);
    }

    .menu a:hover {
      background: #357ABD;
    }

    .menu a.logout {
      background: #e74c3c;
      box-shadow: 0 4px 10px rgba(231,76,60,0.3);
    }

    .menu a.logout:hover {
      background: #c0392b;
    }
  </style>
</head>
<body>
  <div class="dashboard">
    <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['name']); ?> 👋</h2>
    <p>Anda telah login sebagai <strong>Admin Gudang</strong>.</p>

    <div class="menu">
      <a href="products.php">Kelola Produk</a>
      <a href="profile.php">Profil & Password</a>
      <a href="logout.php" class="logout">Logout</a>
    </div>
  </div>
</body>
</html>
