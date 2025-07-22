<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';

// Proses tambah video
if (isset($_POST['upload'])) {
    $judul = $_POST['judul'];
    $link  = $_POST['link'];
    $video_file = $_FILES['video']['name'];
    $tmp = $_FILES['video']['tmp_name'];
    $path = '../uploads/' . $video_file;

    if (!empty($video_file)) {
        move_uploaded_file($tmp, $path);
        $save = $video_file;
    } elseif (!empty($link)) {
        $save = $link;
    } else {
        $save = null;
    }

    if ($save !== null) {
        $conn->query("INSERT INTO video_iklan (judul, file_video) VALUES ('$judul', '$save')");
        header("Location: video.php?success=1");
    }
}

// Proses hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM video_iklan WHERE id=$id");
    header("Location: video.php?deleted=1");
}

// Ambil semua video
$video = $conn->query("SELECT * FROM video_iklan ORDER BY id DESC");
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<div class="content">
    <h2>Manajemen Video Iklan</h2>

    <?php if (isset($_GET['success'])) echo "<p style='color:green;'>Video berhasil ditambahkan!</p>"; ?>
    <?php if (isset($_GET['deleted'])) echo "<p style='color:red;'>Video berhasil dihapus!</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Judul Video</label><br>
        <input type="text" name="judul" required><br><br>

        <label>Upload File (.mp4)</label><br>
        <input type="file" name="video" accept="video/*"><br><br>

        <label>Atau Link YouTube</label><br>
        <input type="text" name="link" placeholder="https://youtube.com/..."><br><br>

        <button name="upload">Tambah Video</button>
    </form>

    <br><hr><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Video</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = $video->fetch_assoc()) : ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['judul']) ?></td>
            <td>
                <?php if (str_contains($row['file_video'], 'http')): ?>
                    <iframe width="200" height="120" src="<?= htmlspecialchars($row['file_video']) ?>" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <video width="200" controls>
                        <source src="../uploads/<?= htmlspecialchars($row['file_video']) ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
            </td>
            <td><a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus video ini?')">Hapus</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
