<!-- Tambahkan link ini di <head> jika mau icon Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
    body {
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        background-color: #f4f6f8;
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 250px;
        height: 100vh;
        background: linear-gradient(145deg, #1e1f31, #343a40);
        color: white;
        padding-top: 30px;
        box-shadow: 4px 0 15px rgba(0, 0, 0, 0.3);
        z-index: 999;
    }

    .sidebar h4 {
        text-align: center;
        font-size: 22px;
        margin-bottom: 30px;
        color: #ffffff;
        font-weight: bold;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #ccc;
        text-decoration: none;
        padding: 15px 25px;
        transition: 0.3s;
        font-size: 16px;
        border-left: 4px solid transparent;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #495057;
        color: #ffffff;
        border-left: 4px solid #00d1b2;
    }

    .sidebar a i {
        width: 20px;
        text-align: center;
    }

    .main-content {
        margin-left: 250px;
        padding: 25px;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .main-content {
            margin-left: 0;
        }
    }
</style>

<div class="sidebar">
    <h4><i class="fas fa-user-cog"></i> Admin Panel</h4>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="tambah_barang.php" class="<?= basename($_SERVER['PHP_SELF']) == 'tambah_barang.php' ? 'active' : '' ?>">
        <i class="fas fa-plus-square"></i> Tambah Barang
    </a>
    <a href="slider_iklan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'slider_iklan.php' ? 'active' : '' ?>">
        <i class="fas fa-images"></i> Slider Iklan
    </a>
    <a href="kategori.php" class="<?= basename($_SERVER['PHP_SELF']) == 'kategori.php' ? 'active' : '' ?>">
        <i class="fas fa-tags"></i> Kategori
    </a>
    <a href="keuangan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'keuangan.php' ? 'active' : '' ?>">
        <i class="fas fa-wallet"></i> Keuangan
    </a>
    <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>
