<?php
session_start();
include 'inc/db.php';

// Pastikan hanya admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Validasi ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $cek = mysqli_query($conn, "SELECT * FROM kategori WHERE id = $id");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "DELETE FROM kategori WHERE id = $id");
        $_SESSION['hapus_berhasil'] = true;
    }
}

// Redirect kembali ke kategori.php
header("Location: kategori.php");
exit;
