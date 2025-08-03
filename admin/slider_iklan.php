<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';

// --- BAGIAN BARU UNTUK EDIT & UPDATE ---
$edit_mode = false;
$edit_data = null;
$error = null;

// Mengambil data slider jika mode edit aktif
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM slider_iklan WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $edit_mode = true;
        $edit_data = $result->fetch_assoc();
    } else {
        header("Location: slider_iklan.php");
        exit;
    }
}

// Proses update slider
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $judul = $conn->real_escape_string($_POST['judul']);
    $gambar_file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $path = '../uploads/' . basename($gambar_file);

    $update_query = "UPDATE slider_iklan SET judul='$judul' WHERE id=$id";
    
    if (!empty($gambar_file)) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($tmp);

        if (in_array($file_type, $allowed_types)) {
            $old_file_res = $conn->query("SELECT file_gambar FROM slider_iklan WHERE id=$id");
            if ($old_file_res && $old_file_res->num_rows > 0) {
                $old_file = '../uploads/' . $old_file_res->fetch_assoc()['file_gambar'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }

            if (move_uploaded_file($tmp, $path)) {
                $update_query = "UPDATE slider_iklan SET judul='$judul', file_gambar='$gambar_file' WHERE id=$id";
            } else {
                $error = "Gagal mengupload file baru.";
            }
        } else {
            $error = "Format file tidak didukung. Gunakan JPG, PNG, atau GIF.";
        }
    }
    
    if ($conn->query($update_query)) {
        header("Location: slider_iklan.php?updated=1");
        exit;
    } else {
        $error = "Gagal mengupdate data.";
    }
}

// --- AKHIR BAGIAN BARU ---


// Proses tambah slider
if (isset($_POST['upload'])) {
    $judul = $conn->real_escape_string($_POST['judul']);
    $gambar_file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $path = '../uploads/' . basename($gambar_file);

    if (!empty($gambar_file)) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($tmp);

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($tmp, $path)) {
                $conn->query("INSERT INTO slider_iklan (judul, file_gambar) VALUES ('$judul', '$gambar_file')");
                header("Location: slider_iklan.php?success=1");
                exit;
            } else {
                $error = "Gagal mengupload file.";
            }
        } else {
            $error = "Format file tidak didukung. Gunakan JPG, PNG, atau GIF.";
        }
    } else {
        $error = "Silakan pilih file gambar.";
    }
}

// Proses hapus slider
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $result = $conn->query("SELECT file_gambar FROM slider_iklan WHERE id=$id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_to_delete = '../uploads/' . $row['file_gambar'];
        if (file_exists($file_to_delete)) {
            unlink($file_to_delete);
        }
    }
    $conn->query("DELETE FROM slider_iklan WHERE id=$id");
    header("Location: slider_iklan.php?deleted=1");
    exit;
}

// Ambil semua slider
$slider = $conn->query("SELECT * FROM slider_iklan ORDER BY id DESC");
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>

<div class="main-content">
    <h2>Manajemen Slider Iklan</h2>

    <?php if (isset($_GET['success'])): ?>
        <div style="color: green;">Slider berhasil ditambahkan!</div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div style="color: red;">Slider berhasil dihapus!</div>
    <?php endif; ?>

    <?php if (isset($_GET['updated'])): ?>
        <div style="color: green;">Slider berhasil diperbarui!</div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div style="color: red;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="margin-bottom: 2rem;">
        <h3><?= $edit_mode ? "Edit Slider" : "Tambah Slider" ?></h3>
        
        <?php if ($edit_mode): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($edit_data['id']) ?>">
        <?php endif; ?>

        <label>Judul Slide</label><br>
        <input type="text" name="judul" value="<?= $edit_mode ? htmlspecialchars($edit_data['judul']) : '' ?>" required style="width: 300px; padding: 8px; margin-bottom: 1rem;"><br>

        <label>Upload Gambar (.jpg, .png, .gif) <?= $edit_mode ? "(Kosongkan jika tidak ingin mengubah)" : '' ?></label><br>
        <input type="file" name="gambar" accept="image/*" <?= $edit_mode ? '' : 'required' ?> style="margin-bottom: 1rem;"><br>
        
        <?php if ($edit_mode): ?>
            <p>Gambar saat ini:</p>
            <img src="../uploads/<?= htmlspecialchars($edit_data['file_gambar']) ?>" alt="<?= htmlspecialchars($edit_data['judul']) ?>" style="max-width: 200px; margin-bottom: 1rem;"><br>
        <?php endif; ?>
        
        <button type="submit" name="<?= $edit_mode ? 'update' : 'upload' ?>" style="padding: 10px 20px;">
            <?= $edit_mode ? 'Update Slider' : 'Tambah Slider' ?>
        </button>
        <?php if ($edit_mode): ?>
            <a href="slider_iklan.php" style="margin-left: 10px; padding: 10px 20px; text-decoration: none; border: 1px solid #ccc;">Batal</a>
        <?php endif; ?>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; max-width: 800px;">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = $slider->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td><img src="../uploads/<?= htmlspecialchars($row['file_gambar']) ?>" alt="<?= htmlspecialchars($row['judul']) ?>" style="max-width: 200px;"></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>" style="margin-right: 10px;">Edit</a>
                        <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus slide ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>