<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include 'inc/db.php';

$message = '';
$message_type = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Fungsi upload file tetap sama
function handleFileUpload($file, $target_dir, $allowed_types, $max_size)
{
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'File upload error: ' . $file['error']];
    }

    $file_tmp = $file['tmp_name'];
    $file_name = $file['name'];
    $file_size = $file['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file_tmp);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        return ['success' => false, 'error' => 'Format file tidak didukung. Hanya JPG, PNG, GIF.'];
    }
    if ($file_size > $max_size) {
        return ['success' => false, 'error' => 'Ukuran file terlalu besar (maks ' . ($max_size / (1024 * 1024)) . 'MB).'];
    }

    $new_file_name = uniqid('slider_', true) . '.' . $file_ext;
    $target_path = $target_dir . $new_file_name;

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (move_uploaded_file($file_tmp, $target_path)) {
        return ['success' => true, 'file_name' => $new_file_name, 'full_path' => $target_path];
    } else {
        return ['success' => false, 'error' => 'Gagal memindahkan file yang diupload.'];
    }
}

$upload_dir = '../uploads/';
$allowed_image_types = ['image/jpeg', 'image/png', 'image/gif'];
$max_image_size = 5 * 1024 * 1024;

// --- Tambah Slider ---
if (isset($_POST['upload'])) {
    $judul = trim($_POST['judul'] ?? '');

    if (empty($judul)) {
        $_SESSION['message'] = "Judul slider tidak boleh kosong.";
        $_SESSION['message_type'] = "danger";
    } elseif (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] === UPLOAD_ERR_NO_FILE) {
        $_SESSION['message'] = "Silakan pilih gambar untuk diupload.";
        $_SESSION['message_type'] = "danger";
    } else {
        $upload_result = handleFileUpload($_FILES['gambar'], $upload_dir, $allowed_image_types, $max_image_size);

        if ($upload_result['success']) {
            $gambar_file = $upload_result['file_name'];

            $stmt = mysqli_prepare($conn, "INSERT INTO slider_iklan (judul, file_gambar) VALUES (?, ?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $judul, $gambar_file);
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['message'] = "Slider berhasil ditambahkan!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Gagal menyimpan data slider ke database. " . mysqli_error($conn);
                    $_SESSION['message_type'] = "danger";
                    if (file_exists($upload_result['full_path'])) {
                        unlink($upload_result['full_path']);
                    }
                }
                mysqli_stmt_close($stmt);
            } else {
                $_SESSION['message'] = "Terjadi kesalahan sistem saat menyiapkan query. " . mysqli_error($conn);
                $_SESSION['message_type'] = "danger";
                if (file_exists($upload_result['full_path'])) {
                    unlink($upload_result['full_path']);
                }
            }
        } else {
            $_SESSION['message'] = "Error upload gambar: " . $upload_result['error'];
            $_SESSION['message_type'] = "danger";
        }
    }
    // Jika request AJAX, skrip akan melanjutkan untuk merender HTML ulang
    if (!$is_ajax) {
        header("Location: slider_iklan.php");
        exit;
    }
}

// --- Hapus Slider ---
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];

    // Ambil nama file gambar sebelum menghapus dari database
    $stmt_select_file = mysqli_prepare($conn, "SELECT file_gambar FROM slider_iklan WHERE id = ?");
    if ($stmt_select_file) {
        mysqli_stmt_bind_param($stmt_select_file, "i", $id_hapus);
        mysqli_stmt_execute($stmt_select_file);
        mysqli_stmt_bind_result($stmt_select_file, $file_gambar_to_delete);
        mysqli_stmt_fetch($stmt_select_file);
        mysqli_stmt_close($stmt_select_file);

        if ($file_gambar_to_delete) {
            $full_path_to_delete = $upload_dir . $file_gambar_to_delete;
            // Hapus dari database
            $stmt_delete = mysqli_prepare($conn, "DELETE FROM slider_iklan WHERE id = ?");
            if ($stmt_delete) {
                mysqli_stmt_bind_param($stmt_delete, "i", $id_hapus);
                if (mysqli_stmt_execute($stmt_delete)) {
                    // Hapus file fisik jika berhasil dihapus dari database
                    if (file_exists($full_path_to_delete)) {
                        unlink($full_path_to_delete);
                    }
                    $_SESSION['message'] = "Slider berhasil dihapus!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Gagal menghapus slider dari database. " . mysqli_error($conn);
                    $_SESSION['message_type'] = "danger";
                }
                mysqli_stmt_close($stmt_delete);
            } else {
                $_SESSION['message'] = "Terjadi kesalahan sistem saat menyiapkan query hapus. " . mysqli_error($conn);
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "Slider tidak ditemukan atau gambar sudah tidak ada.";
            $_SESSION['message_type'] = "info";
        }
    } else {
        $_SESSION['message'] = "Terjadi kesalahan sistem saat mengambil data gambar untuk dihapus. " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }

    if (!$is_ajax) {
        header("Location: slider_iklan.php");
        exit;
    }
}


