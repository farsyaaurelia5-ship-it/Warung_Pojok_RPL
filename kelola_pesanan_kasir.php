<?php
session_start();
include 'koneksi.php';

// [PERBAIKAN 1] Cek apakah KASIR sudah login (Bukan Admin)
if (!isset($_SESSION['kasir_id'])) {
    header("Location: login_kasir.php");
    exit();
}

// [PERBAIKAN 2] Query diperbaiki:
// 1. Join ke tabel 'meja' untuk ambil nomor_meja
// 2. Hapus 'WHERE status=Pending' agar Kasir bisa lihat SEMUA riwayat pesanan
$sql = "SELECT p.*, pl.nama AS nama_pelanggan, m.nomor_meja 
        FROM pesanan p
        LEFT JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
        LEFT JOIN meja m ON p.id_meja = m.id_meja
        ORDER BY p.tanggal_pesanan DESC";
$orders = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Warung Pojok</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Menggunakan CSS yang sama -->
    <link rel="stylesheet" href="kelola_pesanan_owner.css">
    <style>
        /* Sedikit perbaikan style agar lebih rapi di HP */
        .order-header { display: flex; justify-content: space-between; align-items: flex-start; }
        .order-meta { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        @media (min-width: 768px) {
            .order-meta { grid-template-columns: repeat(4, 1fr); }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 class="page-title">
                <i class="fas fa-clipboard-list"></i> Kelola Pesanan
            </h1>
            <!-- [PERBAIKAN 3] Link kembali ke Dashboard KASIR -->
            <a href="kasir_dashboard.php" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <?php if (mysqli_num_rows($orders) > 0): ?>
            <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                <?php
                // [PERBAIKAN 4] Mencegah Error "Undefined Array Key" / "Null"
                // Gunakan operator '??' untuk memberikan nilai default jika data kosong
                $nama_pelanggan = $order['nama_pelanggan'] ?? 'Pelanggan Umum';
                $nomor_meja = $order['nomor_meja'] ?? '-';
                $metode_pembayaran = $order['metode_pembayaran'] ?? 'Tunai'; 
                $status_pesanan = $order['status_pesanan'] ?? 'Pending';
                
                $order_id = $order['id_pesanan'];
                $status_class = 'status-' . strtolower($status_pesanan);
                ?>

                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <span class="order-id">#<?= $order_id ?></span>
                            <span> • </span>
                            <span class="customer-name"><?= htmlspecialchars($nama_pelanggan) ?></span>
                        </div>
                        <span class="order-status <?= $status_class ?>"><?= htmlspecialchars($status_pesanan) ?></span>
                    </div>

                    <div class="order-body">
                        <div class="order-meta">
                            <div class="meta-item">
                                <span class="meta-label">Nomor Meja</span>
                                <span class="meta-value"><?= htmlspecialchars($nomor_meja) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Total Pesanan</span>
                                <span class="meta-value price">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Metode Pembayaran</span>
                                <span class="meta-value"><?= ucfirst($metode_pembayaran) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Tanggal Pesanan</span>
                                <span class="meta-value"><?= date('d M H:i', strtotime($order['tanggal_pesanan'])) ?></span>
                            </div>
                        </div>

                        <h5 style="margin-bottom: 15px; font-size: 16px; margin-top: 20px;">Rincian Pesanan:</h5>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $items = mysqli_query($conn, "SELECT dp.*, m.nama_menu FROM detail_pesanan dp JOIN menu m ON dp.id_menu = m.id_menu WHERE dp.id_pesanan = $order_id");
                                while ($item = mysqli_fetch_assoc($items)):
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                                        <td><?= $item['jumlah_item'] ?></td>
                                        <td>Rp <?= number_format($item['subtotal_item'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                        <!-- [PERBAIKAN 5] Form update status disesuaikan dengan update_status.php -->
                        <form method="post" action="update_status.php" class="status-form" style="margin-top: 20px;">
                            <!-- Tambahkan action update_manual agar update_status.php mengenali request ini -->
                            <input type="hidden" name="action" value="update_manual">
                            <input type="hidden" name="order_id" value="<?= $order_id ?>">
                            
                            <select name="status" class="form-select">
                                <?php foreach (['Pending', 'Selesai', 'Dibatalkan'] as $val): ?>
                                    <option value="<?= $val ?>" <?= $val == $status_pesanan ? 'selected' : '' ?>>
                                        <?= $val ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard"></i>
                </div>
                <h3 style="margin-bottom: 10px; color: #555;">Belum ada pesanan</h3>
                <p class="empty-text">Data pesanan kosong.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>