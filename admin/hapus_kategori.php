<?php
session_start();
include 'inc/db.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Validasi dan sanitasi ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // pastikan ID berupa angka

    // Cek apakah kategori benar-benar ada
    $cek = mysqli_query($conn, "SELECT * FROM kategori WHERE id = $id");

    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "DELETE FROM kategori WHERE id = $id");
        header("Location: kategori.php?hapus=1");
        exit;
    } else {
        // Jika kategori tidak ditemukan
        header("Location: kategori.php?notfound=1");
        exit;
    }
} else {
    // Jika tidak ada ID yang dikirim
    header("Location: kategori.php");
    exit;
}
?>

