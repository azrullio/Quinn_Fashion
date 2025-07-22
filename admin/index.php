<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';
include 'inc/sidebar.php'; // Jangan masukkan header di sini jika sudah pakai Bootstrap di bawah


$barang = mysqli_query($conn, "SELECT * FROM barang");
?>

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .scrolling-wrapper {
        overflow-x: auto;
        display: flex;
        gap: 1rem;
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
        min-width: 250px;
        max-width: 250px;
        flex: 0 0 auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .product-card img {
        height: 200px;
        object-fit: cover;
    }
</style>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Barang Quinn Fashion</h2>
        <a href="tambah_barang.php" class="btn btn-success">+ Tambah Barang</a>
    </div>

    <?php
    $kategoriBarang = [];
    while ($row = mysqli_fetch_assoc($barang)) {
        $kategoriBarang[$row['kategori']][] = $row;
    }

    foreach ($kategoriBarang as $kategori => $items) {
        echo "<h4 class='mt-4'>$kategori</h4>";
        echo "<div class='scrolling-wrapper'>";
        foreach ($items as $row) {
            echo "
            <div class='card product-card'>
                <img src='img/{$row['gambar']}' class='card-img-top' alt='Gambar'>
                <div class='card-body'>
                    <h5 class='card-title'>{$row['nama_barang']}</h5>
                    <p class='card-text mb-1'>Rp" . number_format($row['harga'], 0, ',', '.') . "</p>
                    <p class='card-text mb-1'>Stok: {$row['stok']}</p>
                    <p class='card-text small mb-2'>{$row['deskripsi']}</p>
                    <div class='d-grid gap-1 mb-2'>
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
