<?php
// keuangan.php
// File ini hanya berisi konten yang akan dimuat ke index.php via AJAX.
// Tidak ada header, sidebar, atau footer di sini, karena sudah ditangani oleh index.php.
session_start(); // Tetap butuh session untuk pesan feedback

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['admin'])) {
    // Jika diakses langsung tanpa AJAX dan belum login, redirect ke login
    // Ini adalah fallback, idealnya tidak akan diakses langsung lagi
    header("Location: login.php");
    exit;
}
include 'inc/db.php'; // Tetap butuh koneksi database

// Inisialisasi pesan (dari session jika ada, atau kosong)
// Pesan ini akan diatur di operasi CRUD dan ditampilkan saat halaman dimuat ulang via AJAX
$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Handle tambah pemasukan/pengeluaran
if (isset($_POST['tambah_keuangan'])) {
    $tipe = trim($_POST['tipe'] ?? ''); // Ini akan mapping ke kolom 'jenis' di DB
    // Hapus titik dan koma dari input jumlah untuk memastikan hanya angka yang tersisa
    $jumlah_raw = str_replace(['.', ','], '', $_POST['jumlah'] ?? '');
    $jumlah = filter_var($jumlah_raw, FILTER_VALIDATE_FLOAT); // Menggunakan FLOAT karena 'nominal' di DB adalah DOUBLE
    $deskripsi = trim($_POST['deskripsi'] ?? ''); // Ini akan mapping ke kolom 'keterangan' di DB
    $tanggal = trim($_POST['tanggal'] ?? '');

    $errors = [];
    if (!in_array($tipe, ['pemasukan', 'pengeluaran'])) {
        $errors[] = "Tipe tidak valid.";
    }
    // Periksa validitas float, izinkan nol jika itu skenario yang valid untuk data Anda
    if ($jumlah === false || $jumlah < 0) {
        $errors[] = "Jumlah harus berupa angka positif atau nol.";
    }
    if (empty($deskripsi)) {
        $errors[] = "Deskripsi tidak boleh kosong.";
    }
    if (empty($tanggal)) {
        $errors[] = "Tanggal tidak boleh kosong.";
    }

    if (empty($errors)) {
        // PERUBAHAN UTAMA: Gunakan kolom 'jenis', 'nominal', 'keterangan' sesuai DB Anda
        $stmt = mysqli_prepare($conn, "INSERT INTO keuangan (jenis, nominal, keterangan, tanggal) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            // 's' untuk string (jenis), 'd' untuk double (nominal), 's' untuk string (keterangan), 's' untuk string (tanggal)
            mysqli_stmt_bind_param($stmt, "sdss", $tipe, $jumlah, $deskripsi, $tanggal);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = ucfirst($tipe) . " sebesar Rp" . number_format($jumlah, 0, ',', '.') . " berhasil ditambahkan!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Gagal menambahkan data keuangan: " . mysqli_error($conn);
                $_SESSION['message_type'] = "danger";
                error_log("Failed to add keuangan: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = "Terjadi kesalahan sistem saat menyiapkan query.";
            $_SESSION['message_type'] = "danger";
            error_log("Prepare statement error for INSERT keuangan: " . mysqli_error($conn));
        }
    } else {
        $_SESSION['message'] = "<ul>";
        foreach ($errors as $err) {
            $_SESSION['message'] .= "<li>" . htmlspecialchars($err) . "</li>";
        }
        $_SESSION['message'] .= "</ul>";
        $_SESSION['message_type'] = "danger";
    }
    // Tidak ada header redirect di sini karena ini adalah fragmen AJAX.
    // Pesan akan diambil oleh index.php saat fragment ini dimuat ulang.
}

// Handle hapus data keuangan
if (isset($_GET['hapus'])) {
    $id = filter_var($_GET['hapus'], FILTER_VALIDATE_INT);

    if ($id === false) {
        $_SESSION['message'] = "ID data keuangan tidak valid.";
        $_SESSION['message_type'] = "danger";
    } else {
        $stmt = mysqli_prepare($conn, "DELETE FROM keuangan WHERE id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $_SESSION['message'] = "Data keuangan berhasil dihapus!";
                    $_SESSION['message_type'] = "danger";
                } else {
                    $_SESSION['message'] = "Data keuangan tidak ditemukan atau sudah dihapus.";
                    $_SESSION['message_type'] = "info";
                }
            } else {
                $_SESSION['message'] = "Gagal menghapus data keuangan: " . mysqli_error($conn);
                $_SESSION['message_type'] = "danger";
                error_log("Failed to delete keuangan: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = "Terjadi kesalahan sistem saat menyiapkan query penghapusan.";
            $_SESSION['message_type'] = "danger";
            error_log("Prepare statement error for DELETE keuangan: " . mysqli_error($conn));
        }
    }
    // Tidak ada header redirect
}

// Ambil semua data keuangan
// PERUBAHAN: Ambil kolom 'jenis', 'nominal', 'keterangan'
$query_keuangan = "SELECT id, tanggal, keterangan, nominal, jenis FROM keuangan ORDER BY tanggal DESC, id DESC";
$stmt_keuangan = mysqli_prepare($conn, $query_keuangan);
$keuangan_result = false;
if ($stmt_keuangan) {
    mysqli_stmt_execute($stmt_keuangan);
    $keuangan_result = mysqli_stmt_get_result($stmt_keuangan);
    mysqli_stmt_close($stmt_keuangan);
} else {
    error_log("Error preparing keuangan select statement: " . mysqli_error($conn));
    // Jika ada error di sini, set pesan error untuk ditampilkan
    $message = "Gagal mengambil data keuangan.";
    $message_type = "danger";
}

// Ambil total pemasukan dan pengeluaran (ulang untuk memastikan data terbaru)
// PERUBAHAN: Gunakan kolom 'nominal' dan 'jenis'
$total_pemasukan_q = "SELECT SUM(nominal) AS total FROM keuangan WHERE jenis = 'pemasukan'";
$total_pengeluaran_q = "SELECT SUM(nominal) AS total FROM keuangan WHERE jenis = 'pengeluaran'";

$total_pemasukan_stmt = mysqli_prepare($conn, $total_pemasukan_q);
$total_pemasukan = 0;
if ($total_pemasukan_stmt) {
    mysqli_stmt_execute($total_pemasukan_stmt);
    $res = mysqli_stmt_get_result($total_pemasukan_stmt);
    $row = mysqli_fetch_assoc($res);
    $total_pemasukan = $row['total'] ?? 0;
    mysqli_stmt_close($total_pemasukan_stmt);
} else {
    error_log("Error preparing pemasukan sum: " . mysqli_error($conn));
}

$total_pengeluaran_stmt = mysqli_prepare($conn, $total_pengeluaran_q);
$total_pengeluaran = 0;
if ($total_pengeluaran_stmt) {
    mysqli_stmt_execute($total_pengeluaran_stmt);
    $res = mysqli_stmt_get_result($total_pengeluaran_stmt);
    $row = mysqli_fetch_assoc($res);
    $total_pengeluaran = $row['total'] ?? 0;
    mysqli_stmt_close($total_pengeluaran_stmt);
} else {
    error_log("Error preparing pengeluaran sum: " . mysqli_error($conn));
}

$saldo = $total_pemasukan - $total_pengeluaran;
?>

<style>
    /* Styling khusus untuk halaman keuangan */
    .keuangan-title {
        margin-bottom: 25px;
        color: #343a40;
        font-weight: 700;
        border-bottom: 3px solid #00d1b2;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .summary-cards .card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        text-align: center;
        padding: 20px;
        margin-bottom: 20px;
        height: 100%;
    }
    .summary-cards .card-body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .summary-cards .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 10px;
    }
    .summary-cards .card-text {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    .card-pemasukan .card-text { color: #28a745; }
    .card-pengeluaran .card-text { color: #dc3545; }
    .card-saldo .card-text { color: #007bff; }

    .form-add-keuangan {
        background: #fdfdfd;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-bottom: 30px;
        border-top: 5px solid #00d1b2;
    }
    .form-add-keuangan .form-label {
        font-weight: 600;
        color: #555;
    }
    .form-add-keuangan .form-control {
        border-radius: 6px;
        padding: 10px;
    }
    .form-add-keuangan .btn-success {
        background-color: #00d1b2;
        border-color: #00d1b2;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-add-keuangan .btn-success:hover {
        background-color: #00b89b;
        border-color: #00b89b;
    }

    .table-responsive {
        margin-top: 20px;
    }
    .table {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-radius: 8px;
        overflow: hidden;
    }
    .table thead th {
        background-color: #e9ecef;
        color: #495057;
        font-weight: 600;
        padding: 15px;
        border-bottom: 2px solid #dee2e6;
    }
    .table tbody td {
        padding: 15px;
        border-bottom: 1px solid #e2e6ea;
        vertical-align: middle;
    }
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    .table tbody tr:hover {
        background-color: #f1f3f5;
    }
    .table .btn-danger {
        padding: 6px 12px;
        font-size: 0.875rem;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .table .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .table .btn-danger:hover {
        background-color: #c82333;
        border-color: #c82333;
    }
    /* Alert styling (copied from index.php/sidebar.php for consistency) */
    .alert-custom {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .alert-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .alert-info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

</style>

<h2 class="keuangan-title"><i class="fas fa-wallet"></i> Manajemen Keuangan</h2>

<?php if ($message): ?>
    <div class="alert alert-custom alert-<?= htmlspecialchars($message_type) ?>" role="alert">
        <?php if ($message_type == 'success'): ?>
            <i class="fas fa-check-circle"></i>
        <?php elseif ($message_type == 'danger'): ?>
            <i class="fas fa-exclamation-triangle"></i>
        <?php elseif ($message_type == 'info'): ?>
            <i class="fas fa-info-circle"></i>
        <?php endif; ?>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<div class="row summary-cards">
    <div class="col-md-4">
        <div class="card card-pemasukan">
            <div class="card-body">
                <h5 class="card-title">Total Pemasukan</h5>
                <p class="card-text">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-pengeluaran">
            <div class="card-body">
                <h5 class="card-title">Total Pengeluaran</h5>
                <p class="card-text">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-saldo">
            <div class="card-body">
                <h5 class="card-title">Saldo Bersih</h5>
                <p class="card-text">Rp <?= number_format($saldo, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>
</div>

<form method="POST" class="form-add-keuangan">
    <h5 class="mb-3 text-primary"><i class="fas fa-money-bill-transfer"></i> Tambah Pemasukan / Pengeluaran</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tipe" class="form-label">Tipe</label>
            <select name="tipe" id="tipe" class="form-control" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="pemasukan">Pemasukan</option>
                <option value="pengeluaran">Pengeluaran</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="jumlah" class="form-label">Jumlah (Rp)</label>
            <input type="text" name="jumlah" id="jumlah" class="form-control" required oninput="formatRupiah(this)" placeholder="Cth: 500.000">
        </div>
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required placeholder="Cth: Penjualan barang A, Gaji karyawan, Pembelian bahan baku"></textarea>
    </div>
    <div class="mb-3">
        <label for="tanggal" class="form-label">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
    </div>
    <button type="submit" name="tambah_keuangan" class="btn btn-success"><i class="fas fa-plus-circle me-2"></i> Tambah Data</button>
</form>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php if ($keuangan_result && mysqli_num_rows($keuangan_result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($keuangan_result)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars(date('d M Y', strtotime($row['tanggal']))) ?></td>
                        <td class="<?= ($row['jenis'] == 'pemasukan') ? 'text-success' : 'text-danger' ?>">
                            <?= ucfirst(htmlspecialchars($row['jenis'])) ?>
                        </td>
                        <td>Rp <?= number_format(htmlspecialchars($row['nominal']), 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td>
                            <a href="?hapus=<?= htmlspecialchars($row['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data keuangan ini? Data yang dihapus tidak dapat dikembalikan.')">
                                <i class="fas fa-trash-alt me-1"></i>Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada data keuangan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Fungsi ini membantu memformat input jumlah ke format Rupiah saat diketik
    function formatRupiah(input) {
        let angka = input.value.replace(/[^,\d]/g, "").toString();
        let split = angka.split(",");
        let rupiah = split[0];
        let decimal = split.length > 1 ? ',' + split[1] : '';

        rupiah = rupiah.replace(/\./g, ''); // Hapus titik sebelumnya
        let ribuan = rupiah.replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Tambahkan titik sebagai pemisah ribuan

        input.value = ribuan + decimal;
    }

    // Event listener untuk form submission (untuk menangkap submit dan memuat ulang via AJAX)
    document.addEventListener('submit', function(event) {
        // Hanya tangani form di dalam konten keuangan ini
        if (event.target.closest('.form-add-keuangan')) {
            event.preventDefault(); // Mencegah form submit default (full page reload)

            const form = event.target;
            const formData = new FormData(form);

            fetch(form.action || window.location.href, { // Aksi form atau URL saat ini
                method: form.method,
                body: formData
            })
            .then(response => response.text()) // Ambil respons sebagai teks (HTML fragmen)
            .then(html => {
                // Asumsi elemen kontainer utama adalah '#main-content' di index.php
                const mainContentDiv = document.getElementById('main-content');
                if (mainContentDiv) {
                    mainContentDiv.innerHTML = html; // Ganti konten utama dengan HTML yang baru
                    // Opsional: inisialisasi ulang komponen JS Bootstrap jika ada di konten yang baru dimuat
                    // (misalnya modal, tooltip, dll. jika ada di halaman keuangan)
                } else {
                    console.error("Elemen #main-content tidak ditemukan.");
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                // Tampilkan pesan error jika AJAX gagal
                const mainContentDiv = document.getElementById('main-content');
                if (mainContentDiv) {
                    mainContentDiv.innerHTML = `<div class="alert alert-danger" role="alert">Terjadi kesalahan saat memproses data: ${error.message}.</div>`;
                }
            });
        }
    });

    // Event listener untuk tombol hapus (untuk menangkap klik dan memuat ulang via AJAX)
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-danger') && event.target.closest('table')) {
            // Pastikan ini adalah tombol hapus di tabel keuangan
            event.preventDefault(); // Mencegah default link behavior (full page reload)

            if (confirm(event.target.onclick.toString().match(/'(.*?)'/)[1])) { // Mengambil pesan konfirmasi dari onclick
                const deleteUrl = event.target.href;

                fetch(deleteUrl)
                    .then(response => response.text()) // Ambil respons sebagai teks (HTML fragmen)
                    .then(html => {
                        const mainContentDiv = document.getElementById('main-content');
                        if (mainContentDiv) {
                            mainContentDiv.innerHTML = html; // Ganti konten utama dengan HTML yang baru
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting data:', error);
                        const mainContentDiv = document.getElementById('main-content');
                        if (mainContentDiv) {
                            mainContentDiv.innerHTML = `<div class="alert alert-danger" role="alert">Terjadi kesalahan saat menghapus data: ${error.message}.</div>`;
                        }
                    });
            }
        }
    });

    // Panggil formatRupiah untuk input jumlah saat halaman dimuat jika ada nilai awal
    // Ini penting jika input memiliki nilai default atau nilai yang di-preserve dari submit sebelumnya
    const jumlahInput = document.getElementById('jumlah');
    if (jumlahInput) {
        formatRupiah(jumlahInput);
    }
</script>