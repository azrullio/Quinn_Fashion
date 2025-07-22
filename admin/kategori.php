<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';

// Tambah kategori
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $conn->query("INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
    header("Location: kategori.php?add=1");
}

// Hapus kategori
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM kategori WHERE id=$id");
    header("Location: kategori.php?hapus=1");
}

// Ambil semua kategori
$kategori = $conn->query("SELECT * FROM kategori ORDER BY id DESC");
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<div class="content">
    <h2>Manajemen Kategori</h2>

    <?php if (isset($_GET['add'])) echo "<p style='color:green;'>Kategori berhasil ditambahkan!</p>"; ?>
    <?php if (isset($_GET['hapus'])) echo "<p style='color:red;'>Kategori berhasil dihapus!</p>"; ?>

    <form method="POST">
        <input type="text" name="nama" placeholder="Nama kategori" required>
        <button name="tambah">Tambah</button>
    </form>

    <br>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = $kategori->fetch_assoc()) : ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['nama_kategori'] ?></td>
            <td>
                <!-- (Opsi) Buat edit jika ingin -->
                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
