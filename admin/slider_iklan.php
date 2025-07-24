<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';

// Proses tambah slider
if (isset($_POST['upload'])) {
    $judul = $conn->real_escape_string($_POST['judul']);
    $gambar_file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $path = '../uploads/' . basename($gambar_file);

    if (!empty($gambar_file)) {
        // Validasi file gambar (optional)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($tmp);

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($tmp, $path)) {
                $conn->query("INSERT INTO slider_iklan (judul, file_gambar) VALUES ('$judul', '$gambar_file')");
                header("Location: slider_iklan.php?success=1");
                exit;
            } else {
                $error = "Gagal mengupload file.";
            }
        } else {
            $error = "Format file tidak didukung. Gunakan JPG, PNG, atau GIF.";
        }
    } else {
        $error = "Silakan pilih file gambar.";
    }
}

// Proses hapus slider
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    // Ambil nama file dulu untuk dihapus dari folder
    $result = $conn->query("SELECT file_gambar FROM slider_iklan WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_to_delete = '../uploads/' . $row['file_gambar'];
        if (file_exists($file_to_delete)) {
            unlink($file_to_delete);
        }
    }
    $conn->query("DELETE FROM slider_iklan WHERE id=$id");
    header("Location: slider_iklan.php?deleted=1");
    exit;
}

// Ambil semua slider
$slider = $conn->query("SELECT * FROM video_iklan ORDER BY id DESC");
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>

<div class="main-content">
    <h2>Manajemen Slider Iklan</h2>

    <?php if (isset($_GET['success'])): ?>
        <div style="color: green;">Slider berhasil ditambahkan!</div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div style="color: red;">Slider berhasil dihapus!</div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div style="color: red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="margin-bottom: 2rem;">
        <label>Judul Slide</label><br>
        <input type="text" name="judul" required style="width: 300px; padding: 8px; margin-bottom: 1rem;"><br>

        <label>Upload Gambar (.jpg, .png, .gif)</label><br>
        <input type="file" name="gambar" accept="image/*" required style="margin-bottom: 1rem;"><br>

        <button type="submit" name="upload" style="padding: 10px 20px;">Tambah Slider</button>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; max-width: 800px;">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = $slider->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><img src="../uploads/<?= htmlspecialchars($row['file_gambar']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" style="max-width: 200px;"></td>
                    <td>
                        <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus slide ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
