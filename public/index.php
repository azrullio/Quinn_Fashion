<?php
include '../admin/inc/db.php'; // Pastikan jalur ke file db.php sudah benar

// Tangani pencarian
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Tangani filter kategori
$kategori_filter = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

// --- Mulai: Perbaikan Logika Pembangunan Klausa WHERE ---
$conditions = []; // Array untuk menyimpan semua kondisi WHERE

if (!empty($search)) {
    // Tambahkan kondisi pencarian ke array jika ada
    $conditions[] = "nama_barang LIKE '%" . $conn->real_escape_string($search) . "%'";
}

if (!empty($kategori_filter)) {
    // Tambahkan kondisi kategori ke array jika ada
    $conditions[] = "kategori = '" . $conn->real_escape_string($kategori_filter) . "'";
}

$where_clause = '';
if (!empty($conditions)) {
    // Jika ada kondisi (array tidak kosong), gabungkan dengan 'AND'
    // dan tambahkan 'WHERE' di depannya
    $where_clause = " WHERE " . implode(" AND ", $conditions);
}
// --- Akhir: Perbaikan Logika Pembangunan Klausa WHERE ---


// Ambil produk dari tabel "barang" dengan filter pencarian dan kategori
// Gunakan $where_clause yang sudah dibangun dengan benar
$produk_query_string = "SELECT * FROM barang " . $where_clause . " ORDER BY id DESC";
$produk = $conn->query($produk_query_string);

// Pastikan query berhasil dieksekusi
if ($produk === false) {
    die('Error fetching products: ' . $conn->error . ' Query: ' . $produk_query_string);
}

// Cek apakah tabel video_iklan ada dan ambil data video
$video = null;
$check_video_table = $conn->query("SHOW TABLES LIKE 'video_iklan'");
if ($check_video_table && $check_video_table->num_rows > 0) {
    $video = $conn->query("SELECT * FROM video_iklan ORDER BY id DESC");
    if ($video === false) {
        die('Error fetching videos: ' . $conn->error);
    }
}

