<?php
include '../admin/inc/db.php'; // Pastikan jalur sudah benar

// Ambil query pencarian dan filter kategori dari URL
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$kategori_filter = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

// Build kondisi WHERE untuk produk
$conditions = [];
if (!empty($search)) {
    $conditions[] = "nama_barang LIKE '%" . $conn->real_escape_string($search) . "%'";
}
if (!empty($kategori_filter)) {
    $conditions[] = "kategori = '" . $conn->real_escape_string($kategori_filter) . "'";
}
$where_clause = '';
if (!empty($conditions)) {
    $where_clause = " WHERE " . implode(" AND ", $conditions);
}

// Query produk
$produk_query_string = "SELECT * FROM barang " . $where_clause . " ORDER BY id DESC";
$produk = $conn->query($produk_query_string);
if ($produk === false) {
    die('Error fetching products: ' . $conn->error . ' Query: ' . $produk_query_string);
}

// Ambil semua kategori untuk menu filter
$semua_kategori = $conn->query("SELECT DISTINCT nama_kategori FROM kategori ORDER BY nama_kategori ASC");
if ($semua_kategori === false) {
    die('Error fetching categories: ' . $conn->error);
}

// Ambil data slider gambar iklan
$slider = $conn->query("SELECT * FROM slider_iklan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Quinn Fashion - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        .carousel-item img {
            max-height: 400px;
            object-fit: cover;
            width: 100%;
        }

        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 0.3rem;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container">
            <h1>Selamat Datang di Quinn Fashion</h1>
        </div>
    </header>

    <main class="container my-5">

        <!-- ✅ Slider Otomatis Bootstrap -->
        <?php if ($slider && $slider->num_rows > 0) : ?>
            <section class="slider-section text-center mb-5">
                <h2></h2>
                <div id="carouselIklan" class="carousel slide" data-bs-ride="carousel"  data-bs-interval="5000">
                    <div class="carousel-inner">
                        <?php $active = 'active'; ?>
                        <?php while ($s = $slider->fetch_assoc()) : ?>
                            <div class="carousel-item <?= $active ?>">
                                <img src="../uploads/<?= htmlspecialchars($s['file_gambar']) ?>" class="d-block w-100 rounded"
                                    alt="<?= htmlspecialchars($s['judul']) ?>" />
                                <div class="carousel-caption d-none d-md-block">
                                    <h5><?= htmlspecialchars($s['judul']) ?></h5>
                                </div>
                            </div>
                            <?php $active = ''; ?>
                        <?php endwhile; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIklan"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Sebelumnya</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselIklan"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Berikutnya</span>
                    </button>
                </div>
            </section>
        <?php endif; ?>
        <!-- ✅ Akhir slider -->

        <section class="search-form-section mb-5">
            <div class="search-form">
                <form method="get" class="d-flex flex-column flex-md-row align-items-center justify-content-center">
                    <input type="text" name="q" class="form-control me-md-3 mb-3 mb-md-0" placeholder="Cari produk..."
                        value="<?= htmlspecialchars($search) ?>" />
                    <?php if (!empty($kategori_filter)) : ?>
                        <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori_filter) ?>" />
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
        </section>

        <section class="kategori-menu-section mb-5">
            <div class="card p-4 shadow-sm rounded-3">
                <h3 class="text-center mb-4 text-primary">Kategori Produk</h3>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="index.php?q=<?= htmlspecialchars($search) ?>"
                        class="btn btn-outline-secondary <?= empty($kategori_filter) ? 'active' : '' ?>">Semua Produk</a>
                    <?php while ($kat = $semua_kategori->fetch_assoc()) : ?>
                        <?php
                        $active_class = ($kategori_filter == $kat['nama_kategori']) ? 'active' : '';
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

        <section class="produk-section">
            <?php if (!empty($kategori_filter)) : ?>
                <h3 class="text-center mb-4 text-secondary">Menampilkan Produk Kategori: "<?= htmlspecialchars($kategori_filter) ?>"
                </h3>
            <?php elseif (!empty($search)) : ?>
                <h3 class="text-center mb-4 text-secondary">Hasil Pencarian untuk: "<?= htmlspecialchars($search) ?>"
                </h3>
            <?php else : ?>
                <h3 class="text-center mb-4 text-secondary">Semua Produk</h3>
            <?php endif; ?>

            <div class="produk-container">
                <?php if ($produk->num_rows > 0) : ?>
                    <?php while ($p = $produk->fetch_assoc()) : ?>
                        <div class="produk-card">
                            <img src="../uploads/<?= htmlspecialchars($p['gambar']) ?>"
                                onerror="this.onerror=null;this.src='./default.jpg';"
                                alt="<?= htmlspecialchars($p['nama_barang']) ?>" class="img-fluid" />

                            <div class="card-body p-4 pt-0">
                                <h4 class="card-title"><?= htmlspecialchars($p['nama_barang']) ?></h4>
                                <p class="card-text"><strong>Rp <?= number_format($p['harga'], 0, ',', '.') ?></strong></p>
                                <p class="card-text"><small>Kategori: <?= htmlspecialchars($p['kategori']) ?></small></p>
                                <a href="detail.php?id=<?= htmlspecialchars($p['id']) ?>" class="btn btn-info d-block mb-2">Lihat
                                    Detail</a>

                                <div class="ecommerce-links">
                                    <?php if (!empty($p['link_shopee'])) : ?>
                                        <a href="<?= htmlspecialchars($p['link_shopee']) ?>" target="_blank"
                                            class="ecommerce-icon shopee-icon" title="Beli di Shopee">
                                            <i class="fas fa-shopping-bag"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($p['link_tokopedia'])) : ?>
                                        <a href="<?= htmlspecialchars($p['link_tokopedia']) ?>" target="_blank"
                                            class="ecommerce-icon tokopedia-icon" title="Beli di Tokopedia">
                                            <i class="fas fa-store"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($p['link_lazada'])) : ?>
                                        <a href="<?= htmlspecialchars($p['link_lazada']) ?>" target="_blank"
                                            class="ecommerce-icon lazada-icon" title="Beli di Lazada">
                                            <i class="fas fa-box"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else : ?>
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
        document.addEventListener('DOMContentLoaded', function () {
            const fadeInElements = document.querySelectorAll('.produk-card, .slider-section, .kategori-menu-section .card');
            fadeInElements.forEach((el, index) => {
                el.style.opacity = 0;
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
                    el.style.opacity = 1;
                    el.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });
    </script>
</body>

</html>
