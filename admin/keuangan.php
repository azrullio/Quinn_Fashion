<?php
include 'inc/db.php';
include 'inc/sidebar.php';

// Filter berdasarkan tanggal
$where = "";
$filter_label = "Semua Data";
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    switch ($filter) {
        case 'hari':
            $where = "WHERE DATE(tanggal) = CURDATE()";
            $filter_label = "Hari Ini";
            break;
        case 'minggu':
            $where = "WHERE YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)";
            $filter_label = "Minggu Ini";
            break;
        case 'bulan':
            $where = "WHERE MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())";
            $filter_label = "Bulan Ini";
            break;
        case 'tahun':
            $where = "WHERE YEAR(tanggal) = YEAR(CURDATE())";
            $filter_label = "Tahun Ini";
            break;
    }
}

$total_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS total FROM keuangan WHERE jenis = 'pemasukan' " . ($where ? "AND " . substr($where, 6) : "")))['total'] ?? 0;
$total_keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS total FROM keuangan WHERE jenis = 'pengeluaran' " . ($where ? "AND " . substr($where, 6) : "")))['total'] ?? 0;
$saldo = $total_masuk - $total_keluar;

$data = mysqli_query($conn, "SELECT * FROM keuangan $where ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keuangan Olshop</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
            color: #333;
        }
        .main-content {
            padding: 30px;
            margin-left: 250px; /* for sidebar offset */
        }
        h2 {
            margin-bottom: 10px;
        }
        .rekap {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .rekap p {
            background: #fff;
            padding: 15px 20px;
            border-left: 5px solid #3f51b5;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            flex: 1;
            min-width: 200px;
        }
        select, input, button {
            padding: 8px;
            margin: 5px 0;
            font-size: 14px;
        }
        form select {
            width: 200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        th, td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .form {
            margin-top: 30px;
            background: #fff;
            padding: 20px;
            max-width: 500px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }
        .export-buttons {
            margin-top: 20px;
        }
        .export-buttons a {
            display: inline-block;
            background: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            margin-right: 10px;
            border-radius: 4px;
        }
        .export-buttons a:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h2>Rekap Keuangan: <?= $filter_label ?></h2>

        <form method="GET" action="">
            <label>Filter Waktu: </label>
            <select name="filter" onchange="this.form.submit()">
                <option value="">-- Semua --</option>
                <option value="hari" <?= (isset($_GET['filter']) && $_GET['filter'] == 'hari') ? 'selected' : '' ?>>Hari Ini</option>
                <option value="minggu" <?= (isset($_GET['filter']) && $_GET['filter'] == 'minggu') ? 'selected' : '' ?>>Minggu Ini</option>
                <option value="bulan" <?= (isset($_GET['filter']) && $_GET['filter'] == 'bulan') ? 'selected' : '' ?>>Bulan Ini</option>
                <option value="tahun" <?= (isset($_GET['filter']) && $_GET['filter'] == 'tahun') ? 'selected' : '' ?>>Tahun Ini</option>
            </select>
        </form>

        <div class="rekap">
            <p><strong>Total Pemasukan:</strong><br>Rp <?= number_format($total_masuk, 0, ',', '.') ?></p>
            <p><strong>Total Pengeluaran:</strong><br>Rp <?= number_format($total_keluar, 0, ',', '.') ?></p>
            <p><strong>Saldo:</strong><br>Rp <?= number_format($saldo, 0, ',', '.') ?></p>
        </div>

        <table>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Nominal</th>
                <th>Jenis</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($data)) : ?>
            <tr>
                <td><?= $row['tanggal'] ?></td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                <td>Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
                <td><?= ucfirst($row['jenis']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <div class="form">
            <h3>Tambah Transaksi</h3>
            <form action="tambah_keuangan.php" method="POST">
                <label>Tanggal:</label><br>
                <input type="date" name="tanggal" required><br>
                <label>Keterangan:</label><br>
                <input type="text" name="keterangan" placeholder="Keterangan" required><br>
                <label>Nominal:</label><br>
                <input type="number" name="nominal" placeholder="Nominal (Rp)" required><br>
                <label>Jenis:</label><br>
                <select name="jenis">
                    <option value="pemasukan">Pemasukan</option>
                    <option value="pengeluaran">Pengeluaran</option>
                </select><br>
                <button type="submit">Tambah</button>
            </form>
        </div>

        <div class="export-buttons">
            <a href="export_excel.php?filter=<?= $_GET['filter'] ?? 'semua' ?>" target="_blank">Export ke Excel</a>
            <a href="export_pdf.php?filter=<?= $_GET['filter'] ?? 'semua' ?>" target="_blank">Export ke PDF</a>
        </div>
    </div>
</body>
</html>
