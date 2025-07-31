<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';
include "inc/sidebar.php";

$query_kategori = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
$stmt_kategori = mysqli_prepare($conn, $query_kategori);
if ($stmt_kategori) {
    mysqli_stmt_execute($stmt_kategori);
    $kategori_result = mysqli_stmt_get_result($stmt_kategori);
    mysqli_stmt_close($stmt_kategori);
} else {
    error_log("Error preparing category select statement: " . mysqli_error($conn));
    $kategori_result = false;
}

$message = '';
$message_type = '';

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $kategori_nama = trim($_POST['kategori'] ?? '');
    $harga_raw = str_replace(['.', ','], '', $_POST['harga'] ?? '');
    $stok = filter_var($_POST['stok'] ?? 0, FILTER_VALIDATE_INT);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $link_shopee = filter_var(trim($_POST['link_shopee'] ?? ''), FILTER_VALIDATE_URL) ?: null;
    $link_lazada = filter_var(trim($_POST['link_lazada'] ?? ''), FILTER_VALIDATE_URL) ?: null;
    $link_tokopedia = filter_var(trim($_POST['link_tokopedia'] ?? ''), FILTER_VALIDATE_URL) ?: null;
    $promo = trim($_POST['promo'] ?? '');

    $harga = (int) $harga_raw;

    $errors = [];

    if (empty($nama_barang)) { $errors[] = "Nama barang tidak boleh kosong."; }
    if (empty($kategori_nama)) { $errors[] = "Kategori tidak boleh kosong."; }
    if ($harga <= 0) { $errors[] = "Harga harus lebih dari nol."; }
    if ($stok === false || $stok < 0) { $errors[] = "Stok harus berupa angka positif."; }
    if (empty($deskripsi)) { $errors[] = "Deskripsi barang tidak boleh kosong."; }

    $kategori_id = null;
    if (!empty($kategori_nama)) {
        $stmt_get_kategori_id = mysqli_prepare($conn, "SELECT id FROM kategori WHERE nama_kategori = ?");
        if ($stmt_get_kategori_id) {
            mysqli_stmt_bind_param($stmt_get_kategori_id, "s", $kategori_nama);
            mysqli_stmt_execute($stmt_get_kategori_id);
            $result_kategori_id = mysqli_stmt_get_result($stmt_get_kategori_id);
            if ($row_kategori = mysqli_fetch_assoc($result_kategori_id)) {
                $kategori_id = $row_kategori['id'];
            } else {
                $errors[] = "Kategori yang dipilih tidak valid.";
            }
            mysqli_stmt_close($stmt_get_kategori_id);
        } else {
            error_log("Error preparing statement to get category ID: " . mysqli_error($conn));
            $errors[] = "Terjadi kesalahan sistem saat mencari kategori.";
        }
    }

    $gambar_nama = null;
    $upload_path = null;

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_mime_type = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);

        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file_mime_type, $allowed_mime_types)) {
            $errors[] = "Jenis file gambar tidak diizinkan. Hanya JPG, JPEG, PNG, GIF.";
        }

        if (empty($errors)) {
            $gambar_nama = uniqid('img_', true) . '.' . $file_ext;
            $upload_dir = "img/";
            $upload_path = $upload_dir . $gambar_nama;

            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0775, true)) {
                    $errors[] = "Gagal membuat direktori upload gambar.";
                    error_log("Failed to create upload directory: " . $upload_dir);
                }
            }

            if (empty($errors) && is_writable($upload_dir)) {
                if (!move_uploaded_file($file_tmp, $upload_path)) {
                    $errors[] = "Gagal mengupload gambar. Silakan coba lagi.";
                    error_log("Failed to move uploaded file: $file_tmp to $upload_path");
                }
            } else if (empty($errors)) {
                $errors[] = "Folder upload gambar tidak dapat ditulisi. Periksa izin direktori.";
                error_log("Upload directory not writable: " . $upload_dir);
            }
        }
    } else {
        if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] == UPLOAD_ERR_NO_FILE) {
            $errors[] = "Gambar harus diupload.";
        } else {
            switch ($_FILES['gambar']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = "Ukuran file gambar terlalu besar.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors[] = "File gambar hanya terupload sebagian.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $errors[] = "Missing a temporary folder for image upload.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $errors[] = "Failed to write image file to disk.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $errors[] = "A PHP extension stopped the image upload.";
                    break;
                default:
                    $errors[] = "Terjadi kesalahan upload gambar: Kode " . $_FILES['gambar']['error'];
                    break;
            }
        }
    }

    if (empty($errors)) {
        $stmt_insert = mysqli_prepare($conn, "
            INSERT INTO barang (
                nama_barang, kategori_id, harga, stok, gambar, deskripsi,
                link_shopee, link_lazada, link_tokopedia, promo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt_insert) {
            mysqli_stmt_bind_param(
                $stmt_insert,
                "siisssssss",
                $nama_barang,
                $kategori_id,
                $harga,
                $stok,
                $gambar_nama,
                $deskripsi,
                $link_shopee,
                $link_lazada,
                $link_tokopedia,
                $promo
            );

            if (mysqli_stmt_execute($stmt_insert)) {
                $message = "Barang berhasil ditambahkan!";
                $message_type = "success";
                $_POST = []; // Reset form data agar kosong setelah sukses
            } else {
                $message = "Gagal menambahkan barang ke database: " . mysqli_error($conn);
                $message_type = "danger";
                if ($gambar_nama && file_exists($upload_path)) {
                    unlink($upload_path);
                }
            }
            mysqli_stmt_close($stmt_insert);
        } else {
            $message = "Terjadi kesalahan saat menyiapkan query.";
            $message_type = "danger";
            if ($gambar_nama && file_exists($upload_path)) {
                unlink($upload_path);
            }
        }
    } else {
        $message = "<ul>";
        foreach ($errors as $err) {
            $message .= "<li>" . htmlspecialchars($err) . "</li>";
        }
        $message .= "</ul>";
        $message_type = "danger";
        if ($gambar_nama && file_exists($upload_path)) {
            unlink($upload_path);
        }
    }
}
?>

