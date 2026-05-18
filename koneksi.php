<?php
$host     = 'localhost';
$user     = 'root';
$password = '';
$dbname   = 'warung_pojok';

$conn = new mysqli($host, $user, $password, $dbname);

// cek koneksi
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}
?>
