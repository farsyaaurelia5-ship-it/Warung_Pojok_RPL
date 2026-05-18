<?php
require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int) $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE pesanan SET status_pesanan = ? WHERE id_pesanan = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
}

header("Location: kelola_pesanan_admin.php");
exit;
?>
