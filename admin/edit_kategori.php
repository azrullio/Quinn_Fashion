<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include 'inc/db.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data kategori berdasarkan ID
$query = mysqli_query($conn, "SELECT * FROM kategori WHERE id = $id");
$kategori = mysqli_fetch_assoc($query);

if (!$kategori) {
    echo "<p>Kategori tidak ditemukan.</p>";
    exit;
}

// Proses update kategori
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_baru = trim($_POST['nama_kategori']);

    // Cek apakah nama sudah digunakan kategori lain
    $cek = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori='$nama_baru' AND id != $id");
    if (mysqli_num_rows($cek) > 0) {
        $error = "⚠️ Nama kategori sudah digunakan!";
    } else {
        mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama_baru' WHERE id = $id");
        header("Location: kategori.php?edit=1");
        exit;
    }
}
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>

<div class="main-content">
    <h2>Edit Kategori</h2>

    <?php if (isset($error)): ?>
        <div class="alert warning"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nama_kategori" value="<?= htmlspecialchars($kategori['nama_kategori']) ?>" required>
        <button type="submit">💾 Simpan Perubahan</button>
    </form>

    <p><a href="kategori.php">← Kembali ke Manajemen Kategori</a></p>
</div>

<style>
    .main-content {
        padding: 2rem;
        font-family: 'Segoe UI', sans-serif;
        background-color: #f9f9f9;
        min-height: 100vh;
    }

    .alert.warning {
        background-color: #fff3cd;
        color: #856404;
        padding: 10px 15px;
        border-radius: 6px;
        margin-bottom: 1rem;
    }

    form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    form input[type="text"] {
        flex: 1;
        padding: 12px 16px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    form button {
        background-color: #0d6efd;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
    }

    form button:hover {
        background-color: #0b5ed7;
    }

    a {
        color: #198754;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