// --- Edit Slider ---
if (isset($_POST['edit'])) {
    $id_edit = $_POST['id_edit'] ?? '';
    $judul_edit = trim($_POST['judul_edit'] ?? '');
    $gambar_lama = '';

    // Ambil nama file gambar lama sebelum update
    $stmt_select_old_file = mysqli_prepare($conn, "SELECT file_gambar FROM slider_iklan WHERE id = ?");
    if ($stmt_select_old_file) {
        mysqli_stmt_bind_param($stmt_select_old_file, "i", $id_edit);
        mysqli_stmt_execute($stmt_select_old_file);
        mysqli_stmt_bind_result($stmt_select_old_file, $gambar_lama);
        mysqli_stmt_fetch($stmt_select_old_file);
        mysqli_stmt_close($stmt_select_old_file);
    }

    if (empty($judul_edit)) {
        $_SESSION['message'] = "Judul slider tidak boleh kosong.";
        $_SESSION['message_type'] = "danger";
    } else {
        $gambar_file_baru = $gambar_lama; // Default menggunakan gambar lama

        // Cek apakah ada file gambar baru yang diupload
        if (isset($_FILES['gambar_edit']) && $_FILES['gambar_edit']['error'] === UPLOAD_ERR_OK) {
            $upload_result_edit = handleFileUpload($_FILES['gambar_edit'], $upload_dir, $allowed_image_types, $max_image_size);

            if ($upload_result_edit['success']) {
                $gambar_file_baru = $upload_result_edit['file_name'];
                // Hapus gambar lama jika ada dan berhasil upload gambar baru
                if ($gambar_lama && file_exists($upload_dir . $gambar_lama)) {
                    unlink($upload_dir . $gambar_lama);
                }
            } else {
                $_SESSION['message'] = "Error upload gambar baru: " . $upload_result_edit['error'];
                $_SESSION['message_type'] = "danger";
                // Jangan lanjutkan update DB jika upload gambar baru gagal
                if (!$is_ajax) {
                    header("Location: slider_iklan.php");
                    exit;
                }
                goto skip_db_update; // Langsung ke bagian pengambilan data slider
            }
        }

        $stmt_update = mysqli_prepare($conn, "UPDATE slider_iklan SET judul = ?, file_gambar = ? WHERE id = ?");
        if ($stmt_update) {
            mysqli_stmt_bind_param($stmt_update, "ssi", $judul_edit, $gambar_file_baru, $id_edit);
            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['message'] = "Slider berhasil diperbarui!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Gagal memperbarui data slider. " . mysqli_error($conn);
                $_SESSION['message_type'] = "danger";
                // Jika update DB gagal, dan ada gambar baru diupload, hapus gambar baru
                if (isset($upload_result_edit) && $upload_result_edit['success'] && file_exists($upload_result_edit['full_path'])) {
                    unlink($upload_result_edit['full_path']);
                }
            }
            mysqli_stmt_close($stmt_update);
        } else {
            $_SESSION['message'] = "Terjadi kesalahan sistem saat menyiapkan query update. " . mysqli_error($conn);
            $_SESSION['message_type'] = "danger";
            if (isset($upload_result_edit) && $upload_result_edit['success'] && file_exists($upload_result_edit['full_path'])) {
                unlink($upload_result_edit['full_path']);
            }
        }
    }
    skip_db_update:; // Label untuk goto

    if (!$is_ajax) {
        header("Location: slider_iklan.php");
        exit;
    }
}


