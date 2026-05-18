<?php
session_start();
if (isset($_SESSION['kasir_id'])) {
    header("Location: kasir_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir - Warung Pojok</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="jumbotron jumbotron-fluid text-center mb-4">
        <div class="container">
            <h1 class="display-4 font-weight-bold">LOGIN <span>KASIR</span></h1>
            <hr class="my-4 bg-light" />
            <p class="lead font-weight-bold">
                Masukkan nama dan password Anda<br />
                untuk mengakses panel kasir
            </p>
        </div>
    </div>

    <div class="container">
        <div class="login-container">
            <h3 class="mb-4 text-center"><i class="fas fa-cash-register mr-2"></i> Kasir Login</h3>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="proses_login_kasir.php" method="POST">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Kasir</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <div class="text-center mt-3">
                <p>Belum punya akun? <a href="register_kasir.php">Daftar di sini</a></p>
            </div>
        </div>
    </div>

    <footer class="mt-5">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> Warung Pojok. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>