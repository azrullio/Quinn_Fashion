<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah ada pesan sukses dari penghapusan
$pesan_hapus = "";

include 'inc/db.php';

// Tambah kategori
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama']);
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

// Ambil semua kategori
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>
<?php if (isset($_GET['add'])) echo "<p class='message success'>Kategori berhasil ditambahkan!</p>"; ?>
<?php if (isset($_GET['hapus'])) echo "<p class='message error'>Kategori berhasil dihapus!</p>"; ?>
<?php if (isset($_GET['exists'])) echo "<p class='message warning'>Kategori sudah ada!</p>"; ?>
<?php if (isset($_SESSION['hapus_berhasil'])): ?>
    <div class="alert error">🗑️ Kategori berhasil dihapus!</div>
    <?php unset($_SESSION['hapus_berhasil']); ?>
<?php endif; ?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>

<style>
    .main-content {
        padding: 2rem;
        font-family: 'Segoe UI', sans-serif;
        background-color: #f9f9f9;
    }

    .main-content h2 {
        font-size: 28px;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .alert {
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 1rem;
        font-weight: bold;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
    }

    .warning {
        background-color: #fff3cd;
        color: #856404;
    }

    form {
        display: flex;
        gap: 10px;
        margin-bottom: 2rem;
    }

    form input[type="text"] {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    form button {
        background-color: #1a365d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }

    form button:hover {
        background-color: #45a049;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    table th {
        background-color: #1a365d;
        color: white;
        text-align: left;
        padding: 12px;
    }

    table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }

    table tr:hover {
        background-color: #f1f1f1;
    }

    table a {
        color: #d32f2f;
        text-decoration: none;
    }

    table a:hover {
        text-decoration: underline;
    }
</style>


<div class="main-content">
    <h2>Manajemen Kategori</h2>

    <?php if (isset($_GET['add'])): ?>
        <div class="alert success">✅ Kategori berhasil ditambahkan!</div>
    <?php endif; ?>
    <?php if (isset($_GET['hapus'])): ?>
        <div class="alert error">🗑️ Kategori berhasil dihapus!</div>
    <?php endif; ?>
    <?php if (isset($_GET['exists'])): ?>
        <div class="alert warning">⚠️ Kategori sudah ada!</div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nama" placeholder="Contoh: Dress, Hijab, Atasan" required>
        <button name="tambah">+ Tambah Kategori</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($kategori)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td>
                    <a href="hapus_kategori.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

