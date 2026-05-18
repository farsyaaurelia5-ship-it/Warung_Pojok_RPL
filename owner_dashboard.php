<?php
session_start();

// Cek role owner
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login_owner.php");
    exit;
}

include 'koneksi.php';


$query_dashboard = "
    SELECT
        -- Menghitung pesanan dengan status 'diproses' untuk kartu 
        SUM(CASE WHEN status_pesanan = 'Pending' THEN 1 ELSE 0 END) AS pesanan_pending,
        
        -- Menjumlahkan total pendapatan dari pesanan yang 'selesai' HARI INI
        SUM(CASE WHEN status_pesanan = 'Selesai' AND DATE(tanggal_pesanan) = CURDATE() THEN total_harga ELSE 0 END) AS pendapatan_hari_ini,
        
        -- Menghitung jumlah pesanan yang 'selesai' HARI INI
        SUM(CASE WHEN status_pesanan = 'Selesai' AND DATE(tanggal_pesanan) = CURDATE() THEN 1 ELSE 0 END) AS pesanan_selesai_hari_ini
    FROM
        pesanan
";

$result = mysqli_query($conn, $query_dashboard);

// Ambil hasilnya, jika tidak ada data, berikan nilai default 0 untuk semua.
$data = mysqli_fetch_assoc($result) ?? [
    'pesanan_pending' => 0,
    'pendapatan_hari_ini' => 0,
    'pesanan_selesai_hari_ini' => 0
];

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard Owner - Warung Pojok</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="d-flex">
        <div class="sidebar position-fixed bg-dark" style="width: 250px; min-height: 100vh;">
            <div class="p-3">
                <h4 class="text-white mb-4">Warung Pojok</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link text-white active" href="owner_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="kelola_menu.php"><i class="fas fa-utensils me-2"></i>Kelola Menu</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="laporan.php"><i class="fas fa-chart-line me-2"></i>Laporan</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="logout_owner.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
        
        <main class="flex-grow-1" style="margin-left: 250px;">
            <div class="container-fluid py-4">
                <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama'] ?? 'Owner') ?></h2>
                <hr>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <a href="laporan.php" class="text-decoration-none">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Pendapatan Hari Ini</h5>
                                    <h2>Rp <?= number_format($data['pendapatan_hari_ini'] ?? 0, 0, ',', '.') ?></h2>
                                    <p class="mb-0">Pendapatan pada tanggal <?= date('d/m/Y') ?></p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card bg-warning text-dark h-100">
                            <div class="card-body">
                                <h5 class="card-title">Pesanan Selesai Hari Ini</h5>
                                <h2><?= $data['pesanan_selesai_hari_ini'] ?></h2>
                                <p class="mb-0">Total pesanan selesai hari ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>