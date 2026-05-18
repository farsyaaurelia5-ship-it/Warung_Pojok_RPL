<?php
session_start();
require 'koneksi.php';

// [PERBAIKAN 1] Tangkap 'id_pesanan', bukan 'order_id' (sesuai redirect dari checkout)
if (!isset($_GET['id_pesanan'])) {
    echo "ID Pesanan tidak ditemukan di URL!";
    exit;
}

$id_pesanan = (int)$_GET['id_pesanan'];

// [PERBAIKAN 2] Tambahkan JOIN ke tabel 'meja' untuk mengambil nomor_meja yang benar
$stmt = $conn->prepare("
    SELECT p.*, pl.nama AS nama_pelanggan, m.nomor_meja 
    FROM pesanan p 
    LEFT JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan 
    LEFT JOIN meja m ON p.id_meja = m.id_meja
    WHERE p.id_pesanan = ?");

$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "Data pesanan tidak ditemukan di database.";
    exit;
}

// Ambil item menu
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
<html>
<head>
    <title>Nota Pesanan - Warung Pojok</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="nota.css">
    <style>
        .nota { max-width: 600px; margin: 30px auto; background: #fff; padding: 30px; border-radius: 10px; }
        body { background-color: #f4f6f9; }
    </style>
</head>
<body>
<div class="container">

    <div class="nota shadow-sm">
        <div class="text-center mb-4">
            <h2 class="font-weight-bold">WARUNG POJOK</h2>
            <p class="text-muted">Nota Pembelian Resmi</p>
            <hr>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <p class="mb-1"><strong>No. Pesanan:</strong> #<?= $order['id_pesanan'] ?></p>
                <p class="mb-1"><strong>Pelanggan:</strong> <?= htmlspecialchars($order['nama_pelanggan']) ?></p>
            </div>
            <div class="col-6 text-right">
                <p class="mb-1"><strong>Meja:</strong> <span class="badge badge-primary" style="font-size: 1rem;">No. <?= htmlspecialchars($order['nomor_meja']) ?></span></p>
                <p class="mb-1 text-muted small"><?= date("d M Y, H:i", strtotime($order['tanggal_pesanan'])) ?></p>
            </div>
        </div>

        <table class="table table-striped table-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Menu</th>
                    <th class="text-center">Jml</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $items_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                    <td class="text-center"><?= $item['jumlah_item'] ?></td>
                    <td class="text-right"><?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($item['subtotal_item'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="3" class="text-right">TOTAL BAYAR</td>
                    <td class="text-right" style="font-size: 1.2rem;">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-4 p-3 bg-light rounded text-center border">
            <p class="mb-2 font-weight-bold">Status: <span class="text-warning">BELUM DIBAYAR</span></p>
            <small class="text-muted">Silakan menuju kasir dan tunjukkan Nota ini atau Nomor Meja Anda.</small>
            
            <?php if (isset($order['metode_pembayaran']) && strtolower($order['metode_pembayaran']) === 'qris'): ?>
                <div class="mt-3">
                    <p class="mb-1">Scan QRIS:</p>
                    <img src="uploads/qris.png" alt="QRIS" width="150">
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-4 text-center">
            <a href="index.php" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-home"></i> Kembali ke Menu
            </a>
            <a href="logout_pembeli.php" class="btn btn-success btn-sm ml-2" onclick="return confirm('Selesai memesan? Sesi Anda akan direset.')">
                <i class="fas fa-check"></i> Selesai & Pesan Baru
            </a>
        </div>
    </div>
</div>
</body>
</html>