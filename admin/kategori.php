<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';

// Tambah kategori
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama']);
    // Cek duplikat berdasarkan nama kategori
    $cek = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori='$nama'");
    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
        header("Location: kategori.php?add=1");
    } else {
        header("Location: kategori.php?exists=1");
    }
    exit;
}

// Hapus kategori
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM kategori WHERE id=$id");
    header("Location: kategori.php?hapus=1");
    exit;
}

// Ambil semua kategori, urut berdasarkan nama kategori
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<div class="main-content">
    <h2>Manajemen Kategori</h2>

    <?php if (isset($_GET['add'])) echo "<p style='color:green;'>Kategori berhasil ditambahkan!</p>"; ?>
    <?php if (isset($_GET['hapus'])) echo "<p style='color:red;'>Kategori berhasil dihapus!</p>"; ?>
    <?php if (isset($_GET['exists'])) echo "<p style='color:orange;'>Kategori sudah ada!</p>"; ?>

    <form method="POST">
        <input type="text" name="nama" placeholder="Nama kategori baru" required>
        <button name="tambah">Tambah</button>
    </form>

    <br>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($kategori)) : ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
            <td>
                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
