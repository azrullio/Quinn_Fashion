<?php
// kategori.php
// File ini hanya berisi konten yang akan dimuat ke index.php via AJAX.
// Tidak ada header, sidebar, atau footer di sini.
session_start(); // Tetap butuh session untuk pesan feedback

if (!isset($_SESSION['admin'])) {
    // Jika diakses langsung tanpa AJAX dan belum login, redirect ke login
    // Ini adalah fallback, idealnya tidak akan diakses langsung lagi
    header("Location: login.php");
    exit;
}
include 'inc/db.php'; // Tetap butuh koneksi database

// Inisialisasi pesan (dari session jika ada, atau kosong)
$message = '';
$message_type = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// Handle add category
if (isset($_POST['tambah_kategori'])) {
    $nama_kategori = trim($_POST['nama_kategori'] ?? '');

    if (!empty($nama_kategori)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO kategori (nama_kategori) VALUES (?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $nama_kategori);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['message'] = "Kategori '<b>" . htmlspecialchars($nama_kategori) . "</b>' berhasil ditambahkan!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Gagal menambahkan kategori: " . mysqli_error($conn);
                $_SESSION['message_type'] = "danger";
                error_log("Failed to add category: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = "Terjadi kesalahan sistem saat menyiapkan query.";
            $_SESSION['message_type'] = "danger";
            error_log("Prepare statement error for INSERT category: " . mysqli_error($conn));
        }
    } else {
        $_SESSION['message'] = "Nama kategori tidak boleh kosong.";
        $_SESSION['message_type'] = "danger";
    }
    // Karena ini diakses via AJAX, tidak perlu header redirect,
    // pesan akan diambil di reload AJAX berikutnya atau ditampilkan langsung.
    // Untuk konsistensi, tetap pakai session agar bisa diambil di load berikutnya.
}

// Handle delete category
if (isset($_GET['hapus'])) {
    $id_kategori = filter_var($_GET['hapus'], FILTER_VALIDATE_INT);

    if ($id_kategori === false) {
        $_SESSION['message'] = "ID kategori tidak valid.";
        $_SESSION['message_type'] = "danger";
    } else {
        $stmt_check_barang = mysqli_prepare($conn, "SELECT COUNT(*) FROM barang WHERE kategori_id = ?");
        if ($stmt_check_barang) {
            mysqli_stmt_bind_param($stmt_check_barang, "i", $id_kategori);
            mysqli_stmt_execute($stmt_check_barang);
            mysqli_stmt_bind_result($stmt_check_barang, $count_barang);
            mysqli_stmt_fetch($stmt_check_barang);
            mysqli_stmt_close($stmt_check_barang);

            if ($count_barang > 0) {
                $_SESSION['message'] = "Kategori tidak bisa dihapus karena masih ada <b>" . $count_barang . " barang</b> yang terkait dengan kategori ini. Harap hapus atau ubah kategori barang tersebut terlebih dahulu.";
                $_SESSION['message_type'] = "warning";
            } else {
                $stmt_get_name = mysqli_prepare($conn, "SELECT nama_kategori FROM kategori WHERE id = ?");
                if ($stmt_get_name) {
                    mysqli_stmt_bind_param($stmt_get_name, "i", $id_kategori);
                    mysqli_stmt_execute($stmt_get_name);
                    $result_name = mysqli_stmt_get_result($stmt_get_name);
                    $row_name = mysqli_fetch_assoc($result_name);
                    $deleted_name = $row_name ? htmlspecialchars($row_name['nama_kategori']) : "Tidak Dikenal";
                    mysqli_stmt_close($stmt_get_name);

                    $stmt_delete = mysqli_prepare($conn, "DELETE FROM kategori WHERE id = ?");
                    if ($stmt_delete) {
                        mysqli_stmt_bind_param($stmt_delete, "i", $id_kategori);
                        if (mysqli_stmt_execute($stmt_delete)) {
                            if (mysqli_stmt_affected_rows($stmt_delete) > 0) {
                                $_SESSION['message'] = "Kategori '<b>" . $deleted_name . "</b>' berhasil dihapus!";
                                $_SESSION['message_type'] = "danger";
                            } else {
                                $_SESSION['message'] = "Kategori tidak ditemukan atau sudah dihapus.";
                                $_SESSION['message_type'] = "info";
                            }
                        } else {
                            $_SESSION['message'] = "Gagal menghapus kategori: " . mysqli_error($conn);
                            $_SESSION['message_type'] = "danger";
                            error_log("Failed to delete category: " . mysqli_error($conn));
                        }
                        mysqli_stmt_close($stmt_delete);
                    } else {
                        $_SESSION['message'] = "Terjadi kesalahan sistem saat menyiapkan query penghapusan.";
                        $_SESSION['message_type'] = "danger";
                        error_log("Prepare statement error for DELETE category: " . mysqli_error($conn));
                    }
                } else {
                     $_SESSION['message'] = "Terjadi kesalahan sistem saat mengambil nama kategori.";
                    $_SESSION['message_type'] = "danger";
                }
            }
        } else {
            $_SESSION['message'] = "Terjadi kesalahan sistem saat memeriksa keterkaitan barang.";
            $_SESSION['message_type'] = "danger";
            error_log("Prepare statement error for checking related items: " . mysqli_error($conn));
        }
    }
}

