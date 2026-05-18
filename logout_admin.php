<?php
session_start();

// Hapus semua variabel sesi admin
unset($_SESSION['role']);
unset($_SESSION['nama']);
unset($_SESSION['admin_id']);

// Hancurkan sesi
session_destroy();

// Arahkan ke halaman login
header("Location: login_owner.php");
exit;
?>