<style>
    /* Styling khusus untuk halaman tambah barang */
    .add-form-container {
        max-width: 700px;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        margin: 30px auto;
        transition: all 0.3s ease-in-out;
        border-top: 5px solid #00d1b2;
    }

    .add-form-container:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .form-title {
        text-align: center;
        margin-bottom: 30px;
        font-weight: 700;
        color: #333;
        font-size: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .form-label {
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ced4da;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .btn-primary-custom {
        width: 100%;
        padding: 12px;
        font-weight: 700;
        font-size: 18px;
        background-color: #00d1b2;
        border-color: #00d1b2;
        color: white;
        transition: background-color 0.3s ease, border-color 0.3s ease;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary-custom:hover {
        background-color: #00b89b;
        border-color: #00b89b;
    }

    /* Styling untuk alert */
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
</style>

<div class="add-form-container">
    <h4 class="form-title"><i class="fas fa-plus-square"></i> Tambah Barang</h4>

    <?php if ($message): ?>
        <div class="alert alert-custom alert-<?= $message_type ?>" role="alert">
            <?php if ($message_type == 'success'): ?>
                <i class="fas fa-check-circle"></i>
            <?php elseif ($message_type == 'danger'): ?>
                <i class="fas fa-exclamation-circle"></i>
            <?php endif; ?>
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" required autocomplete="off"
             value="<?= htmlspecialchars($_POST['nama_barang'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
            <select name="kategori" id="kategori" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <?php if ($kategori_result && mysqli_num_rows($kategori_result) > 0) : ?>
                    <?php 
                    // Reset pointer jika POST
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') mysqli_data_seek($kategori_result, 0);

                    while ($k = mysqli_fetch_assoc($kategori_result)) : 
                        $selected = (($_POST['kategori'] ?? '') === $k['nama_kategori']) ? 'selected' : '';
                    ?>
                        <option value="<?= htmlspecialchars($k['nama_kategori']) ?>" <?= $selected ?>>
                            <?= htmlspecialchars($k['nama_kategori']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="" disabled>Tidak ada kategori. Tambahkan di Manajemen Kategori.</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
            <input type="text" name="harga" id="harga" class="form-control" required oninput="formatRupiah(this)" placeholder="Cth: 150.000"
             value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="promo" class="form-label">Promo (Opsional)</label>
            <input type="text" name="promo" id="promo" class="form-control" placeholder="Contoh: Gratis Ongkir, Diskon 10%" value="<?= htmlspecialchars($_POST['promo'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
            <input type="number" name="stok" id="stok" class="form-control" required min="0" placeholder="Cth: 100" value="<?= htmlspecialchars($_POST['stok'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi Barang <span class="text-danger">*</span></label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="5" required placeholder="Deskripsi lengkap tentang barang..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="link_shopee" class="form-label">Link Shopee (Opsional)</label>
            <input type="url" name="link_shopee" id="link_shopee" class="form-control" placeholder="Cth: https://shopee.co.id/produk-anda" value="<?= htmlspecialchars($_POST['link_shopee'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="link_lazada" class="form-label">Link Lazada (Opsional)</label>
            <input type="url" name="link_lazada" id="link_lazada" class="form-control" placeholder="Cth: https://www.lazada.co.id/produk-anda" value="<?= htmlspecialchars($_POST['link_lazada'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="link_tokopedia" class="form-label">Link Tokopedia (Opsional)</label>
            <input type="url" name="link_tokopedia" id="link_tokopedia" class="form-control" placeholder="Cth: https://www.tokopedia.com/produk-anda" value="<?= htmlspecialchars($_POST['link_tokopedia'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="gambar" class="form-label">Upload Gambar <span class="text-danger">*</span></label>
            <input type="file" name="gambar" id="gambar" class="form-control" accept="image/jpeg, image/png, image/gif" required>
            <div class="form-text">Format: JPG, JPEG, PNG, GIF. Maksimal 5MB.</div>
        </div>

        <button type="submit" class="btn btn-primary-custom"><i class="fas fa-save me-2"></i> Tambah Barang</button>
    </form>
</div>

<script>
function formatRupiah(input) {
    let angka = input.value.replace(/[^,\d]/g, "").toString();
    let split = angka.split(",");
    let rupiah = split[0];
    let decimal = split.length > 1 ? ',' + split[1] : '';

    rupiah = rupiah.replace(/\./g, '');
    let ribuan = rupiah.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    input.value = ribuan + decimal;
}
</script>
