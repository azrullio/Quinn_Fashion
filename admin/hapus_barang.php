<?php
include 'inc/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ubah ke integer untuk mencegah SQL injection

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("DELETE FROM barang WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Sukses menghapus
        header("Location: index.php?status=deleted");
        exit();
    } else {
        // Gagal menghapus
        echo "Gagal menghapus data: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID tidak ditemukan!";
}

$conn->close();
?>
