<?php
include 'admin/inc/db.php';
include 'header.php';

$id = intval($_GET['id'] ?? 0);
$query = $conn->query("
    SELECT barang.*, kategori.nama_kategori AS kategori
    FROM barang
    JOIN kategori ON barang.kategori_id = kategori.id
    WHERE barang.id = $id
");

if (!$data = $query->fetch_assoc()) {
    echo "<div class='container mt-5'><p class='alert alert-danger'>Produk tidak ditemukan. <a href='index.php'>Kembali ke Beranda</a></p></div>";
    include 'footer.php';
    exit;
}

// Produk rekomendasi berdasarkan kategori yang sama
$kategori_id = intval($data['kategori_id'] ?? 0);
$produk_rekomendasi = $conn->query("
    SELECT id, nama_barang, harga, gambar, kategori_id, promo
    FROM barang
    WHERE kategori_id = $kategori_id AND id != $id
    ORDER BY nama_barang ASC
    LIMIT 6
");
?>
<link rel="stylesheet" href="public/style.css">
<div class="container my-5 product-detail-container">
    <div class="row">
        <div class="col-md-6 text-center" style="position: relative;">
            <?php if (!empty(trim($data['promo']))) : ?>
                <span class="promo-badge">PROMO</span>
            <?php endif; ?>
            <img src="admin/img/<?= htmlspecialchars($data['gambar']) ?>"
                alt="<?= htmlspecialchars($data['nama_barang']) ?>"
                onerror="this.onerror=null;this.src='default.jpg';"
                class="img-fluid mb-3 rounded shadow-sm product-detail-image">
        </div>
        <div class="col-md-6 product-detail-info">
            <h2><?= htmlspecialchars($data['nama_barang']) ?></h2>
            <p class="price-text"><strong>Harga:</strong> Rp <?= number_format($data['harga'], 0, ',', '.') ?></p>
            <p><strong>Kategori:</strong> <span class="badge bg-info text-dark"><?= htmlspecialchars($data['kategori']) ?></span></p>
            <p><strong>Stok:</strong> <span class="badge bg-secondary"><?= htmlspecialchars($data['stok']) ?></span></p>

            <h4 class="mt-4">Deskripsi Produk:</h4>
            <p class="description-text"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>

            <div class="marketplace-links mt-4">
                <?php if (!empty($data['link_shopee'])): ?>
                    <p><a href="<?= htmlspecialchars($data['link_shopee']) ?>" target="_blank" class="btn btn-outline-warning btn-lg me-2"><i class="fas fa-shopping-bag"></i> 🛒 Beli di Shopee</a></p>
                <?php endif; ?>
                <?php if (!empty($data['link_tokopedia'])): ?>
                    <p><a href="<?= htmlspecialchars($data['link_tokopedia']) ?>" target="_blank" class="btn btn-outline-success btn-lg me-2"><i class="fas fa-shopping-bag"></i> 🛒 Beli di Tokopedia</a></p>
                <?php endif; ?>
                <?php if (!empty($data['link_lazada'])): ?>
                    <p><a href="<?= htmlspecialchars($data['link_lazada']) ?>" target="_blank" class="btn btn-outline-primary btn-lg"><i class="fas fa-shopping-bag"></i> 🛒 Beli di Lazada</a></p>
                <?php endif; ?>
            </div>

            <div class="mt-4">
                <a href="index.php" class="btn btn-secondary">← Kembali ke Katalog</a>
            </div>
        </div>
    </div>
</div>

<!-- Produk Rekomendasi -->
<div class="container my-5">
    <h3>Produk Rekomendasi</h3>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php if ($produk_rekomendasi && $produk_rekomendasi->num_rows > 0): ?>
            <?php while ($rec = $produk_rekomendasi->fetch_assoc()): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm position-relative">
                        <?php if (!empty(trim($rec['promo']))) : ?>
                            <span class="promo-badge">PROMO</span>
                        <?php endif; ?>
                        <img src="admin/img/<?= htmlspecialchars($rec['gambar']) ?>" 
                             alt="<?= htmlspecialchars($rec['nama_barang']) ?>" 
                             class="card-img-top"
                             onerror="this.onerror=null;this.src='default.jpg';">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($rec['nama_barang']) ?></h5>
                            <p class="card-text mb-1"><strong>Rp<?= number_format($rec['harga'], 0, ',', '.') ?></strong></p>
                            <a href="detail.php?id=<?= $rec['id'] ?>" class="btn btn-outline-primary mt-auto">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <p>Tidak ada produk rekomendasi.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
