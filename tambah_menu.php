<?php
session_start();
include 'koneksi.php';

// Menangani form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    $status = isset($_POST['status']) ? $_POST['status'] : 'Habis';
    $gambar = ''; // Default nama gambar kosong

    // Proses upload gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['gambar']['name']);
        // Membuat nama file yang unik untuk menghindari penimpaan file
        $new_file_name = uniqid() . '-' . $file_name;
        $target_path = $upload_dir . $new_file_name;

        // Memindahkan file yang di-upload ke direktori 'uploads'
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_path)) {
            $gambar = $new_file_name;
        } else {
            echo "Error: Gagal mengupload gambar.";
            exit;
        }
    }

    // Menambahkan kolom 'gambar' ke dalam query INSERT
    $stmt = $conn->prepare("INSERT INTO menu (nama_menu, harga, kategori, deskripsi, status, gambar) VALUES (?, ?, ?, ?, ?, ?)");
    // Menyesuaikan tipe data di bind_param (s=string, d=double, i=integer)
    $stmt->bind_param("sdssss", $nama_menu, $harga, $kategori, $deskripsi, $status, $gambar);
    
    if ($stmt->execute()) {
        header("Location: kelola_menu.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Menu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Tambah Menu Baru</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama_menu" class="form-label">Nama Menu</label>
            <input type="text" class="form-control" name="nama_menu" id="nama_menu" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" class="form-control" name="harga" id="harga" required>
        </div>
        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-select" name="kategori" id="kategori" required>
                <option value="" disabled selected>Pilih Kategori</option>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" name="deskripsi" id="deskripsi"></textarea>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Gambar Menu</label>
            <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status" id="status" required>
                <option value="Tersedia">Tersedia</option>
                <option value="Habis">Habis</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Tambah Menu</button>
        <a href="kelola_menu.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>