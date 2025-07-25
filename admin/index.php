<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';
include 'inc/sidebar.php'; // Jangan masukkan header di sini jika sudah pakai Bootstrap di bawah


$barang = mysqli_query($conn, "SELECT barang.*, kategori.nama_kategori FROM barang JOIN kategori ON barang.kategori_id = kategori.id");

?>

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', sans-serif;
    }

    .main-content {
        padding: 2rem;
    }

    .scrolling-wrapper {
        overflow-x: auto;
        display: flex;
        gap: 1.5rem;
        padding-bottom: 1rem;
    }

    .scrolling-wrapper::-webkit-scrollbar {
        height: 8px;
    }

    .scrolling-wrapper::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 4px;
    }

    .product-card {
        min-width: 270px;
        max-width: 270px;
        flex: 0 0 auto;
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .product-card img {
        height: 200px;
        object-fit: cover;
    }

    .badge-kategori {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: #fff;
        padding: 0.25rem 0.6rem;
        font-size: 0.75rem;
        border-radius: 8px;
    }

    .btn-outline-danger:hover,
    .btn-outline-primary:hover,
    .btn-outline-success:hover {
        transform: scale(1.05);
    }

    h4 {
        color: #333;
        font-weight: 600;
        margin-top: 2rem;
        border-left: 4px solid #0d6efd;
        padding-left: 0.5rem;
    }
</style>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Data Barang <span style="color:#d63384">Quinn Fashion</span></h2>
        <a href="tambah_barang.php" class="btn btn-success shadow-sm">+ Tambah Barang</a>
    </div>

    <?php
    $kategoriBarang = [];
    while ($row = mysqli_fetch_assoc($barang)) {
        $kategoriBarang[$row['nama_kategori']][] = $row;
    }

    foreach ($kategoriBarang as $kategori => $items) {
        echo "<h4>$kategori</h4>";
        echo "<div class='scrolling-wrapper'>";
        foreach ($items as $row) {
            echo "
            <div class='card product-card position-relative'>
                <img src='img/{$row['gambar']}' class='card-img-top' alt='Gambar'>
                <span class='badge-kategori'>{$kategori}</span>
                <div class='card-body'>
                    <h5 class='card-title text-truncate'>{$row['nama_barang']}</h5>
                    <p class='card-text mb-1 text-muted'>Rp" . number_format($row['harga'], 0, ',', '.') . "</p>
                    <p class='card-text mb-1'><strong>Stok:</strong> {$row['stok']}</p>
                    <p class='card-text small mb-2 text-secondary'>{$row['deskripsi']}</p>
                    <div class='d-grid gap-2 mb-2'>
                        <a href='{$row['link_shopee']}' target='_blank' class='btn btn-sm btn-outline-danger'>Shopee</a>
                        <a href='{$row['link_lazada']}' target='_blank' class='btn btn-sm btn-outline-primary'>Lazada</a>
                        <a href='{$row['link_tokopedia']}' target='_blank' class='btn btn-sm btn-outline-success'>Tokopedia</a>
                    </div>
                    <div class='d-flex justify-content-between'>
                        <a href='edit_barang.php?id={$row['id']}' class='btn btn-warning btn-sm w-50 me-1'>Edit</a>
                        <a href='hapus_barang.php?id={$row['id']}' onclick=\"return confirm('Yakin ingin hapus barang ini?')\" class='btn btn-danger btn-sm w-50'>Hapus</a>
                    </div>
                </div>
            </div>
            ";
        }
        echo "</div>";
    }
    ?>
</div>
