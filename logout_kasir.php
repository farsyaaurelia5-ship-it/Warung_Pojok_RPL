<?php
session_start();

// Hapus semua variabel sesi kasir
unset($_SESSION['kasir_id']);
unset($_SESSION['kasir_nama']);

// Arahkan ke halaman login kasir
header("Location: login_kasir.php");
exit;
?>