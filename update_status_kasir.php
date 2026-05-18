<?php
session_start();
include 'koneksi.php';

// Cek Login
if (!isset($_SESSION['kasir_id'])) {
    header("Location: login_kasir.php");
    exit();
}

// --- CASE 1: UPDATE MANUAL (DARI HALAMAN LIST PESANAN) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_manual') {
    $id_pesanan = $_POST['order_id'];
    $status_baru = $_POST['status'];

    // 1. Update Status Pesanan
    $conn->query("UPDATE pesanan SET status_pesanan = '$status_baru' WHERE id_pesanan = '$id_pesanan'");

    // 2. LOGIKA TAMBAHAN (OPSIONAL):
    // Jika status diubah jadi 'Dibatalkan', kita harus kosongkan meja otomatis biar mejanya Hijau lagi
    if ($status_baru == 'Dibatalkan') {
        // Cari meja yang terkait pesanan ini
        $cek_meja = $conn->query("SELECT id_meja FROM pesanan WHERE id_pesanan = '$id_pesanan'");
        $data_meja = $cek_meja->fetch_assoc();
        if ($data_meja) {
            $id_meja = $data_meja['id_meja'];
            $conn->query("UPDATE meja SET status_meja = 'Kosong', id_pesanan_aktif = NULL WHERE id_meja = '$id_meja'");
        }
    }

    // 3. REDIRECT KEMBALI KE HALAMAN LIST (INI JAWABANNYA)
    // Supaya kasir tetap di halaman tabel daftar pesanan
    header("Location: kelola_pesanan.php"); 
    exit;
}

// --- CASE 2: TOMBOL DASHBOARD (DARI HALAMAN KOTAK-KOTAK) ---
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'bayar' && isset($_GET['id_pesanan'])) {
        // ... kode bayar ...
        $id_pesanan = $_GET['id_pesanan'];
        $conn->query("UPDATE pesanan SET status_pesanan = 'Selesai' WHERE id_pesanan = '$id_pesanan'");
        header("Location: kasir_dashboard.php"); // Kalau ini balik ke dashboard
    } 
    elseif ($action == 'clear' && isset($_GET['id_meja'])) {
        // ... kode clear ...
        $id_meja = $_GET['id_meja'];
        $conn->query("UPDATE meja SET status_meja = 'Kosong', id_pesanan_aktif = NULL WHERE id_meja = '$id_meja'");
        header("Location: kasir_dashboard.php"); // Ini juga balik ke dashboard
    }
} else {
    header("Location: kasir_dashboard.php");
}
?>