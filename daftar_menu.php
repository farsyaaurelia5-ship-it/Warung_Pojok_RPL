<?php
session_start();
include 'koneksi.php';

// --- PROSES TAMBAH ITEM KE KERANJANG ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $menu_id = $_POST['menu_id'];
    $jumlah = $_POST['quantity'];

    // [PERBAIKAN 1] SQL Syntax diperbaiki: 'id_menu = ?' (bukan i) dan status = '1'
    $stmt = $conn->prepare("SELECT * FROM menu WHERE id_menu = ? AND status = '1'");
    $stmt->bind_param("i", $menu_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $menu = $result->fetch_assoc();
        $item = [
            'menu_id'   => $menu['id_menu'],
            'nama_menu' => $menu['nama_menu'],
            'harga'     => $menu['harga'],
            'jumlah'    => $jumlah,
            'subtotal'  => $menu['harga'] * $jumlah,
            'gambar'    => $menu['gambar']
        ];

        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        $item_exists = false;
        foreach ($_SESSION['keranjang'] as &$cart_item) {
            if ($cart_item['menu_id'] == $menu_id) {
                $cart_item['jumlah'] += $jumlah;
                $cart_item['subtotal'] = $cart_item['harga'] * $cart_item['jumlah'];
                $item_exists = true;
                break;
            }
        }
        unset($cart_item);

        if (!$item_exists) {
            $_SESSION['keranjang'][] = $item;
        }

        $_SESSION['pesan_sukses'] = "<strong>" . htmlspecialchars($item['nama_menu']) . "</strong> berhasil ditambahkan!";
    } else {
        $_SESSION['pesan_error'] = "Menu tidak ditemukan atau sudah habis.";
    }

    header("Location: daftar_menu.php");
    exit;
}


// --- PROSES PENGAMBILAN DATA MENU UNTUK DITAMPILKAN ---
$menusByCategory = [];

// [PERBAIKAN 2] Query disesuaikan dengan isi database (status = '1')
$query = "SELECT * FROM menu WHERE status = '1' ORDER BY kategori, nama_menu";
$result = $conn->query($query);

if (!$result) {
    $_SESSION['pesan_error'] = "Terjadi kesalahan saat mengambil data menu.";
} else {
    while ($menu = $result->fetch_assoc()) {
        $menusByCategory[$menu['kategori']][] = $menu;
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Warung Pojok - Daftar Menu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="daftar_menu.css">
</head>
<body>

    <div class="jumbotron jumbotron-fluid text-center mb-4">
        <div class="container">
            <h1 class="display-4 font-weight-bold">WARUNG <span>POJOK</span></h1>
            <hr class="my-4 bg-light" />
            <p class="lead font-weight-bold">
                Silahkan Pesan Menu Sesuai Keinginan Anda<br />
                Enjoy Your Meal
            </p>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="index.php">Warung <span>Pojok</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="daftar_menu.php"><i class="fas fa-utensils me-1"></i>Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="keranjang.php"><i class="fas fa-shopping-cart me-1"></i>Keranjang</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <h3 class="text-center font-weight-bold mb-4">DAFTAR MENU</h3>

        <?php
        if (isset($_SESSION['pesan_sukses'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . $_SESSION['pesan_sukses'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            unset($_SESSION['pesan_sukses']);
        }
        if (isset($_SESSION['pesan_error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['pesan_error'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            unset($_SESSION['pesan_error']);
        }
        ?>

        <?php if (!empty($menusByCategory)): ?>
            <?php foreach ($menusByCategory as $category => $menus): ?>
                <h4 class="section-title mt-5"><?= strtoupper(htmlspecialchars($category)) ?></h4>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php foreach ($menus as $menu): ?>
                        <div class="col">
                            <div class="card border-dark h-100">
                                <?php $gambar = !empty($menu['gambar']) ? $menu['gambar'] : 'default.jpg'; ?>
                                
                                <img src="uploads/<?= htmlspecialchars($gambar) ?>" class="card-img-top" alt="<?= htmlspecialchars($menu['nama_menu']) ?>" style="height: 200px; object-fit: cover;" />
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title font-weight-bold"><?= htmlspecialchars($menu['nama_menu']) ?></h5>
                                    <p class="card-text harga">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></p>
                                    
                                    <?php if (!empty($menu['deskripsi'])): ?>
                                        <p class="card-text small text-muted flex-grow-1"><?= htmlspecialchars($menu['deskripsi']) ?></p>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="daftar_menu.php" class="mt-auto">
                                        <input type="hidden" name="menu_id" value="<?= $menu['id_menu'] ?>" />
                                        <div class="input-group mb-2">
                                            <label class="input-group-text" for="jumlah_<?= $menu['id_menu'] ?>">Jumlah</label>
                                            <input type="number" name="quantity" id="jumlah_<?= $menu['id_menu'] ?>" class="form-control" value="1" min="1" />
                                        </div>
                                        <button type="submit" name="add_to_cart" class="btn btn-success w-100">
                                            <i class="fas fa-cart-plus me-1"></i> Beli
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">Saat ini belum ada menu yang tersedia.</div>
        <?php endif; ?>
    </main>

    <footer class="mt-5 py-3 bg-dark text-white">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> Warung Pojok. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>