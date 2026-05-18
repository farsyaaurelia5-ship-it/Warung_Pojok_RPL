<?php
session_start();
require 'koneksi.php';

// Cek apakah kasir sudah login
if (!isset($_SESSION['kasir_id'])) {
    header("Location: login_kasir.php");
    exit();
}

// --- LOGIKA PEMBUATAN MEJA OTOMATIS (JIKA KOSONG) ---
$cek_meja = $conn->query("SELECT COUNT(*) as total FROM meja");
$row_cek = $cek_meja->fetch_assoc();
if ($row_cek['total'] == 0) {
    $sql_insert = "INSERT INTO meja (nomor_meja, status_meja) VALUES ";
    for ($i = 1; $i <= 16; $i++) { // Saya buat 16 meja dulu biar rapi
        $sql_insert .= "('$i', 'Kosong'), ";
    }
    $sql_insert = rtrim($sql_insert, ', ');
    $conn->query($sql_insert);
}

// --- AMBIL DATA MEJA DENGAN JOIN KE PESANAN ---
// Kita butuh status_pesanan untuk tahu dia sudah bayar atau belum
$sql = "SELECT m.*, p.status_pesanan 
        FROM meja m 
        LEFT JOIN pesanan p ON m.id_pesanan_aktif = p.id_pesanan 
        ORDER BY m.id_meja ASC";
$result_meja = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir - Warung Pojok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .header { padding: 2rem 0; background-color: #343a40; color: white; }
        
        .table-card {
            border-radius: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none; /* Hilangkan garis bawah link */
            display: block;
            color: inherit;
        }
        .table-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            color: inherit;
        }

        /* --- WARNA STATUS --- */
        
        /* 1. HIJAU: Kosong */
        .card-kosong {
            background-color: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
        }
        .card-kosong .table-icon { color: #28a745; }

        /* 2. KUNING: Ada Orang, Belum Bayar */
        .card-pending {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
        }
        .card-pending .table-icon { color: #ffc107; }

        /* 3. MERAH: Ada Orang, Sudah Lunas (Sedang Makan) */
        .card-lunas {
            background-color: #f8d7da;
            border: 2px solid #dc3545;
            color: #721c24;
        }
        .card-lunas .table-icon { color: #dc3545; }

        .table-icon { font-size: 3rem; }
        .status-badge { font-weight: bold; font-size: 0.9rem; margin-top: 5px; }
        .action-text { font-size: 0.8rem; font-style: italic; }
    </style>
</head>
<body>
    <div class="header text-center">
        <div class="container">
            <h1><i class="fas fa-cash-register"></i> Panel Kasir</h1>
            <p class="lead">Halo, <?= isset($_SESSION['kasir_nama']) ? htmlspecialchars($_SESSION['kasir_nama']) : 'Kasir' ?>!</p>
            <a href="logout_kasir.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Status Meja</h3>
            <div>
                <span class="badge bg-success me-2">Kosong</span>
                <span class="badge bg-warning text-dark me-2">Belum Bayar</span>
                <span class="badge bg-danger">Sedang Makan</span>
            </div>
        </div>

        <div class="row">
            <?php if ($result_meja && $result_meja->num_rows > 0): ?>
                <?php while ($row = $result_meja->fetch_assoc()): ?>
                    
                    <?php
                        // LOGIKA MENENTUKAN WARNA DAN LINK
                        $status_class = 'card-kosong';
                        $status_text = 'Kosong';
                        $icon = 'fa-chair';
                        $link = '#'; // Default tidak bisa diklik
                        $onclick = '';

                        if ($row['status_meja'] == 'Terisi') {
                            if ($row['status_pesanan'] == 'Pending') {
                                // KUNING: Belum Bayar
                                $status_class = 'card-pending';
                                $status_text = 'Belum Bayar';
                                $icon = 'fa-file-invoice-dollar';
                                // Link ke update_status untuk BAYAR
                                $link = "update_status_kasir.php?action=bayar&id_pesanan=" . $row['id_pesanan_aktif'];
                                $onclick = "return confirm('Konfirmasi Pembayaran untuk Meja " . $row['nomor_meja'] . "?')";
                            } 
                            elseif ($row['status_pesanan'] == 'Selesai') {
                                // MERAH: Sudah Lunas
                                $status_class = 'card-lunas';
                                $status_text = 'Sedang Makan';
                                $icon = 'fa-utensils';
                                // Link ke update_status untuk CLEAR MEJA
                                $link = "update_status_kasir.php?action=clear&id_meja=" . $row['id_meja'];
                                $onclick = "return confirm('Pelanggan sudah pulang? Bersihkan Meja " . $row['nomor_meja'] . "?')";
                            }
                        }
                    ?>

                    <div class="col-md-3 col-sm-6 mb-4">
                        <a href="<?= $link ?>" class="card table-card h-100 text-center <?= $status_class ?>" onclick="<?= $onclick ?>">
                            <div class="card-body py-4">
                                <i class="fas <?= $icon ?> table-icon mb-3"></i>
                                <h4 class="card-title">Meja <?= htmlspecialchars($row['nomor_meja']) ?></h4>
                                <div class="status-badge"><?= strtoupper($status_text) ?></div>
                                
                                <?php if($status_class != 'card-kosong'): ?>
                                    <div class="action-text mt-2">
                                        <?= ($status_class == 'card-pending') ? 'Klik untuk Bayar' : 'Klik jika Selesai' ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">Data meja tidak ditemukan.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        setTimeout(function(){
           location.reload();
        }, 10000);
    </script>
</body>
</html>