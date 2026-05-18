<?php
session_start();
include 'koneksi.php';

// Cek apakah owner sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login_owner.php");
    exit();
}

// Logika untuk menghapus menu
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "DELETE FROM menu WHERE id_menu = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: kelola_menu.php");
        exit;
    }
}

// Ambil semua data menu
$menus = mysqli_query($conn, "SELECT * FROM menu ORDER BY id_menu DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Warung Pojok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="kelola_menu.css">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-utensils me-2"></i> Kelola Menu</h2>
            <div>
                <a href="owner_dashboard.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
                <a href="tambah_menu.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Menu
                </a>
            </div>
        </div>
        
        <div class="input-group mb-3">
            <input type="text" class="form-control search-input" placeholder="Cari menu...">
            <button class="btn btn-outline-secondary" type="button">
                <i class="fas fa-search"></i>
            </button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered menu-table">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Menu</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($menu = mysqli_fetch_assoc($menus)): ?>
                    <tr>
                        <td><?= htmlspecialchars($menu['nama_menu']) ?></td>
                        <td>Rp <?= number_format($menu['harga'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($menu['kategori']) ?></td>
                        <td>
                            <span class="badge <?= $menu['status'] == 'Tersedia' ? 'bg-success' : 'bg-danger' ?>">
                                <?= htmlspecialchars($menu['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="edit_menu.php?id=<?= $menu['id_menu'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="kelola_menu.php?hapus=<?= $menu['id_menu'] ?>" 
                                onclick="return confirm('Yakin ingin menghapus menu ini?')" 
                                class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi pencarian sederhana
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.menu-table tbody tr');
            
            rows.forEach(row => {
                const menuName = row.querySelector('td:first-child').textContent.toLowerCase();
                if (menuName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>