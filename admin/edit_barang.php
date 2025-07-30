<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include 'inc/db.php';

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM barang WHERE id=$id");
$data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $shopee = $_POST['shopee'];
    $tokopedia = $_POST['tokopedia'];
    $lazada = $_POST['lazada'];
    $promo = $_POST['promo'] ?? '';

    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "img/" . $gambar);
    } else {
        $gambar = $data['gambar'];
    }

    $update = mysqli_query($conn, "UPDATE barang SET 
        nama_barang='$nama',
        kategori_id='$kategori',
        harga='$harga',
        stok='$stok',
        gambar='$gambar',
        deskripsi='$deskripsi',
        link_shopee='$shopee',
        link_tokopedia='$tokopedia',
        link_lazada='$lazada',
        promo='$promo'
    WHERE id=$id");

    // ⛔️ HARUS dilakukan sebelum HTML apa pun muncul!
    header("Location: index.php");
    exit;
}

// Sidebar dan tampilan HTML hanya ditampilkan jika bukan POST
include 'inc/sidebar.php';
?>

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .main-content {
        margin-left: 240px;
        padding: 30px;
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .edit-form {
        max-width: 600px;
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        margin: auto;
    }

    .edit-form img {
        max-width: 150px;
        border-radius: 6px;
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: 500;
    }

    .form-control, .form-select {
        margin-bottom: 15px;
    }

    .form-title {
        text-align: center;
        margin-bottom: 25px;
    }

    button[type="submit"] {
        width: 100%;
    }
</style>

<div class="main-content">
    <div class="edit-form">
        <h4 class="form-title">Edit Barang</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" class="form-control" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang']) ?>" required>
            </div>

<?php
$kategori_list = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>

<div class="mb-3">
    <label class="form-label">Kategori</label>
    <select name="kategori" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>
        <?php while ($k = mysqli_fetch_assoc($kategori_list)) : ?>
            <option value="<?= $k['id'] ?>" <?= $data['kategori_id'] == $k['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($k['nama_kategori']) ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" class="form-control" name="harga" value="<?= htmlspecialchars($data['harga']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Promo</label>
                <input type="text" class="form-control" name="promo" value="<?= htmlspecialchars($data['promo']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" class="form-control" name="stok" value="<?= htmlspecialchars($data['stok']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="deskripsi" rows="4"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Link Shopee</label>
                <input type="text" class="form-control" name="shopee" value="<?= htmlspecialchars($data['link_shopee']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Link Tokopedia</label>
                <input type="text" class="form-control" name="tokopedia" value="<?= htmlspecialchars($data['link_tokopedia']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Link Lazada</label>
                <input type="text" class="form-control" name="lazada" value="<?= htmlspecialchars($data['link_lazada']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Saat Ini</label><br>
                <img src="img/<?= htmlspecialchars($data['gambar']) ?>" alt="Gambar Barang">
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Gambar Baru (opsional)</label>
                <input type="file" class="form-control" name="gambar">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
