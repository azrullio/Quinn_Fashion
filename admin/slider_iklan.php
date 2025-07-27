<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'inc/db.php';

// Tambah slider
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
            $error = "Format file tidak didukung.";
        }
    } else {
        $error = "Silakan pilih gambar.";
    }
}

// Hapus slider
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

// Edit slider (update)
if (isset($_POST['edit'])) {
    $id = (int)$_POST['id'];
    $judul = $conn->real_escape_string($_POST['judul']);
    $gambar_file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    if (!empty($gambar_file)) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($tmp);

        if (in_array($file_type, $allowed_types)) {
            $path = '../uploads/' . basename($gambar_file);
            if (move_uploaded_file($tmp, $path)) {
                $conn->query("UPDATE slider_iklan SET judul='$judul', file_gambar='$gambar_file' WHERE id=$id");
            }
        }
    } else {
        $conn->query("UPDATE slider_iklan SET judul='$judul' WHERE id=$id");
    }

    header("Location: slider_iklan.php?edited=1");
    exit;
}

$slider = $conn->query("SELECT * FROM slider_iklan ORDER BY id DESC");
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>

<!-- Tambahkan link Bootstrap di head jika belum -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="main-content">
    <h2 class="mb-4">Manajemen Slider Iklan</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Slider berhasil ditambahkan!</div>
    <?php elseif (isset($_GET['deleted'])): ?>
        <div class="alert alert-danger">Slider berhasil dihapus!</div>
    <?php elseif (isset($_GET['edited'])): ?>
        <div class="alert alert-primary">Slider berhasil diubah!</div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Form Tambah -->
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <label class="form-label">Judul Slide</label>
            <input type="text" name="judul" required class="form-control" style="max-width: 400px;">
        </div>
        <div class="mb-3">
            <label class="form-label">Upload Gambar (.jpg, .png, .gif)</label>
            <input type="file" name="gambar" accept="image/*" required class="form-control" style="max-width: 400px;">
        </div>
        <button type="submit" name="upload" class="btn btn-success">Tambah Slider</button>
    </form>

    <!-- Tabel -->
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
                <?php $no = 1; while ($row = $slider->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['judul']) ?></td>
                        <td><img src="../uploads/<?= htmlspecialchars($row['file_gambar']) ?>" style="max-width: 150px;" class="img-thumbnail"></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editSlider(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['judul'])) ?>')" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                            <a href="?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus slide ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Slider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <input type="hidden" name="id" id="editId">
                  <div class="mb-3">
                      <label class="form-label">Judul</label>
                      <input type="text" name="judul" id="editJudul" class="form-control" required>
                  </div>
                  <div class="mb-3">
                      <label class="form-label">Ganti Gambar (opsional)</label>
                      <input type="file" name="gambar" class="form-control" accept="image/*">
                  </div>
              </div>
              <div class="modal-footer">
                <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              </div>
          </form>
        </div>
      </div>
    </div>
</div>

<script>
function editSlider(id, judul) {
    document.getElementById('editId').value = id;
    document.getElementById('editJudul').value = judul;
}
</script>   
