<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include 'inc/db.php'; // Koneksi database

// Ambil data barang beserta kategori
$barang_query = mysqli_query($conn, "SELECT barang.*, kategori.nama_kategori FROM barang JOIN kategori ON barang.kategori_id = kategori.id");

$kategoriBarang = [];
if ($barang_query) {
    while ($row = mysqli_fetch_assoc($barang_query)) {
        $kategoriBarang[$row['nama_kategori']][] = $row;
    }
} else {
    error_log("Error fetching barang data: " . mysqli_error($conn));
}

// Cek jika request AJAX untuk load konten main-content saja
// Pastikan hanya konten dashboard yang dirender saat 'index.php' diminta melalui AJAX
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    // Jika ada session message dari halaman sebelumnya (misal: setelah tambah/edit/hapus barang)
    $message = '';
    $message_type = '';
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $message_type = $_SESSION['message_type'];
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }

    if ($message) {
        echo "<div class='alert alert-custom alert-{$message_type}' role='alert'>";
        if ($message_type == 'success') {
            echo "<i class='fas fa-check-circle'></i>";
        } elseif ($message_type == 'danger') {
            echo "<i class='fas fa-exclamation-circle'></i>";
        } elseif ($message_type == 'warning') {
            echo "<i class='fas fa-exclamation-triangle'></i>";
        } elseif ($message_type == 'info') {
            echo "<i class='fas fa-info-circle'></i>";
        }
        echo "{$message}</div>";
    }

    echo "<div class='d-flex justify-content-between align-items-center mb-4'>
              <h2 class='fw-bold'>Data Barang <span style='color:#d63384'>Quinn Fashion</span></h2>
          </div>"; // Hapus tombol tambah barang di sini

    if (empty($kategoriBarang)) {
        echo "<div class='alert alert-info text-center' role='alert'>
                  <i class='fas fa-info-circle me-2'></i>Belum ada data barang yang ditambahkan.
              </div>";
    } else {
        foreach ($kategoriBarang as $kategori => $items) {
            echo "<h4>" . htmlspecialchars($kategori) . "</h4>";
            echo "<div class='scrolling-wrapper'>";
            foreach ($items as $row) {
                echo "<div class='card product-card'>";

                if (!empty(trim($row['promo']))) {
                    echo "<span class='badge-promo'>" . htmlspecialchars($row['promo']) . "</span>";
                }

                echo "<img src='img/" . htmlspecialchars($row['gambar']) . "' class='card-img-top' alt='Gambar " . htmlspecialchars($row['nama_barang']) . "'>";
                echo "<span class='badge-kategori'>" . htmlspecialchars($kategori) . "</span>";

                echo "<div class='card-body'>";
                echo "<h5 class='card-title text-truncate'>" . htmlspecialchars($row['nama_barang']) . "</h5>";
                echo "<p class='card-text mb-1 text-muted'>Rp" . number_format($row['harga'], 0, ',', '.') . "</p>";
                echo "<p class='card-text mb-1'><strong>Stok:</strong> " . htmlspecialchars($row['stok']) . "</p>";

                $deskripsi = $row['deskripsi'];
                $potong = mb_substr($deskripsi, 0, 100);
                $panjang = mb_strlen($deskripsi) > 100;
                $id = $row['id'];

                echo "<p class='card-text small mb-2 text-secondary'>
                            <span id='short-{$id}'>" . htmlspecialchars($potong) . ($panjang ? '... ' : '') . "</span>";

                if ($panjang) {
                    echo "<span id='full-{$id}' style='display:none;'>" . htmlspecialchars($deskripsi) . "</span>
                                <a href='javascript:void(0);' class='text-primary' onclick='toggleDeskripsi({$id})' id='toggle-{$id}'>Selengkapnya</a>";
                }
                echo "</p>";

                echo "<div class='d-grid gap-2 mb-2'>
                                <a href='" . htmlspecialchars($row['link_shopee']) . "' target='_blank' class='btn btn-sm btn-outline-danger'><i class='fas fa-shopping-bag me-1'></i> Shopee</a>
                                <a href='" . htmlspecialchars($row['link_lazada']) . "' target='_blank' class='btn btn-sm btn-outline-primary'><i class='fas fa-box me-1'></i> Lazada</a>
                                <a href='" . htmlspecialchars($row['link_tokopedia']) . "' target='_blank' class='btn btn-sm btn-outline-success'><i class='fas fa-store me-1'></i> Tokopedia</a>
                            </div>";

                echo "<div class='d-flex justify-content-between'>
                                <a href='edit_barang.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-warning btn-sm w-50 me-1'><i class='fas fa-edit me-1'></i> Edit</a>
                                <a href='hapus_barang.php?id=" . htmlspecialchars($row['id']) . "' onclick=\"return confirm('Yakin ingin hapus barang ini?')\" class='btn btn-danger btn-sm w-50'><i class='fas fa-trash-alt me-1'></i> Hapus</a>
                            </div>";

                echo "</div></div>";
            }
            echo "</div>";
        }
    }
    // Stop script supaya tidak render halaman penuh
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - Quinn Fashion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        /* CSS untuk tampilan daftar barang */
        .scrolling-wrapper {
            overflow-x: auto;
            display: flex;
            gap: 1.5rem;
            padding-bottom: 1rem;
        }
        .scrolling-wrapper::-webkit-scrollbar {
            height: 8px;
        }
        .scrolling-wrapper::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }
        .product-card {
            min-width: 270px;
            max-width: 270px;
            flex: 0 0 auto;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .product-card img {
            height: 200px;
            object-fit: cover;
        }
        .badge-kategori {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: #fff;
            padding: 0.25rem 0.6rem;
            font-size: 0.75rem;
            border-radius: 8px;
        }
        .badge-promo {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #dc3545;
            color: white;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 8px;
            z-index: 10;
            text-transform: uppercase;
            box-shadow: 0 0 6px rgba(220, 53, 69, 0.7);
        }
        .btn-outline-danger:hover,
        .btn-outline-primary:hover,
        .btn-outline-success:hover {
            transform: scale(1.05);
        }
        h4 {
            color: #333;
            font-weight: 600;
            margin-top: 2rem;
            border-left: 4px solid #0d6efd;
            padding-left: 0.5rem;
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
        .alert-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .alert-info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    </style>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>

    <div class="main-content" id="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Data Barang <span style="color:#d63384">Quinn Fashion</span></h2>
            </div>

        <?php
        // Ambil pesan feedback dari session jika ada
        $message = '';
        $message_type = '';
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $message_type = $_SESSION['message_type'];
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }

        if ($message) {
            echo "<div class='alert alert-custom alert-{$message_type}' role='alert'>";
            if ($message_type == 'success') {
                echo "<i class='fas fa-check-circle'></i>";
            } elseif ($message_type == 'danger') {
                echo "<i class='fas fa-exclamation-circle'></i>";
            } elseif ($message_type == 'warning') {
                echo "<i class='fas fa-exclamation-triangle'></i>";
            } elseif ($message_type == 'info') {
                echo "<i class='fas fa-info-circle'></i>";
            }
            echo "{$message}</div>";
        }
        ?>

        <?php
        // Konten daftar barang akan selalu dimuat di sini saat halaman utama
        if (empty($kategoriBarang)) {
            echo "<div class='alert alert-info text-center' role='alert'>
                    <i class='fas fa-info-circle me-2'></i>Belum ada data barang yang ditambahkan.
                </div>";
        } else {
            foreach ($kategoriBarang as $kategori => $items) {
                echo "<h4>" . htmlspecialchars($kategori) . "</h4>";
                echo "<div class='scrolling-wrapper'>";
                foreach ($items as $row) {
                    echo "<div class='card product-card'>";

                    if (!empty(trim($row['promo']))) {
                        echo "<span class='badge-promo'>" . htmlspecialchars($row['promo']) . "</span>";
                    }

                    echo "<img src='img/" . htmlspecialchars($row['gambar']) . "' class='card-img-top' alt='Gambar " . htmlspecialchars($row['nama_barang']) . "'>";
                    echo "<span class='badge-kategori'>" . htmlspecialchars($kategori) . "</span>";

                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title text-truncate'>" . htmlspecialchars($row['nama_barang']) . "</h5>";
                    echo "<p class='card-text mb-1 text-muted'>Rp" . number_format($row['harga'], 0, ',', '.') . "</p>";
                    echo "<p class='card-text mb-1'><strong>Stok:</strong> " . htmlspecialchars($row['stok']) . "</p>";

                    $deskripsi = $row['deskripsi'];
                    $potong = mb_substr($deskripsi, 0, 100);
                    $panjang = mb_strlen($deskripsi) > 100;
                    $id = $row['id'];

                    echo "<p class='card-text small mb-2 text-secondary'>
                                <span id='short-{$id}'>" . htmlspecialchars($potong) . ($panjang ? '... ' : '') . "</span>";

                    if ($panjang) {
                        echo "<span id='full-{$id}' style='display:none;'>" . htmlspecialchars($deskripsi) . "</span>
                                    <a href='javascript:void(0);' class='text-primary' onclick='toggleDeskripsi({$id})' id='toggle-{$id}'>Selengkapnya</a>";
                    }
                    echo "</p>";

                    echo "<div class='d-grid gap-2 mb-2'>
                                <a href='" . htmlspecialchars($row['link_shopee']) . "' target='_blank' class='btn btn-sm btn-outline-danger'><i class='fas fa-shopping-bag me-1'></i> Shopee</a>
                                <a href='" . htmlspecialchars($row['link_lazada']) . "' target='_blank' class='btn btn-sm btn-outline-primary'><i class='fas fa-box me-1'></i> Lazada</a>
                                <a href='" . htmlspecialchars($row['link_tokopedia']) . "' target='_blank' class='btn btn-sm btn-outline-success'><i class='fas fa-store me-1'></i> Tokopedia</a>
                            </div>";

                    echo "<div class='d-flex justify-content-between'>
                                <a href='edit_barang.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-warning btn-sm w-50 me-1'><i class='fas fa-edit me-1'></i> Edit</a>
                                <a href='hapus_barang.php?id=" . htmlspecialchars($row['id']) . "' onclick=\"return confirm('Yakin ingin hapus barang ini?')\" class='btn btn-danger btn-sm w-50'><i class='fas fa-trash-alt me-1'></i> Hapus</a>
                            </div>";

                    echo "</div></div>";
                }
                echo "</div>";
            }
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleDeskripsi(id) {
            const shortText = document.getElementById('short-' + id);
            const fullText = document.getElementById('full-' + id);
            const toggleBtn = document.getElementById('toggle-' + id);

            const isExpanded = fullText.style.display === 'inline';

            shortText.style.display = isExpanded ? 'inline' : 'none';
            fullText.style.display = isExpanded ? 'none' : 'inline';
            toggleBtn.textContent = isExpanded ? 'Selengkapnya' : 'Sembunyikan';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const mainContentDiv = document.getElementById('main-content');
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            const navItems = document.querySelectorAll('.sidebar .nav-item');

            function loadContent(pageFile) {
                // Hapus kelas 'active' dari semua nav-item
                navItems.forEach(item => item.classList.remove('active'));

                // Tambahkan kelas 'active' ke nav-item yang sedang aktif
                const activeNavItem = document.querySelector(`.sidebar .nav-item[data-page="${pageFile}"]`);
                if (activeNavItem) {
                    activeNavItem.classList.add('active');
                }

                // Load konten via AJAX (tambahkan ?ajax=1 agar server hanya kirim konten utama)
                fetch(pageFile + '?ajax=1')
                    .then(response => {
                        if (!response.ok) {
                            // Jika ada response 404 atau 500
                            if (response.status === 404) {
                                throw new Error(`Halaman '${pageFile}' tidak ditemukan.`);
                            } else {
                                throw new Error(`Terjadi masalah jaringan atau server (Kode: ${response.status}).`);
                            }
                        }
                        return response.text();
                    })
                    .then(html => {
                        mainContentDiv.innerHTML = html;

                        // Re-evaluate scripts in the loaded HTML
                        mainContentDiv.querySelectorAll('script').forEach(oldScript => {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading content:', error);
                        mainContentDiv.innerHTML = `<div class="alert alert-danger" role="alert">Gagal memuat halaman: ${error.message}. Silakan coba lagi.</div>`;
                    });
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Pastikan elemen parent .nav-item memiliki atribut data-page
                    const pageToLoad = this.closest('.nav-item').dataset.page;
                    if (pageToLoad) {
                        loadContent(pageToLoad);
                    }
                });
            });

            // Tandai Dashboard aktif saat awal dimuat (atau sesuai URL)
            // Anda mungkin ingin menambahkan logika untuk memuat konten berdasarkan hash di URL jika ingin deep linking
            const initialPage = window.location.pathname.split('/').pop() || 'index.php';
            const dashboardNavItem = document.querySelector(`.sidebar .nav-item[data-page="${initialPage}"]`);
            if (dashboardNavItem) {
                dashboardNavItem.classList.add('active');
            } else {
                 // Fallback: Jika tidak ada data-page yang cocok, tandai dashboard sebagai aktif secara default
                const defaultDashboard = document.querySelector('.sidebar .nav-item[data-page="index.php"]');
                if (defaultDashboard) {
                    defaultDashboard.classList.add('active');
                }
            }
        });
    </script>
</body>
</html>