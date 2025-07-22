<?php
include 'admin/inc/db.php';

// Ambil produk dan kategori
$produk = $conn->query("SELECT produk.*, kategori.nama_kategori 
                        FROM produk 
                        JOIN kategori ON produk.kategori_id = kategori.id 
                        ORDER BY produk.created_at DESC");

$video = $conn->query("SELECT * FROM video_iklan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Toko Fashion</title>
    <style>
        body { font-family: Arial; margin: 0; padding: 0; }
        .header { background: #333; color: white; padding: 15px; text-align: center; }
        .produk-container { display: flex; flex-wrap: wrap; justify-content: center; padding: 20px; }
        .produk-card { width: 220px; border: 1px solid #ddd; margin: 10px; padding: 10px; text-align: center; }
        .produk-card img { max-width: 100%; height: 200px; object-fit: cover; }
        .produk-card h4 { margin: 10px 0 5px; }
        .produk-card p { margin: 5px 0; }
        .video-section { padding: 20px; background: #f9f9f9; }
        .video-wrapper { margin-bottom: 20px; text-align: center; }
        .footer { text-align: center; padding: 20px; background: #333; color: white; margin-top: 40px; }
    </style>
</head>
<body>

<div class="header">
    <h1>Selamat Datang di Toko Fashion</h1>
</div>

<div class="video-section">
    <h2>Video Iklan</h2>
    <?php while ($v = $video->fetch_assoc()) : ?>
        <div class="video-wrapper">
            <h4><?= $v['judul'] ?></h4>
            <?php if (str_contains($v['file_video'], 'http')): ?>
                <iframe width="300" height="180" src="<?= $v['file_video'] ?>" frameborder="0" allowfullscreen></iframe>
            <?php else: ?>
                <video width="300" controls>
                    <source src="uploads/<?= $v['file_video'] ?>" type="video/mp4">
                </video>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>

<div class="produk-container">
    <?php while ($p = $produk->fetch_assoc()) : ?>
        <div class="produk-card">
            <img src="uploads/<?= $p['gambar'] ?>" alt="<?= $p['nama_produk'] ?>">
            <h4><?= $p['nama_produk'] ?></h4>
            <p><strong>Rp <?= number_format($p['harga']) ?></strong></p>
            <p><small><?= $p['nama_kategori'] ?></small></p>
        </div>
    <?php endwhile; ?>
</div>

<div class="footer">
    <p>&copy; <?= date('Y') ?> Toko Fashion. Semua Hak Dilindungi.</p>
</div>

</body>
</html>
