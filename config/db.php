<?php
$host = "localhost";
$user = "root"; // ubah kalau pakai user lain
$pass = "";
$db   = "user_management";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
