<?php
session_start();
include 'koneksi.php';

// Cek jika keranjang kosong
if (!isset($_SESSION['keranjang']) || count($_SESSION['keranjang']) === 0) {
    echo "<script>alert('Keranjang masih kosong!'); window.location='keranjang.php';</script>";
    exit;
}

// Ambil data dari form
$nama_pelanggan = $_POST['nama_pelanggan'];
// PENTING: Karena di keranjang.php valuenya adalah ID, maka ini adalah id_meja
$id_meja = $_POST['nomor_meja']; 
$metode_pembayaran = isset($_POST['metode_pembayaran']) ? $_POST['metode_pembayaran'] : 'Tunai';

// 1. Hitung total harga ulang (biar aman dari manipulasi)
$total_harga = 0;
foreach ($_SESSION['keranjang'] as $item) {
    $harga = isset($item['harga']) ? $item['harga'] : 0;
    $jumlah = isset($item['jumlah']) ? (int)$item['jumlah'] : 1;
    $total_harga += $harga * $jumlah;
}

// 2. Simpan Data Pelanggan
$stmt_pelanggan = $conn->prepare("INSERT INTO pelanggan (nama) VALUES (?)");
$stmt_pelanggan->bind_param("s", $nama_pelanggan);
$stmt_pelanggan->execute();
$id_pelanggan = $stmt_pelanggan->insert_id;

// 3. Simpan ke Tabel PESANAN
// Status default 'Pending' (artinya Belum Bayar -> Tombol Kuning di Kasir)
$status_pesanan = 'Pending';
// Kita asumsikan kolom status_pembayaran belum ada, jadi pakai status_pesanan saja
$stmt_pesanan = $conn->prepare("INSERT INTO pesanan (tanggal_pesanan, total_harga, status_pesanan, id_pelanggan, id_meja, id_kasir) VALUES (NOW(), ?, ?, ?, ?, NULL)");

// Note: id_kasir NULL dulu karena belum dilayani kasir
$stmt_pesanan->bind_param("dsii", $total_harga, $status_pesanan, $id_pelanggan, $id_meja);

if ($stmt_pesanan->execute()) {
    $id_pesanan_baru = $stmt_pesanan->insert_id;

    // 4. Simpan DETAIL PESANAN
    $stmt_detail = $conn->prepare("INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah_item, harga_satuan, subtotal_item) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($_SESSION['keranjang'] as $item) {
        $id_menu = (int)$item['menu_id'];
        $harga_satuan = (float)$item['harga'];
        $jumlah = (int)$item['jumlah'];
        $subtotal = $harga_satuan * $jumlah;
        
        $stmt_detail->bind_param("iiidd", $id_pesanan_baru, $id_menu, $jumlah, $harga_satuan, $subtotal);
        $stmt_detail->execute();
    }

    // ==========================================================
    // 5. UPDATE STATUS MEJA (BAGIAN PALING PENTING!)
    // ==========================================================
    // Ini yang bikin meja jadi "Terisi" dan nyambung ke pesanan ini
    $stmt_meja = $conn->prepare("UPDATE meja SET status_meja = 'Terisi', id_pesanan_aktif = ? WHERE id_meja = ?");
    $stmt_meja->bind_param("ii", $id_pesanan_baru, $id_meja);
    $stmt_meja->execute();

    // 6. Bersihkan Keranjang & Redirect
    unset($_SESSION['keranjang']);
    
    // Redirect ke Nota atau Sukses
    echo "<script>
            alert('Pesanan Berhasil! Silakan lakukan pembayaran di kasir.'); 
            window.location='nota.php?id_pesanan=$id_pesanan_baru';
          </script>";
} else {
    echo "Error: " . $conn->error;
}
?>