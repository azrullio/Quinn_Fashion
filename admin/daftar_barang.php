<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';

// Ambil data produk
$sql = "SELECT produk.*, kategori.nama_kategori
        FROM produk 
        JOIN kategori ON produk.kategori_id = kategori.id
        ORDER BY produk.created_at DESC";
$produk = $conn->query($sql);

// Proses hapus jika ada ID di URL
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM produk WHERE id=$id");
    header("Location: daftar_barang.php?deleted=1");
}
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<div class="content">
    <h2>Daftar Barang</h2>

    <?php if (isset($_GET['success'])) echo "<p style='color:green;'>Produk berhasil ditambahkan!</p>"; ?>
    <?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>Produk berhasil dihapus!</p>"; ?>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = $produk->fetch_assoc()) : ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><img src="../uploads/<?= $row['gambar'] ?>" width="70"></td>
            <td><?= $row['nama_produk'] ?></td>
            <td><?= $row['nama_kategori'] ?></td>
            <td>Rp <?= number_format($row['harga']) ?></td>
            <td>
                <a href="edit_barang.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
