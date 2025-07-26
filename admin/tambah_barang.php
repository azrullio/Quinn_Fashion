<?php
include 'inc/db.php';
include 'inc/sidebar.php';

// Ambil kategori
$kategori_result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_barang'];
    $kategori_nama = $_POST['kategori'];
    $harga = str_replace('.', '', $_POST['harga']);
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $link_shopee = $_POST['link_shopee'];
    $link_lazada = $_POST['link_lazada'];
    $link_tokopedia = $_POST['link_tokopedia'];
    $promo = $_POST['promo'] ?? '';

    $result = mysqli_query($conn, "SELECT id FROM kategori WHERE nama_kategori = '$kategori_nama'");
    $row = mysqli_fetch_assoc($result);
    $kategori_id = $row['id'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "img/" . $gambar);

    mysqli_query($conn, "
        INSERT INTO barang (
            nama_barang, kategori_id, harga, stok, gambar, deskripsi,
            link_shopee, link_lazada, link_tokopedia, promo
        ) VALUES (
            '$nama', '$kategori_id', '$harga', '$stok', '$gambar', '$deskripsi',
            '$link_shopee', '$link_lazada', '$link_tokopedia', '$promo'
        )
    ");

    header("Location: index.php");
    exit;
}
?>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f0f2f5;
        font-family: 'Segoe UI', sans-serif;
    }

    .main-content {
        margin-left: 240px;
        padding: 40px 20px;
        min-height: 100vh;
    }

    .edit-form {
        max-width: 700px;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        margin: auto;
        transition: all 0.3s ease-in-out;
    }

    .edit-form:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .form-title {
        text-align: center;
        margin-bottom: 30px;
        font-weight: 600;
        color: #333;
    }

    .form-label {
        font-weight: 500;
        color: #444;
    }

    .form-control, .form-select {
        border-radius: 6px;
    }

    .btn-success {
        width: 100%;
        padding: 10px;
        font-weight: 600;
        font-size: 16px;
        transition: background-color 0.2s ease;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 20px 10px;
        }

        .edit-form {
            padding: 20px;
        }
    }
</style>

<div class="main-content">
    <div class="edit-form">
        <h4 class="form-title">Tambah Barang</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($k = mysqli_fetch_assoc($kategori_result)) : ?>
                        <option value="<?= htmlspecialchars($k['nama_kategori']) ?>">
                            <?= htmlspecialchars($k['nama_kategori']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="text" name="harga" id="harga" class="form-control" required oninput="formatRupiah(this)">
            </div>

            <div class="mb-3">
                <label class="form-label">Promo</label>
                <input type="text" name="promo" class="form-control" placeholder="Contoh: Gratis Ongkir, Beli 1 Gratis 1">
            </div>

            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Barang</label>
                <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Link Shopee</label>
                <input type="url" name="link_shopee" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Link Lazada</label>
                <input type="url" name="link_lazada" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Link Tokopedia</label>
                <input type="url" name="link_tokopedia" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Gambar</label>
                <input type="file" name="gambar" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Tambah Barang</button>
        </form>
    </div>
</div>

<script>
function formatRupiah(input) {
    let angka = input.value.replace(/[^,\d]/g, "").toString();
    let split = angka.split(",");
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    input.value = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
}
</script>
