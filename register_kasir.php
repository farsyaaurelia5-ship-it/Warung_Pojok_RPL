<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Kasir | Warung Pojok</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="register.css">
</head>
<body>

<div class="jumbotron jumbotron-fluid">
    <div class="container">
    <h1 class="display-4 font-weight-bold">DAFTAR <span>KASIR</span></h1>
    <hr class="my-4 bg-light" />
    <p class="lead font-weight-bold">
        Buat akun baru untuk kasir
    </p>
    </div>
</div>


<div class="container">
    <div class="register-container">
    <h3 class="text-center mb-4"><i class="fas fa-user-plus mr-2"></i> Form Pendaftaran Kasir</h3>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
        <?= $_SESSION['flash']['msg'] ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form action="proses_register_kasir.php" method="POST">
        <div class="mb-3">
        <label class="form-label">Nama Kasir</label>
        <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="confirm" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">
        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
        </button>
        <p class="text-center mt-3">Sudah punya akun? <a href="login_kasir.php" class="login-link">Login disini</a></p>
    </form>
    </div>
</div>

<footer>
    <div class="container">
    <p>&copy; 2024 Warung Pojok. All rights reserved.</p>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>