// Ambil data slider
$query_slider = "SELECT id, judul, file_gambar FROM slider_iklan ORDER BY id DESC";
$stmt_slider = mysqli_prepare($conn, $query_slider);
if ($stmt_slider) {
    mysqli_stmt_execute($stmt_slider);
    $slider_result = mysqli_stmt_get_result($stmt_slider);
    mysqli_stmt_close($stmt_slider);
} else {
    $slider_result = false;
}
?>

<style>
    /* Gaya CSS tetap sama persis seperti yang Anda berikan */
    .slider-title {
        margin-bottom: 25px;
        color: #343a40;
        font-weight: 700;
        border-bottom: 3px solid #00d1b2;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    form {
        background: #fdfdfd;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border-top: 5px solid #00d1b2;
    }

    form .form-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
    }

    form .form-control {
        border-radius: 6px;
        padding: 10px;
        border: 1px solid #ced4da;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    form .form-control:focus {
        outline: none;
        border-color: #00d1b2;
        box-shadow: 0 0 0 0.25rem rgba(0, 209, 178, .25);
    }

    form button.btn-success {
        background-color: #00d1b2;
        border-color: #00d1b2;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        transition: background-color 0.3s ease, border-color 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    form button.btn-success:hover {
        background-color: #00b89b;
        border-color: #00b89b;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .table {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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

    .table img {
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .table .btn-primary,
    .table .btn-danger {
        padding: 6px 12px;
        font-size: 0.875rem;
        border-radius: 5px;
        margin-right: 5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .table .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .table .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .table .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .table .btn-danger:hover {
        background-color: #c82333;
        border-color: #c82333;
    }

    /* Alert styling */
    .alert-custom {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    /* Modal specific styling */
    .modal-content {
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background-color: #00d1b2;
        color: white;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .modal-header .btn-close {
        filter: invert(1) brightness(2);
    }

    .modal-title {
        font-weight: 600;
    }

    .modal-footer .btn {
        border-radius: 6px;
    }
</style>

<h2 class="slider-title"><i class="fas fa-images"></i> Manajemen Slider Iklan</h2>

<div id="feedbackSlider">
    <?php if ($message): ?>
        <div class="alert alert-custom alert-<?= htmlspecialchars($message_type) ?>" role="alert">
            <?php if ($message_type == 'success'): ?>
                <i class="fas fa-check-circle"></i>
            <?php elseif ($message_type == 'danger'): ?>
                <i class="fas fa-exclamation-triangle"></i>
            <?php elseif ($message_type == 'info'): ?>
                <i class="fas fa-info-circle"></i>
            <?php endif; ?>
            <?= $message ?>
        </div>
    <?php endif; ?>
</div>

<form method="POST" enctype="multipart/form-data" id="formUploadSlider">
    <h5 class="mb-3 text-primary"><i class="fas fa-plus-circle"></i> Tambah Slider Baru</h5>
    <div class="mb-3">
        <label for="judul" class="form-label">Judul Slide</label>
        <input type="text" name="judul" id="judul" required class="form-control" maxlength="255" placeholder="">
    </div>
    <div class="mb-3">
        <label for="gambar" class="form-label">Upload Gambar (.jpg, .png, .gif) <br><small class="text-muted">Maksimal 5MB. Resolusi ideal untuk slider disarankan.</small></label>
        <input type="file" name="gambar" id="gambar" accept="image/jpeg, image/png, image/gif" required class="form-control">
    </div>
    <button type="submit" name="upload" class="btn btn-success"><i class="fas fa-upload me-2"></i>Tambah Slider</button>
</form>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($slider_result && mysqli_num_rows($slider_result) > 0): ?>
                <?php $no = 1;
                while ($row = mysqli_fetch_assoc($slider_result)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['judul']) ?></td>
                        <td>
                            <?php
                            $image_path_display = '../uploads/' . htmlspecialchars($row['file_gambar']);
                            if (file_exists($image_path_display) && is_file($image_path_display)) {
                                echo '<img src="' . $image_path_display . '" style="max-width: 150px; height: auto;" class="img-thumbnail" alt="Slider Image">';
                            } else {
                                echo '<span class="text-danger">Gambar tidak ditemukan!</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" onclick="editSlider(<?= htmlspecialchars($row['id']) ?>, '<?= htmlspecialchars(addslashes($row['judul'])) ?>')" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                            <a href="?hapus=<?= htmlspecialchars($row['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus slide &quot;<?= htmlspecialchars($row['judul']) ?>&quot;? Tindakan ini tidak dapat dibatalkan.')">
                                <i class="fas fa-trash-alt me-1"></i>Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Belum ada slider yang ditambahkan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Slider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" id="formEditSlider">
                <div class="modal-body">
                    <input type="hidden" name="id_edit" id="editId">
                    <div class="mb-3">
                        <label for="editJudul" class="form-label">Judul Slide</label>
                        <input type="text" name="judul_edit" id="editJudul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGambar" class="form-label">Ganti Gambar (Opsional)</label>
                        <input type="file" name="gambar_edit" id="editGambar" accept="image/jpeg, image/png, image/gif" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk mengisi data ke modal edit
    function editSlider(id, judul) {
        document.getElementById('editId').value = id;
        document.getElementById('editJudul').value = judul;
        document.getElementById('editGambar').value = ''; // Reset input file saat modal dibuka
    }

    // Fungsi utama untuk menangani submit form via AJAX
    function handleSliderFormSubmit(e) {
        e.preventDefault(); // Mencegah form melakukan submit default (full page reload)

        const form = e.target;
        const formData = new FormData(form);

        // Tambahkan header khusus untuk menandakan ini adalah request AJAX
        fetch('slider_iklan.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Penting untuk deteksi AJAX di PHP
                },
                body: formData
            })
            .then(response => response.text()) // Mengambil respon dalam bentuk teks (HTML)
            .then(html => {
                // Ganti seluruh isi `#konten` dengan HTML yang diterima dari server
                // Pastikan di file utama (misal index.php) ada div dengan id="konten"
                // tempat `slider_iklan.php` dimuat.
                const kontenElement = document.getElementById('konten');
                if (kontenElement) {
                    kontenElement.innerHTML = html;

                    // Setelah konten diperbarui, panggil fungsi untuk memasang kembali event listener
                    attachAllEventListeners();

                    // Scroll ke atas agar user bisa melihat pesan feedback
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                    // Khusus untuk modal edit, tutup modal setelah submit berhasil
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                    if (editModal) {
                        editModal.hide();
                    }
                } else {
                    console.error("Element with ID 'konten' not found. Cannot update content.");
                }
            })
            .catch(err => {
                // Tampilkan pesan error jika terjadi masalah pada fetch
                const feedbackElement = document.getElementById('feedbackSlider');
                if (feedbackElement) {
                    feedbackElement.innerHTML =
                        '<div class="alert alert-danger">Gagal mengupload/memperbarui slider. Coba lagi.</div>';
                }
                console.error("Fetch Error:", err);
            });
    }

    // Fungsi untuk memasang semua event listener
    function attachAllEventListeners() {
        // Hapus listener lama sebelum memasang yang baru untuk mencegah duplikasi
        const formUploadSlider = document.getElementById('formUploadSlider');
        if (formUploadSlider) {
            formUploadSlider.removeEventListener('submit', handleSliderFormSubmit);
            formUploadSlider.addEventListener('submit', handleSliderFormSubmit);
        }

        const formEditSlider = document.getElementById('formEditSlider');
        if (formEditSlider) {
            formEditSlider.removeEventListener('submit', handleSliderFormSubmit);
            formEditSlider.addEventListener('submit', handleSliderFormSubmit);
        }
    }

    // Panggil fungsi untuk memasang event listener saat halaman pertama kali dimuat
    // atau setelah konten AJAX dimuat ulang.
    // Jika Anda memuat `slider_iklan.php` secara langsung di halaman, panggil ini.
    // Jika `slider_iklan.php` dimuat via AJAX pertama kali oleh file lain, panggil ini setelah loading.
    document.addEventListener('DOMContentLoaded', attachAllEventListeners);

</script>