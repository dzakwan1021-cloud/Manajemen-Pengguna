<?php
include('../includes/auth_check.php');
include('../config/db.php');

// Ambil ID user yang sedang login
$user_id = $_SESSION['user_id'];

// Tambah produk
if (isset($_POST['add'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $price = floatval($_POST['price']);
  $stock = intval($_POST['stock']);

  mysqli_query($conn, "INSERT INTO products (name, description, price, stock, created_by)
                       VALUES ('$name', '$description', '$price', '$stock', '$user_id')");
  header('Location: products.php');
  exit;
}

// Hapus produk (hanya milik user yang login)
if (isset($_GET['delete'])) {
  $id = intval($_GET['delete']);
  mysqli_query($conn, "DELETE FROM products WHERE id='$id' AND created_by='$user_id'");
  header('Location: products.php');
  exit;
}

// Ambil hanya produk milik user ini
$result = mysqli_query($conn, "SELECT * FROM products WHERE created_by='$user_id' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Produk — Admin Gudang</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .container {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      width: 95%;
      max-width: 900px;
      margin: 30px auto;
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    table, th, td {
      border: 1px solid #ccc;
    }

    th, td {
      padding: 10px;
      text-align: center;
    }

    th {
      background: #f4f4f4;
    }

    form.add-form {
      margin-bottom: 20px;
    }

    form.add-form input, textarea {
      width: 100%;
      padding: 8px;
      margin: 5px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      padding: 8px 15px;
      background: #4a90e2;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background: #357ABD;
    }

    .delete {
      background: #e74c3c;
    }

    .delete:hover {
      background: #c0392b;
    }

    .back {
      display: inline-block;
      margin-bottom: 10px;
      text-decoration: none;
      color: white;
      background: #555;
      padding: 8px 12px;
      border-radius: 5px;
    }

    .back:hover {
      background: #333;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="dashboard.php" class="back">← Kembali ke Dashboard</a>
    <h2>Kelola Produk</h2>

    <!-- Form Tambah Produk -->
    <form method="POST" class="add-form">
      <input type="text" name="name" placeholder="Nama Produk" required>
      <textarea name="description" placeholder="Deskripsi Produk"></textarea>
      <input type="number" step="0.01" name="price" placeholder="Harga" required>
      <input type="number" name="stock" placeholder="Stok" required>
      <button type="submit" name="add">Tambah Produk</button>
    </form>

    <!-- Daftar Produk -->
    <table>
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Deskripsi</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
      </tr>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td>Rp <?= number_format($row['price'], 2, ',', '.') ?></td>
        <td><?= $row['stock'] ?></td>
        <td>
          <a href="edit_product.php?id=<?= $row['id'] ?>" class="edit">Edit</a>
          <a href="?delete=<?= $row['id'] ?>" class="delete" onclick="return confirm('Hapus produk ini?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>
</html>