// --- Ambil semua kategori dari database untuk menu filter ---
$semua_kategori = $conn->query("SELECT DISTINCT nama_kategori FROM kategori ORDER BY nama_kategori ASC");
if ($semua_kategori === false) {
    die('Error fetching categories: ' . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quinn Fashion - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <header class="header">
        <div class="container">
            <h1>Selamat Datang di Quinn Fashion</h1>
        </div>
    </header>

    <main class="container my-5">
        <section class="search-form-section mb-5">
            <div class="search-form">
                <form method="get" class="d-flex flex-column flex-md-row align-items-center justify-content-center">
                    <input type="text" name="q" class="form-control me-md-3 mb-3 mb-md-0" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
                    <?php if (!empty($kategori_filter)): ?>
                        <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori_filter) ?>">
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
        </section>

        <section class="kategori-menu-section mb-5">
            <div class="card p-4 shadow-sm rounded-3">
                <h3 class="text-center mb-4 text-primary">Kategori Produk</h3>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="index.php?q=<?= htmlspecialchars($search) ?>" class="btn btn-outline-secondary <?= empty($kategori_filter) ? 'active' : '' ?>">Semua Produk</a>
                    <?php while ($kat = $semua_kategori->fetch_assoc()) : ?>
                        <?php
                            $active_class = ($kategori_filter == $kat['nama_kategori']) ? 'active' : '';
                            // Pastikan parameter pencarian tetap ada saat mengklik kategori
                            $kategori_link = "index.php?kategori=" . urlencode($kat['nama_kategori']);
                            if (!empty($search)) {
                                $kategori_link .= "&q=" . urlencode($search);
                            }
                        ?>
                        <a href="<?= $kategori_link ?>" class="btn btn-outline-primary <?= $active_class ?>">
                            <?= htmlspecialchars($kat['nama_kategori']) ?>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

        <?php if ($video && $video->num_rows > 0): ?>
            <section class="video-section text-center mb-5">
                <h2>Video Iklan</h2>
                <div class="video-grid">
                    <?php while ($v = $video->fetch_assoc()) : ?>
                        <div class="video-wrapper">
                            <h4><?= htmlspecialchars($v['judul']) ?></h4>
                            <?php
                            $file_video = htmlspecialchars($v['file_video']);
                            $is_external_video = filter_var($file_video, FILTER_VALIDATE_URL);
                            ?>
                            <?php if ($is_external_video): ?>
                                <div class="video-responsive">
                                    <iframe src="<?= $file_video ?>" title="<?= htmlspecialchars($v['judul']) ?>"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                </div>
                            <?php else: ?>
                                <video width="100%" controls class="img-fluid rounded">
                                    <source src="../uploads/<?= $file_video ?>" type="video/mp4">
                                    Browser Anda tidak mendukung tag video.
                                </video>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>

        <section class="produk-section">
            <?php if (!empty($kategori_filter)): ?>
                <h3 class="text-center mb-4 text-secondary">Menampilkan Produk Kategori: "<?= htmlspecialchars($kategori_filter) ?>"</h3>
            <?php elseif (!empty($search)): ?>
                <h3 class="text-center mb-4 text-secondary">Hasil Pencarian untuk: "<?= htmlspecialchars($search) ?>"</h3>
            <?php else: ?>
                <h3 class="text-center mb-4 text-secondary">Semua Produk</h3>
            <?php endif; ?>

            <div class="produk-container">
                <?php if ($produk->num_rows > 0): ?>
                    <?php while ($p = $produk->fetch_assoc()) : ?>
                        <div class="produk-card">
                            <img src="../uploads/<?= htmlspecialchars($p['gambar']) ?>"
                                onerror="this.onerror=null;this.src='./default.jpg';"
                                alt="<?= htmlspecialchars($p['nama_barang']) ?>" class="img-fluid">

                            <div class="card-body p-4 pt-0">
                                <h4 class="card-title"><?= htmlspecialchars($p['nama_barang']) ?></h4>
                                <p class="card-text"><strong>Rp <?= number_format($p['harga'], 0, ',', '.') ?></strong></p>
                                <p class="card-text"><small>Kategori: <?= htmlspecialchars($p['kategori']) ?></small></p>
                                <a href="detail.php?id=<?= htmlspecialchars($p['id']) ?>" class="btn btn-info d-block mb-2">Lihat Detail</a>

                                <div class="ecommerce-links">
                                    <?php if (!empty($p['link_shopee'])): ?>
                                        <a href="<?= htmlspecialchars($p['link_shopee']) ?>" target="_blank" class="ecommerce-icon shopee-icon" title="Beli di Shopee">
                                            <i class="fas fa-shopping-bag"></i> </a>
                                    <?php endif; ?>
                                    <?php if (!empty($p['link_tokopedia'])): ?>
                                        <a href="<?= htmlspecialchars($p['link_tokopedia']) ?>" target="_blank" class="ecommerce-icon tokopedia-icon" title="Beli di Tokopedia">
                                            <i class="fas fa-store"></i> </a>
                                    <?php endif; ?>
                                    <?php if (!empty($p['link_lazada'])): ?>
                                        <a href="<?= htmlspecialchars($p['link_lazada']) ?>" target="_blank" class="ecommerce-icon lazada-icon" title="Beli di Lazada">
                                            <i class="fas fa-box"></i> </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="col-12 text-center alert alert-info">Produk tidak ditemukan untuk kriteria ini.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Quinn Fashion. Semua Hak Dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi fade-in untuk elemen utama
            const fadeInElements = document.querySelectorAll('.produk-card, .video-wrapper, .kategori-menu-section .card');
            fadeInElements.forEach((el, index) => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
                    el.style.opacity = 1;
                    el.style.transform = 'translateY(0)';
                }, 100 * index); // Staggered fade-in
            });
        });
    </script>

</body>

</html>