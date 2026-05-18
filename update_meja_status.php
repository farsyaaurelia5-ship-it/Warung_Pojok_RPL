<?php
session_start();
require 'koneksi.php';

// Hanya kasir yang bisa mengakses
if (!isset($_SESSION['kasir_id'])) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
    exit();
}

// Validasi input
if (isset($_POST['id_meja']) && isset($_POST['status'])) {
    $id_meja = intval($_POST['id_meja']);
    $status = $_POST['status'] === 'Terisi' ? 'Terisi' : 'Kosong'; // Pastikan status valid

    // Update status di database
    $sql = "UPDATE meja SET status_meja = ? WHERE id_meja = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("si", $status, $id_meja);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengeksekusi query']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mempersiapkan statement']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Input tidak valid']);
}

$conn->close();
?>
