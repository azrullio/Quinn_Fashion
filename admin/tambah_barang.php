<?php
include 'inc/db.php';   
include 'inc/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $link_shopee = $_POST['link_shopee'];
    $link_lazada = $_POST['link_lazada'];
    $link_tokopedia = $_POST['link_tokopedia'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    move_uploaded_file($tmp, "img/" . $gambar);

    mysqli_query($conn, "INSERT INTO barang (nama_barang, kategori, harga, stok, gambar, deskripsi, link_shopee, link_lazada, link_tokopedia)
        VALUES ('$nama', '$kategori', '$harga', '$stok', '$gambar', '$deskripsi', '$link_shopee', '$link_lazada', '$link_tokopedia')");

    header("Location: index.php");
    exit;
}
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
        <h4 class="form-title">Tambah Barang</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <input type="text" name="kategori" class="form-control" placeholder="Contoh: Baju, Topi" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" required>
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
