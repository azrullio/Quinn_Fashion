<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 240px;
        background-color: #343a40;
        color: #fff;
        padding-top: 60px;
        z-index: 1000;
    }

    .sidebar a {
        display: block;
        color: #ccc;
        padding: 15px 20px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
        background-color: #495057;
        color: #fff;
    }

    .sidebar h4 {
        padding-left: 20px;
        padding-bottom: 10px;
        margin-top: 0;
        border-bottom: 1px solid #555;
    }

    .main-content {
        margin-left: 240px;
        padding: 20px;
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
    <h4>Admin Panel</h4>
    <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Dashboard</a>
    <a href="tambah_barang.php" class="<?= basename($_SERVER['PHP_SELF']) == 'tambah_barang.php' ? 'active' : '' ?>">Tambah Barang</a>
<a href="slider_iklan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'slider_iklan.php' ? 'active' : '' ?>">Manajemen Slider Iklan</a>

    <a href="logout.php">Logout</a>
</div>
