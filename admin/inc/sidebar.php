<style>
    /* Global Styles (DIPINDAHKAN KE SINI UNTUK KONSISTENSI) */
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa; /* Warna latar belakang umum */
    }

    /* Styles for the Sidebar */
    .sidebar {
        height: 100vh; /* Menggunakan vh untuk tinggi penuh viewport */
        width: 250px; /* Fixed width */
        position: fixed; /* Fixed position, stay in place when scrolling */
        top: 0;
        left: 0;
        background-color: #1a365d; /* Dark background color */
        padding-top: 20px;
        color: #f8f9fa; /* Light text color */
        box-shadow: 2px 0 5px rgba(0,0,0,0.1); /* Subtle shadow */
        z-index: 1000; /* Ensure it stays on top */
        overflow-y: auto; /* Enable scrolling if content is too long */
        transition: width 0.3s ease; /* Transisi untuk responsive */
    }

    .sidebar-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 0 15px;
        font-size: 1.5rem;
        font-weight: bold;
        color: #00d1b2; /* A accent color for the brand */
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .sidebar-header .logo-icon {
        font-size: 1.8rem;
    }

    .sidebar ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .sidebar ul li a {
        padding: 15px 25px;
        text-decoration: none;
        font-size: 1.1em;
        color: #adb5bd; /* Lighter text for links */
        display: block;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px; /* Space between icon and text */
    }

    .sidebar ul li a:hover,
    .sidebar ul li.active a {
        color: #ffffff; /* White text on hover/active */
        background-color: #495057; /* Slightly lighter dark background */
        border-left: 5px solid #00d1b2; /* Accent border on hover/active */
        padding-left: 20px; /* Adjust padding for border */
    }

    .sidebar ul li a .fas {
        min-width: 25px; /* Ensure icons align */
        text-align: center;
    }

    .sidebar .logout {
        margin-top: 40px;
        border-top: 1px solid #495057;
        padding-top: 20px;
    }

    .sidebar .logout a {
        color: #ffffffff; /* Warning color for logout */
    }

    .sidebar .logout a:hover {
        background-color: #dc3545; /* Red on hover for logout */
        color: white;
        border-left-color: #dc3545;
    }

    /* Styles for Main Content (Offset untuk sidebar fixed) */
    .main-content {
        margin-left: 250px; /* Offset for the fixed sidebar, SESUAIKAN DENGAN LEBAR SIDEBAR */
        padding: 2.5rem; /* Add some padding inside the content area */
        box-sizing: border-box; /* Include padding in element's total width and height */
        min-height: 100vh; /* Ensure content area takes full height */
    }
    
    /* Responsive adjustments for smaller screens */
    @media (max-width: 768px) {
        .sidebar {
            width: 70px; /* Lebar lebih kecil untuk ikon saja */
            overflow-x: hidden; /* Sembunyikan teks */
        }
        .sidebar:hover {
            width: 250px; /* Munculkan teks saat hover */
        }
        .sidebar .sidebar-header span,
        .sidebar ul li a span {
            display: none; /* Sembunyikan teks secara default */
        }
        .sidebar:hover .sidebar-header span,
        .sidebar:hover ul li a span {
            display: inline; /* Tampilkan teks saat hover */
        }
        .sidebar-header {
            justify-content: center; /* Pusatkan ikon */
        }
        .sidebar ul li a {
            padding: 15px 0; /* Sesuaikan padding */
            justify-content: center; /* Pusatkan ikon */
            gap: 0; /* Hapus gap */
        }
        .sidebar ul li a .fas {
            font-size: 1.4em; /* Ukuran ikon lebih besar */
            min-width: unset; /* Reset min-width */
        }
        .sidebar .logout a {
            justify-content: center;
            gap: 0;
        }

        .main-content {
            margin-left: 70px; /* Sesuaikan offset main content */
        }
    }

    @media (max-width: 576px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative; /* Sidebar menjadi relatif pada mobile */
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding-top: 10px;
        }
        .sidebar ul {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .sidebar ul li {
            flex: 1 1 auto;
            min-width: 100px;
            max-width: 150px;
        }
        .sidebar ul li a {
            padding: 10px 15px;
            font-size: 0.9em;
            justify-content: center;
            flex-direction: column;
            gap: 5px;
            text-align: center;
            border-left: none;
            border-bottom: 3px solid transparent;
        }
        .sidebar ul li a:hover,
        .sidebar ul li.active a {
            border-left: none;
            border-bottom: 3px solid #00d1b2;
            padding-left: 15px;
        }
        .sidebar ul li a .fas {
            font-size: 1.2em;
            margin-bottom: 5px;
        }
        .sidebar-header {
            margin-bottom: 15px;
            font-size: 1.2rem;
            flex-direction: column;
        }
        .sidebar-header .logo-icon {
            font-size: 1.5rem;
        }
        .sidebar .logout {
            margin-top: 10px;
            padding-top: 10px;
            border-top: none;
        }
        .sidebar .logout ul {
            display: block;
        }
        .sidebar .logout li {
            min-width: auto;
            max-width: none;
        }
        .sidebar .logout a {
            border-bottom: none;
            justify-content: flex-start;
            flex-direction: row;
        }
        .main-content {
            margin-left: 0; /* Pada mobile, main content tidak perlu offset */
            padding: 15px;
        }
    }
</style>

<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-store logo-icon"></i>
        <span>Admin Panel</span>
    </div>
    <ul class="nav flex-column">
       <li class="nav-item" data-page="index.php">
            <a href="index.php" class="nav-link"><i class="fas fa-th-large"></i> <span>Dashboard</span></a>
        </li>
        <li class="nav-item" data-page="kategori.php">
            <a href="kategori.php" class="nav-link"><i class="fas fa-tags"></i> <span>Kategori</span></a>
        </li>
        <li class="nav-item" data-page="tambah_barang.php">
            <a href="tambah_barang.php" class="nav-link"><i class="fas fa-plus-square"></i> <span>Tambah Barang</span></a>
        </li>
        <li class="nav-item" data-page="slider_iklan.php">
            <a href="slider_iklan.php" class="nav-link"><i class="fas fa-images"></i> <span>Slider Iklan</span></a>
        </li>
        <li class="nav-item" data-page="keuangan.php">
            <a href="keuangan.php" class="nav-link"><i class="fas fa-wallet"></i> <span>Keuangan</span></a>
        </li>
    </ul>
    <ul class="logout">
        <li>
            <a href="logout.php" class="nav-link" onclick="return confirm('Apakah Anda yakin ingin logout?')"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
        </li>
    </ul>
</div>