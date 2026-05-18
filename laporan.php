<?php
include 'koneksi.php';
$query_bulanan = "
    SELECT
        YEAR(tanggal_pesanan) AS tahun,
        MONTHNAME(tanggal_pesanan) AS bulan,
        SUM(total_harga) AS total_pemasukan
    FROM
        pesanan WHERE status_pesanan = 'Selesai'
    GROUP BY
        tahun, bulan, MONTH(tanggal_pesanan)
    ORDER BY
        tahun DESC, MONTH(tanggal_pesanan) DESC
";
$result_bulanan = mysqli_query($conn, $query_bulanan);

$query_tahunan = "
    SELECT
        YEAR(tanggal_pesanan) AS tahun,
        SUM(total_harga) AS total_pemasukan
    FROM
        pesanan WHERE status_pesanan = 'Selesai'
    GROUP BY
        YEAR(tanggal_pesanan)
    ORDER BY
        tahun DESC
";
$result_tahunan = mysqli_query($conn, $query_tahunan);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemasukan Modern</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="laporan.css">
</head>
<body>

<div class="container">
    <h1 class="main-title"><span></span>Laporan Pemasukan</h1>
    <div class="card">
        <div class="card-header monthly">
            Laporan Pemasukan Bulanan
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Bulan</th>
                        <th class="text-end">Total Pemasukan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_bulanan && mysqli_num_rows($result_bulanan) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result_bulanan)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['tahun']) ?></td>
                                <td><?= htmlspecialchars($row['bulan']) ?></td>
                                <td class="text-end total-column">Rp <?= number_format($row['total_pemasukan'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center p-5">Belum ada data pemasukan bulanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header yearly">
            Laporan Pemasukan Tahunan
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th class="text-end">Total Pemasukan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_tahunan && mysqli_num_rows($result_tahunan) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result_tahunan)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['tahun']) ?></td>
                                <td class="text-end total-column">Rp <?= number_format($row['total_pemasukan'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center p-5">Belum ada data pemasukan tahunan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="owner_dashboard.php" class="btn btn-back">Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>