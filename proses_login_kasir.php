<?php
session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM kasir WHERE nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();
    $kasir = $result->fetch_assoc();

    if ($kasir && password_verify($password, $kasir['password'])) {
        // Login berhasil
        $_SESSION['kasir_id'] = $kasir['id_kasir'];
        $_SESSION['kasir_nama'] = $kasir['nama'];
        header("Location: kasir_dashboard.php");
        exit;
    } else {
        // Login gagal
        $_SESSION['error'] = "Nama atau password kasir salah!";
        header("Location: login_kasir.php");
        exit;
    }
}
?>