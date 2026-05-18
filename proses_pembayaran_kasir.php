<?php
session_start();
require 'koneksi.php';

// Cek apakah kasir sudah login
if (!isset($_SESSION['kasir_id'])) {
    header("Location: login_kasir.php");
    exit();
}

// Cek apakah ada parameter id_pesanan
if (!isset($_GET['id_pesanan'])) {
    header("Location: kasir_dashboard.php");
    exit();
}

$id_pesanan = (int)$_GET['id_pesanan'];

// Proses jika form pembayaran disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selesaikan_pembayaran'])) {
    $id_kasir = $_SESSION['kasir_id'];

    // 1. Update status pesanan menjadi Selesai dan catat ID kasir
    $stmt_update = $conn->prepare("UPDATE pesanan SET status_pesanan = 'Selesai', id_kasir = ? WHERE id_pesanan = ?");
    $stmt_update->bind_param("ii", $id_kasir, $id_pesanan);
    $stmt_update->execute();

    // 2. Ambil id_meja dari pesanan untuk mengosongkan meja
    $q_meja = $conn->query("SELECT id_meja FROM pesanan WHERE id_pesanan = $id_pesanan");
    $pesanan_info = $q_meja->fetch_assoc();
    
    if ($pesanan_info && $pesanan_info['id_meja']) {
        // 3. Kosongkan meja (Set status meja jadi Kosong dan hapus id_pesanan_aktif)
        $conn->query("UPDATE meja SET status_meja = 'Kosong', id_pesanan_aktif = NULL WHERE id_meja = " . $pesanan_info['id_meja']);
    }

    // 4. Redirect ke nota untuk dicetak
    header("Location: nota.php?order_id=$id_pesanan");
    exit;
}

// Ambil data pesanan untuk ditampilkan
$stmt = $conn->prepare("
    SELECT p.*, pl.nama AS nama_pelanggan 
    FROM pesanan p 
    LEFT JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan 
    WHERE p.id_pesanan = ? AND p.status_pesanan = 'Pending'");
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "<script>alert('Pesanan ini sudah diproses atau tidak ditemukan.'); window.location='kasir_dashboard.php';</script>";
    exit;
}

// Ambil item-item pesanan
$stmt_items = $conn->prepare("
    SELECT dp.*, m.nama_menu 
    FROM detail_pesanan dp 
    JOIN menu m ON dp.id_menu = m.id_menu 
    WHERE dp.id_pesanan = ?");
$stmt_items->bind_param("i", $id_pesanan);
$stmt_items->execute();
$items_result = $stmt_items->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pembayaran - Warung Pojok</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="pembayaran.css">
</head>
<body class="bg-light">
<div class="container">
    <div class="card payment-card">
        <div class="payment-header text-center">
            <h2><i class="fas fa-file-invoice-dollar"></i> Konfirmasi Pembayaran</h2>
            <p>Meja: <?= htmlspecialchars($order['nomor_meja']) ?></p>
        </div>
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-1"><strong>ID Pesanan:</strong> #<?= $order['id_pesanan'] ?></p>
                    <p class="mb-1"><strong>Pelanggan:</strong> <?= htmlspecialchars($order['nama_pelanggan']) ?></p>
                </div>
                <div class="col-md-6 text-md-right">
                    <p class="mb-1"><strong>Tanggal:</strong> <?= date("d M Y, H:i", strtotime($order['tanggal_pesanan'])) ?></p>
                </div>
            </div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Menu</th>
                        <th scope="col" class="text-center">Jumlah</th>
                        <th scope="col" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                        <td class="text-center"><?= $item['jumlah_item'] ?></td>
                        <td class="text-right">Rp <?= number_format($item['subtotal_item'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr class="total-display">
                        <th colspan="2" class="text-right h5 mb-0">TOTAL BAYAR</th>
                        <th class="text-right h5 mb-0">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>

            <form method="POST" class="text-center mt-4 pt-3 border-top">
                <a href="kasir_dashboard.php" class="btn btn-secondary btn-lg mr-3"><i class="fas fa-times"></i> Batal</a>
                <button type="submit" name="selesaikan_pembayaran" class="btn btn-success btn-lg"><i class="fas fa-check-circle"></i> Selesaikan & Cetak Nota</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>