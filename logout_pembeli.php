<?php
session_start();

// Hapus keranjang belanja dan data sesi pembeli lainnya jika ada
unset($_SESSION['keranjang']);

// Arahkan kembali ke halaman menu utama
header("Location: daftar_menu.php");
exit;
?>
