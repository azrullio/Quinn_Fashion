<?php
include '../admin/inc/db.php';

// Ambil query pencarian dan filter kategori dari URL
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$kategori_filter = isset($_GET['kategori_id']) ? trim($_GET['kategori_id']) : '';

// Ambil data kategori untuk menu
$kategori = mysqli_query($conn, "SELECT id AS kategori_id, nama_kategori FROM kategori ORDER BY nama_kategori ASC");

// Build kondisi WHERE untuk produk
$conditions = [];
if (!empty($search)) {
    $search_escaped = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(b.nama_barang LIKE '%$search_escaped%' OR b.deskripsi LIKE '%$search_escaped%')";
}
if (!empty($kategori_filter)) {
    $kategori_filter_escaped = mysqli_real_escape_string($conn, $kategori_filter);
    $conditions[] = "b.kategori_id = '$kategori_filter_escaped'";
}
$where = '';
if (!empty($conditions)) {
    $where = 'WHERE ' . implode(' AND ', $conditions);
}

// Query produk dengan JOIN kategori
$sql_barang = "
    SELECT b.*, k.nama_kategori
    FROM barang b
    JOIN kategori k ON b.kategori_id = k.id
    $where
    ORDER BY b.nama_barang ASC
";
$produk = mysqli_query($conn, $sql_barang);

// Query slider video iklan
$slider = mysqli_query($conn, "SELECT * FROM slider_iklan ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Beranda Produk - Quinn Fashion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="main-header py-3 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="welcome-text mb-0">Selamat Datang di Quinn Fashion</h1>
            <form method="GET" class="search-form d-flex">
                <input type="text" name="q" class="form-control me-2" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
                <input type="hidden" name="kategori_id" value="<?= htmlspecialchars($kategori_filter) ?>" />
                <button class="btn btn-search" type="submit">Cari</button>
            </form>
        </div>
    </header>

    <div class="container mt-4">

        <div class="category-filter mb-4 text-center">
            <div class="btn-group" role="group" aria-label="Kategori">
                <a href="index.php" class="btn btn-outline-primary <?= ($kategori_filter == '') ? 'active' : '' ?>">Semua</a>
                <?php mysqli_data_seek($kategori, 0); // Reset pointer for category loop ?>
                <?php while ($kat = mysqli_fetch_assoc($kategori)) : ?>
                    <?php $is_active = ($kategori_filter == $kat['kategori_id']) ? 'active' : ''; ?>
                    <a href="index.php?kategori_id=<?= $kat['kategori_id'] ?>" class="btn btn-outline-primary <?= $is_active ?>">
                        <?= htmlspecialchars($kat['nama_kategori']) ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>

        <div id="carouselIklan" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false">
            <div class="carousel-inner">
                <?php $active = 'active'; ?>
                <?php while ($s = $slider->fetch_assoc()) : ?>
                    <div class="carousel-item <?= $active ?>">
                        <img src="../uploads/<?= htmlspecialchars($s['file_gambar']) ?>" class="d-block w-100 rounded" alt="<?= htmlspecialchars($s['judul']) ?>">
                    </div>
                    <?php $active = ''; ?>
                <?php endwhile; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselIklan" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselIklan" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
            </button>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php if ($produk->num_rows > 0): ?>
                <?php while ($p = $produk->fetch_assoc()) : ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm" style="position: relative;">
                            <?php if (!empty(trim($p['promo']))) : ?>
                                <span class="promo-badge">PROMO</span>
                            <?php endif; ?>
                            <img src="../admin/img/<?= htmlspecialchars($p['gambar']) ?>"
                                onerror="this.onerror=null;this.src='default.jpg';"
                                class="card-img-top" alt="<?= htmlspecialchars($p['nama_barang']) ?>">

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($p['nama_barang']) ?></h5>
                                <p class="card-text mb-1"><strong>Rp<?= number_format($p['harga'], 0, ',', '.') ?></strong></p>
                                <p class="card-text mb-2"><small>Kategori: <?= htmlspecialchars($p['nama_kategori']) ?></small></p>

                                <div class="mt-auto d-flex gap-2 flex-wrap">
                                    <?php if (!empty($p['link_shopee'])) : ?>
                                        <a href="<?= htmlspecialchars($p['link_shopee']) ?>" target="_blank" class="btn btn-warning btn-sm flex-grow-1">Shopee</a>
                                    <?php endif; ?>
                                    <?php if (!empty($p['link_tokopedia'])) : ?>
                                        <a href="<?= htmlspecialchars($p['link_tokopedia']) ?>" target="_blank" class="btn btn-success btn-sm flex-grow-1">Tokopedia</a>
                                    <?php endif; ?>
                                    <?php if (!empty($p['link_lazada'])) : ?>
                                        <a href="<?= htmlspecialchars($p['link_lazada']) ?>" target="_blank" class="btn btn-primary btn-sm flex-grow-1">Lazada</a>
                                    <?php endif; ?>
                                </div>

                                <a href="detail.php?id=<?= $p['id'] ?>" class="btn btn-outline-dark btn-sm mt-3">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">Tidak ada produk ditemukan.</div>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Panggil footer di sini, sebelum tutup body -->
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
