<?php
include('../includes/auth_check.php');
include('../config/db.php');

if (!isset($_GET['id'])) {
  header('Location: products.php');
  exit;
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
$product = mysqli_fetch_assoc($result);

if (!$product) {
  header('Location: products.php');
  exit;
}

// Update produk
if (isset($_POST['update'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $price = floatval($_POST['price']);
  $stock = intval($_POST['stock']);

  mysqli_query($conn, "UPDATE products SET 
    name='$name', description='$description', price='$price', stock='$stock' 
    WHERE id='$id'");

  header('Location: products.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Produk</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <div class="container">
    <h2>Edit Produk</h2>
    <form method="POST">
      <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
      <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea>
      <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
      <input type="number" name="stock" value="<?= $product['stock'] ?>" required>
      <button type="submit" name="update">Simpan Perubahan</button>
      <a href="products.php" class="back">Batal</a>
    </form>
  </div>
</body>
</html>
