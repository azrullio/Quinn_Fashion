<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';
include 'inc/sidebar.php';

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
        margin-left: 250px; /* agar tidak numpuk sidebar */
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
        position: relative;
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

    .badge-promo {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #dc3545;
        color: white;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 8px;
        z-index: 10;
        text-transform: uppercase;
        box-shadow: 0 0 6px rgba(220, 53, 69, 0.7);
    }

    h4 {
        color: #333;
        font-weight: 600;
        margin-top: 2rem;
        border-left: 4px solid #0d6efd;
        padding-left: 0.5rem;
    }

    .btn-outline-danger:hover,
    .btn-outline-primary:hover,
    .btn-outline-success:hover {
        transform: scale(1.05);
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

    foreach ($kategoriBarang as $kategori => $items): ?>
        <h4><?= htmlspecialchars($kategori) ?></h4>
        <div class="scrolling-wrapper">
        <?php foreach ($items as $row): 
            $id = $row['id'];
            $deskripsi = htmlspecialchars($row['deskripsi']);
            $potong = mb_substr($deskripsi, 0, 100);
            $panjang = mb_strlen($deskripsi) > 100;
        ?>
            <div class="card product-card">
                <?php if (!empty(trim($row['promo']))): ?>
                    <span class="badge-promo">PROMO</span>
                <?php endif; ?>

                <img src="img/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top" alt="Gambar">
                <span class="badge-kategori"><?= htmlspecialchars($kategori) ?></span>

                <div class="card-body">
                    <h5 class="card-title text-truncate"><?= htmlspecialchars($row['nama_barang']) ?></h5>
                    <p class="card-text mb-1 text-muted">Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
                    <p class="card-text mb-1"><strong>Stok:</strong> <?= $row['stok'] ?></p>

                    <p class="card-text small mb-2 text-secondary">
                        <span id="short-<?= $id ?>"><?= $potong ?><?= $panjang ? '...' : '' ?></span>
                        <?php if ($panjang): ?>
                            <span id="full-<?= $id ?>" style="display:none;"><?= $deskripsi ?></span>
                            <a href="javascript:void(0);" class="text-primary" onclick="toggleDeskripsi(<?= $id ?>)" id="toggle-<?= $id ?>">Selengkapnya</a>
                        <?php endif; ?>
                    </p>

                    <div class="d-grid gap-2 mb-2">
                        <a href="<?= $row['link_shopee'] ?>" target="_blank" class="btn btn-sm btn-outline-danger">Shopee</a>
                        <a href="<?= $row['link_lazada'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lazada</a>
                        <a href="<?= $row['link_tokopedia'] ?>" target="_blank" class="btn btn-sm btn-outline-success">Tokopedia</a>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="edit_barang.php?id=<?= $id ?>" class="btn btn-warning btn-sm w-50 me-1">Edit</a>
                        <a href="hapus_barang.php?id=<?= $id ?>" onclick="return confirm('Yakin ingin hapus barang ini?')" class="btn btn-danger btn-sm w-50">Hapus</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
function toggleDeskripsi(id) {
    const shortText = document.getElementById('short-' + id);
    const fullText = document.getElementById('full-' + id);
    const toggleBtn = document.getElementById('toggle-' + id);

    const isExpanded = fullText.style.display === 'inline';

    shortText.style.display = isExpanded ? 'inline' : 'none';
    fullText.style.display = isExpanded ? 'none' : 'inline';
    toggleBtn.textContent = isExpanded ? 'Selengkapnya' : 'Sembunyikan';
}
</script>
