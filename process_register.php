<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    // Validasi input kosong
    if (empty($nama) || empty($email) || empty($password) || empty($confirm)) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Semua field harus diisi!'];
        header("Location: register.php");
        exit;
    }

    // Validasi konfirmasi password
    if ($password !== $confirm) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Konfirmasi password tidak cocok!'];
        header("Location: register.php");
        exit;
    }

    // Cek apakah email sudah ada di tabel admin_owner
    $check = mysqli_prepare($conn, "SELECT id_admin FROM admin_owner WHERE email = ?");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Email sudah digunakan!'];
        header("Location: register.php");
        exit;
    }

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "INSERT INTO admin_owner (nama, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Pendaftaran admin berhasil! Silakan login.'];
        header("Location: login_owner.php");
        exit;
    } else {
        $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Gagal mendaftar: ' . mysqli_error($conn)];
        header("Location: register.php");
        exit;
    }
}
?>