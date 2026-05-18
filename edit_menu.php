<?php
// Letakkan ini di paling atas untuk menampilkan error jika ada
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'koneksi.php';

// Pastikan ada ID di URL
if (!isset($_GET['id'])) {
    header("Location: kelola_menu.php");
    exit;
}

$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu = $id");
$menu = mysqli_fetch_assoc($result);

// Jika menu dengan ID tersebut tidak ada
if (!$menu) {
    echo "Data tidak ditemukan.";
    exit;
}

// Proses form saat disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $status = isset($_POST['status']) ? $_POST['status'] : 'Habis';

    // --- LOGIKA UPLOAD FOTO BARU ---
    // DIUBAH: Gunakan nama kolom 'gambar' dari database, bukan 'gambar_menu'
    $gambar = $menu['gambar']; 

    // DIUBAH: Cek file upload dari input dengan name="foto"
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "uploads/"; // Folder untuk menyimpan foto
        // DIUBAH: Ambil nama file dari $_FILES['foto']
        $nama_file_baru = time() . '_' . basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $nama_file_baru;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'png', 'jpeg'];
        if (in_array($imageFileType, $allowed_types)) {
            // DIUBAH: Ambil file sementara dari $_FILES['foto']
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                // Jika berhasil, hapus foto lama jika ada
                // DIUBAH: Gunakan nama kolom 'gambar'
                if (!empty($menu['gambar']) && file_exists($target_dir . $menu['gambar'])) {
                    // DIUBAH: Hapus file lama berdasarkan nama dari kolom 'gambar'
                    unlink($target_dir . $menu['gambar']);
                }
                
                // DIUBAH: Update variabel '$gambar' (bukan '$gambar_menu') dengan nama file baru
                $gambar = $nama_file_baru;
            } else {
                echo "Maaf, terjadi kesalahan saat mengupload file.";
            }
        } else {
            echo "Maaf, hanya file JPG, JPEG, & PNG yang diizinkan.";
        }
    }

    // Update data ke database
    // DIUBAH: Pastikan nama kolom di query adalah 'gambar'
    $stmt = $conn->prepare("UPDATE menu SET nama_menu=?, harga=?, kategori=?, deskripsi=?, gambar=?, status=? WHERE id_menu=?");
    // DIUBAH: Masukkan variabel '$gambar' yang sudah benar
    $stmt->bind_param("sdssssi", $nama_menu, $harga, $kategori, $deskripsi, $gambar, $status, $id);
    
    if ($stmt->execute()) {
        header("Location: kelola_menu.php");
        exit;
    } else {
        // Blok ini akan menampilkan error jika query gagal
        echo "<h1>UPDATE GAGAL!</h1>";
        echo "<p>Error: " . htmlspecialchars($stmt->error) . "</p>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Edit Menu</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama_menu" class="form-label">Nama Menu</label>
            <input type="text" class="form-control" name="nama_menu" id="nama_menu" value="<?= htmlspecialchars($menu['nama_menu']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" name="harga" id="harga" value="<?= htmlspecialchars($menu['harga']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <input type="text" class="form-control" name="kategori" id="kategori" value="<?= htmlspecialchars($menu['kategori']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" name="deskripsi" id="deskripsi"><?= htmlspecialchars($menu['deskripsi']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto Menu</label>
            <?php if (!empty($menu['gambar'])): ?>
                <div class="mb-2">
                    <p>Foto saat ini:</p>
                    <img src="uploads/<?= htmlspecialchars($menu['gambar']) ?>" alt="Gambar Menu" style="max-width: 150px; height: auto;">
                </div>
            <?php endif; ?>
            <input type="file" class="form-control" name="foto" id="foto">
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status" id="status" required>
                <option value="Tersedia" <?= $menu['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                <option value="Habis" <?= $menu['status'] == 'Habis' ? 'selected' : '' ?>>Habis</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="kelola_menu.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>