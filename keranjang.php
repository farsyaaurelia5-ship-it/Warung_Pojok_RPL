<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi.php benar

// Hapus salah satu item keranjang berdasarkan indeks
if (isset($_GET['hapus'])) {
    $hapus_index = $_GET['hapus'];
    if (isset($_SESSION['keranjang'][$hapus_index])) {
        unset($_SESSION['keranjang'][$hapus_index]);
        $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // Reset index
    }
    header("Location: keranjang.php");
    exit;
}

// Hapus semua isi keranjang
if (isset($_GET['hapus_semua'])) {
    unset($_SESSION['keranjang']);
    header("Location: keranjang.php");
    exit;
}

// Ambil isi keranjang
$keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang - Warung Pojok</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="keranjang.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <div class="navbar-nav ml-auto flex-row">
            <a href="daftar_menu.php" class="nav-item nav-link mr-3">Menu</a>
            <a href="keranjang.php" class="nav-item nav-link mr-3">Keranjang</a>
        </div>
    </div>
</nav>


<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h2>
        <div class="action-buttons">
            <a href="daftar_menu.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Menu</a>
            <?php if (!empty($keranjang)): ?>
                <a href="keranjang.php?hapus_semua=1" class="btn btn-warning" onclick="return confirm('Hapus semua isi keranjang?')">
                    <i class="fas fa-trash-alt"></i> Hapus Semua
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Gambar</th>
                            <th>Menu</th>
                            <th style="width: 120px;">Harga</th>
                            <th style="width: 100px;">Jumlah</th>
                            <th style="width: 140px;">Subtotal</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($keranjang)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-cart">
                                        <i class="fas fa-shopping-cart"></i>
                                        <h5>Keranjang masih kosong</h5>
                                        <p class="text-muted">Silakan tambahkan menu dari daftar menu</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($keranjang as $key => $item): ?>
                                <?php
                                    $nama = isset($item['nama_menu']) ? $item['nama_menu'] : '(Tidak ada nama)';
                                    $gambar = isset($item['gambar']) ? $item['gambar'] : null;
                                    $harga = isset($item['harga']) ? $item['harga'] : 0;
                                    $jumlah = isset($item['jumlah']) ? (int)$item['jumlah'] : 1;
                                    $subtotal = $harga * $jumlah;
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($gambar): ?>
                                            <img src="uploads/<?= htmlspecialchars($gambar) ?>" alt="<?= htmlspecialchars($nama) ?>" class="menu-img" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                        <?php else: ?>
                                            <div class="menu-img bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($nama) ?></td>
                                    <td>Rp <?= number_format($harga, 0, ',', '.') ?></td>
                                    <td><?= $jumlah ?></td>
                                    <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                    <td>
                                        <a href="keranjang.php?hapus=<?= $key ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus menu ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($keranjang)): ?>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="4" class="text-right"><strong>Total</strong></td>
                                <td colspan="2"><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                            </tr>
                        </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>


    <?php if (!empty($keranjang)): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cash-register"></i> Checkout</h5>
            </div>
            <div class="card-body">
                <form action="proses_checkout.php" method="POST">
                    <input type="hidden" name="total_harga" value="<?= $total ?>">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nama_pelanggan"><i class="fas fa-user"></i> Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan" required placeholder="Masukkan nama Anda">
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="nomor_meja"><i class="fas fa-chair"></i> Pilih Nomor Meja</label>
                            <select name="nomor_meja" class="form-control" id="nomor_meja" required>
                                <option value="">-- Pilih Meja Kosong --</option>
                                <?php
                                // Query mengambil hanya meja yang statusnya 'Kosong'
                                $query_meja = "SELECT * FROM meja WHERE status_meja = 'Kosong' ORDER BY nomor_meja ASC";
                                $result_meja = $conn->query($query_meja);

                                if ($result_meja->num_rows > 0) {
                                    while($row_meja = $result_meja->fetch_assoc()) {
                                        // Value pakai ID_MEJA, Tampilan pakai NOMOR_MEJA
                                        echo '<option value="' . $row_meja['id_meja'] . '">Meja ' . $row_meja['nomor_meja'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="" disabled>Maaf, semua meja penuh</option>';
                                }
                                ?>
                            </select>
                            <small class="text-muted">Hanya menampilkan meja yang tersedia.</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pembayaran"><i class="fas fa-money-bill-wave"></i> Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-control" id="pembayaran" onchange="toggleQRIS()" required>
                            <option value="tunai">Tunai</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div class="form-group text-center mt-3" id="qrisContainer" style="display: none;">
                        <div class="border p-3 d-inline-block rounded bg-light">
                            <h6 class="mb-2">Scan QRIS di bawah ini:</h6>
                            <img src="uploads/qris.png" alt="QRIS Code" width="200">
                            <p class="mt-2 text-muted small">Silakan upload bukti bayar (opsional jika sistem upload belum ada)</p>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-success btn-lg px-4">
                            <i class="fas fa-check-circle"></i> Proses Pesanan
                        </button>
                    </div>
                </form>

                <script>
                    function toggleQRIS() {
                        var metode = document.getElementById("pembayaran").value;
                        var qrisContainer = document.getElementById("qrisContainer");
                        if (metode === "qris") {
                            qrisContainer.style.display = "block";
                        } else {
                            qrisContainer.style.display = "none";
                        }
                    }
                </script>
            </div>
        </div>
    <?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>