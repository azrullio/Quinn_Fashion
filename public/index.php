<?php
include '../admin/inc/db.php'; // Pastikan jalur sudah benar

$kategori_filter = $_GET['nama_kategori'] ?? '';

$kategori_nama = '';
if (!empty($kategori_filter)) {
    $stmt = $conn->prepare("SELECT nama_kategori FROM kategori WHERE id = ?");
    $stmt->bind_param("i", $kategori_filter);
    $stmt->execute();
    $result_kat = $stmt->get_result();
    if ($row_kat = $result_kat->fetch_assoc()) {
        $kategori_nama = $row_kat['nama_kategori'];
    }
}

// Ambil query pencarian dan filter kategori dari URL
$kategori_filter = isset($_GET['kategori_id']) ? (int)$_GET['kategori_id'] : null;
$search = isset($_GET['q']) ? $_GET['q'] : '';

// Build query awal
$sql_barang = "SELECT * FROM barang";

// Build kondisi WHERE untuk produk
$conditions = [];
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $conditions[] = "(nama_barang LIKE '%$search%' OR deskripsi LIKE '%$search%')";
}
if (!empty($kategori_filter)) {
    $conditions[] = "kategori_id = $kategori_filter";
}

// Gabungkan kondisi menjadi WHERE clause
if (!empty($conditions)) {
    $sql_barang .= " WHERE " . implode(" AND ", $conditions);
}

// Tambahkan urutan
$sql_barang .= " ORDER BY id DESC";

// Eksekusi query produk
$produk = $conn->query($sql_barang);
if ($produk === false) {
    die('Error fetching products: ' . $conn->error . ' Query: ' . $sql_barang);
}

// Ambil semua kategori untuk menu filter
$semua_kategori = $conn->query("
    SELECT DISTINCT k.id AS kategori_id, k.nama_kategori 
    FROM kategori k
    INNER JOIN barang b ON b.kategori_id = k.id
    ORDER BY k.nama_kategori ASC
");
if ($semua_kategori === false) {
    die('Error fetching categories: ' . $conn->error);
}

// Ambil data slider gambar iklan
$slider = $conn->query("SELECT * FROM video_iklan ORDER BY id DESC");
if ($slider === false) {
    die('Error fetching slider data: ' . $conn->error);
}
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
                        <input type="hidden" name="kategori-id" value="<?= htmlspecialchars($kategori_filter) ?>" />
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
        </section>

<section class="kategori-menu-section mb-5">
    <div class="card p-4 shadow-sm rounded-3">
        <h3 class="text-center mb-4 text-primary">Kategori Produk</h3>
        <div class="d-flex flex-wrap justify-content-center gap-2">
            <!-- Tombol Semua Produk -->
            <!-- Tombol Semua Produk -->
<a href="index.php<?= !empty($search) ? '?q=' . urlencode($search) : '' ?>"
   class="btn btn-outline-secondary <?= empty($kategori_filter) ? 'active' : '' ?>">
   Semua Produk
</a>

<!-- Tombol Filter Per Kategori -->
<?php while ($kat = $semua_kategori->fetch_assoc()) : ?>
    <?php
        $is_active = ($kategori_filter == $kat['nama_kategori']) ? 'active' : '';

        // Buat base URL
        $kategori_link = "index.php?kategori_id=" . urlencode($kat['kategori_id']);

        // Tambahkan query pencarian jika ada
        if (!empty($search)) {
            $kategori_link .= "&q=" . urlencode($search);
        }
    ?>
    <a href="<?= $kategori_link ?>" class="btn btn-outline-primary <?= $is_active ?>">
        <?= htmlspecialchars($kat['nama_kategori']) ?>
    </a>
<?php endwhile; ?>



        </div>
    </div>
</section>



        <section class="produk-section">
            <?php if (!empty($kategori_filter)) : ?>
    <?php
    // Ambil nama kategori dari database berdasarkan kategori_id
    $stmt_kat_nama = $conn->prepare("SELECT nama_kategori FROM kategori WHERE id = ?");
    $stmt_kat_nama->bind_param("i", $kategori_filter);
    $stmt_kat_nama->execute();
    $result_kat_nama = $stmt_kat_nama->get_result();
    $nama_kategori_terpilih = '';
    if ($row_kat_nama = $result_kat_nama->fetch_assoc()) {
        $nama_kategori_terpilih = $row_kat_nama['nama_kategori'];
    }
    ?>
    <h3 class="text-center mb-4 text-secondary">
        Menampilkan Produk Kategori: "<strong><?= htmlspecialchars($nama_kategori_terpilih) ?></strong>"
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
                                <p class="card-text"><small>Kategori: <?= htmlspecialchars($p['kategori_id']) ?></small></p>
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