// Handle edit category
if (isset($_POST['edit_kategori'])) {
    $id = filter_var($_POST['id_kategori'], FILTER_VALIDATE_INT);
    $nama_kategori_baru = trim($_POST['nama_kategori_baru'] ?? '');

    if ($id === false || empty($nama_kategori_baru)) {
        $_SESSION['message'] = "ID kategori atau nama kategori tidak valid.";
        $_SESSION['message_type'] = "danger";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE kategori SET nama_kategori = ? WHERE id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $nama_kategori_baru, $id);
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $_SESSION['message'] = "Kategori berhasil diubah menjadi '<b>" . htmlspecialchars($nama_kategori_baru) . "</b>'.";
                    $_SESSION['message_type'] = "info";
                } else {
                    $_SESSION['message'] = "Tidak ada perubahan pada kategori atau kategori tidak ditemukan.";
                    $_SESSION['message_type'] = "info";
                }
            } else {
                $_SESSION['message'] = "Gagal mengubah kategori: " . mysqli_error($conn);
                $_SESSION['message_type'] = "danger";
                error_log("Failed to update category: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['message'] = "Terjadi kesalahan sistem saat menyiapkan query.";
            $_SESSION['message_type'] = "danger";
            error_log("Prepare statement error for UPDATE category: " . mysqli_error($conn));
        }
    }
}


// Ambil semua kategori untuk ditampilkan
$query_kategori = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
$stmt_kategori = mysqli_prepare($conn, $query_kategori);
$kategori_result = false;
if ($stmt_kategori) {
    mysqli_stmt_execute($stmt_kategori);
    $kategori_result = mysqli_stmt_get_result($stmt_kategori);
    mysqli_stmt_close($stmt_kategori);
} else {
    error_log("Error preparing category select statement: " . mysqli_error($conn));
    // Jika ada error di sini, set pesan error untuk ditampilkan
    $message = "Gagal mengambil data kategori.";
    $message_type = "danger";
}
?>

<h2 class="kategori-title"><i class="fas fa-tags"></i> Manajemen Kategori</h2>

<?php if ($message): ?>
    <div class="alert alert-custom alert-<?= $message_type ?>" role="alert">
        <?php if ($message_type == 'success'): ?>
            <i class="fas fa-check-circle"></i>
        <?php elseif ($message_type == 'danger'): ?>
            <i class="fas fa-exclamation-triangle"></i>
        <?php elseif ($message_type == 'warning'): ?>
            <i class="fas fa-exclamation-circle"></i>
        <?php elseif ($message_type == 'info'): ?>
            <i class="fas fa-info-circle"></i>
        <?php endif; ?>
        <?= $message ?>
    </div>
<?php endif; ?>

<form method="POST" class="form-add-category">
    <h5 class="mb-3 text-primary"><i class="fas fa-plus-circle"></i> Tambah Kategori Baru</h5>
    <div class="mb-3">
        <label for="nama_kategori" class="form-label">Nama Kategori</label>
        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control" required maxlength="100">
    </div>
    <button type="submit" name="tambah_kategori" class="btn btn-success"><i class="fas fa-plus-square me-2"></i>Tambah Kategori</button>
</form>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php if ($kategori_result && mysqli_num_rows($kategori_result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($kategori_result)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" onclick="editKategori(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['nama_kategori'])) ?>')" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus kategori &quot;<?= htmlspecialchars($row['nama_kategori']) ?>&quot;? Jika ada barang yang menggunakan kategori ini, penghapusan akan gagal.')">
                                <i class="fas fa-trash-alt me-1"></i>Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3" class="text-center">Belum ada kategori.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel"><i class="fas fa-edit me-2"></i>Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_kategori" id="editIdKategori">
                    <div class="mb-3">
                        <label for="nama_kategori_baru" class="form-label">Nama Kategori Baru</label>
                        <input type="text" name="nama_kategori_baru" id="editNamaKategori" class="form-control" required maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="edit_kategori" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi ini harus dideklarasikan secara global atau di dalam scope yang dapat diakses oleh HTML yang dimuat
    function editKategori(id, nama) {
        document.getElementById('editIdKategori').value = id;
        document.getElementById('editNamaKategori').value = nama;
    }

    // Panggil ulang Bootstrap Modal jika HTML dimuat secara dinamis
    // Ini juga bisa dilakukan di index.php setelah loadContent
    // let editModalElement = document.getElementById('editModal');
    // if (editModalElement) {
    //     new bootstrap.Modal(editModalElement);
    // }
</script>