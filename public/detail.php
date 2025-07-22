<?php
include '../admin/inc/db.php';

$id = intval($_GET['id'] ?? 0);
$query = $conn->query("SELECT * FROM barang WHERE id = $id");

if (!$data = $query->fetch_assoc()) {
    echo "<p>Produk tidak ditemukan. <a href='index.php'>Kembali</a></p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk - <?= htmlspecialchars($data['nama_barang']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    </head>
<body>

<div class="detail-page-container">
    <h2><?= htmlspecialchars($data['nama_barang']) ?></h2>
    <img src="../uploads/<?= htmlspecialchars($data['gambar']) ?>"
            alt="<?= htmlspecialchars($data['nama_barang']) ?>"
            onerror="this.onerror=null;this.src='default.jpg';">
    
    <p><strong>Harga:</strong> Rp <?= number_format($data['harga'], 0, ',', '.') ?></p>
    <p><strong>Kategori:</strong> <?= htmlspecialchars($data['kategori']) ?></p>
    <p><strong>Stok:</strong> <?= htmlspecialchars($data['stok']) ?></p>
    <p><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>

    <?php if (!empty($data['link_shopee'])): ?>
        <p><a href="<?= htmlspecialchars($data['link_shopee']) ?>" target="_blank" class="marketplace-link shopee-link">🛒 Beli di Shopee</a></p>
    <?php endif; ?>
    <?php if (!empty($data['link_tokopedia'])): ?>
        <p><a href="<?= htmlspecialchars($data['link_tokopedia']) ?>" target="_blank" class="marketplace-link tokopedia-link">🛒 Beli di Tokopedia</a></p>
    <?php endif; ?>
    <?php if (!empty($data['link_lazada'])): ?>
        <p><a href="<?= htmlspecialchars($data['link_lazada']) ?>" target="_blank" class="marketplace-link lazada-link">🛒 Beli di Lazada</a></p>
    <?php endif; ?>

    <div class="back">
        <a href="index.php">← Kembali ke Katalog</a>
    </div>
</div>

</body>
</html>