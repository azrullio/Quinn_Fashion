<?php
include 'inc/db.php';

$tanggal = $_POST['tanggal'];
$keterangan = $_POST['keterangan'];
$nominal = $_POST['nominal'];
$jenis = $_POST['jenis'];

mysqli_query($conn, "INSERT INTO keuangan (tanggal, keterangan, nominal, jenis) VALUES ('$tanggal', '$keterangan', '$nominal', '$jenis')");

header("Location: keuangan.php");
