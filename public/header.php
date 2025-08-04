<?php
include '../admin/inc/db.php';

// Ambil query pencarian dan filter kategori dari URL
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$kategori_filter = isset($_GET['kategori_id']) ? trim($_GET['kategori_id']) : '';

// Ambil data kategori untuk menu
$kategori = mysqli_query($conn, "SELECT id AS kategori_id, nama_kategori FROM kategori ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Beranda Produk - Quinn Fashion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="main-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="welcome-text mb-0">Selamat Datang di Quinn Fashion</h1>
            <form method="GET" class="search-form d-flex" action="index.php">
                <input type="text" name="q" class="form-control me-2" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
                <input type="hidden" name="kategori_id" value="<?= htmlspecialchars($kategori_filter) ?>" />
                <button class="btn btn-search" type="submit">Cari</button>
                <button id="darkToggle">
  <i id="themeIcon" class="fa-solid fa-moon"></i>
</button>

            </form>
        </div>
    </header>

    <div class="container mt-4">
        <div class="category-filter">
            <div class="btn-group">
                <?php
                // Link "Semua" kategori, tetap kirim q supaya pencarian tidak hilang saat reset kategori
                $url_semua = "index.php";
                if ($search !== '') {
                    $url_semua .= "?q=" . urlencode($search);
                }
                ?>
                <a href="<?= $url_semua ?>" class="btn btn-outline-primary <?= ($kategori_filter == '') ? 'active' : '' ?>">Semua</a>

                <?php while ($kat = mysqli_fetch_assoc($kategori)) : ?>
                    <?php
                    // Link kategori dengan mempertahankan pencarian $search juga
                    $url_kategori = "index.php?kategori_id=" . urlencode($kat['kategori_id']);
                    if ($search !== '') {
                        $url_kategori .= "&q=" . urlencode($search);
                    }
                    $is_active = ($kategori_filter == $kat['kategori_id']) ? 'active' : '';
                    ?>
                    <a href="<?= $url_kategori ?>" class="btn btn-outline-primary <?= $is_active ?>">
                        <?= htmlspecialchars($kat['nama_kategori']) ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- SCRIPT DARK MODE -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("darkToggle");
    const icon = document.getElementById("themeIcon");

    function updateIcon() {
        if (document.body.classList.contains("dark-mode")) {
            icon.setAttribute("class", "fa-solid fa-sun");
        } else {
            icon.setAttribute("class", "fa-solid fa-moon");
        }
    }

    // Cek localStorage dan atur mode awal
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark-mode");
    }

    // Set ikon awal sesuai mode
    updateIcon();

    // Event tombol klik untuk toggle
    toggleBtn.addEventListener("click", function () {
        document.body.classList.toggle("dark-mode");

        // Simpan preferensi ke localStorage
        const newTheme = document.body.classList.contains("dark-mode") ? "dark" : "light";
        localStorage.setItem("theme", newTheme);

        // Update ikon
        updateIcon();
    });
});
</script>

</body>
</html>