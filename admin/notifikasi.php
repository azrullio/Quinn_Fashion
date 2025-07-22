<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';

// Tambah notifikasi manual (opsional)
if (isset($_POST['submit'])) {
    $pesan = $_POST['pesan'];
    $conn->query("INSERT INTO notifikasi (pesan) VALUES ('$pesan')");
    header("Location: notifikasi.php?success=1");
}

// Ambil semua notifikasi
$notif = $conn->query("SELECT * FROM notifikasi ORDER BY waktu DESC");
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<div class="content">
    <h2>Notifikasi Sistem</h2>

    <?php if (isset($_GET['success'])) echo "<p style='color:green;'>Notifikasi berhasil ditambahkan!</p>"; ?>

    <form method="POST">
        <textarea name="pesan" rows="3" cols="50" placeholder="Tambah notifikasi manual..."></textarea><br>
        <button name="submit">Tambah Notifikasi</button>
    </form>

    <br><hr><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Pesan</th>
            <th>Waktu</th>
        </tr>
        <?php $no = 1; while ($row = $notif->fetch_assoc()) : ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['pesan'] ?></td>
            <td><?= $row['waktu'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
