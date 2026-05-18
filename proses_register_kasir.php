<?php
session_start();
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Validasi input
    if (empty($nama) || empty($password) || empty($confirm)) {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'msg' => 'Semua field harus diisi!'
        ];
        header("Location: register_kasir.php");
        exit;
    }

    if ($password !== $confirm) {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'msg' => 'Password dan konfirmasi password tidak cocok!'
        ];
        header("Location: register_kasir.php");
        exit;
    }

    // Cek apakah nama kasir sudah ada
    $stmt = $conn->prepare("SELECT id_kasir FROM kasir WHERE nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'msg' => 'Nama kasir sudah digunakan!'
        ];
        header("Location: register_kasir.php");
        exit;
    }

    $stmt->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert ke database
    $stmt = $conn->prepare("INSERT INTO kasir (nama, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'msg' => 'Pendaftaran kasir berhasil! Silakan login.'
        ];
        header("Location: login_kasir.php");
        exit;
    } else {
        $_SESSION['flash'] = [
            'type' => 'danger',
            'msg' => 'Terjadi kesalahan saat mendaftar.'
        ];
        header("Location: register_kasir.php");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: register_kasir.php");
    exit;
}
?>