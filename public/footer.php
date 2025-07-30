<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quinn Fashion Footer</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</head>
<body>
  <div class="main-content">
    <!-- Konten halaman utama website akan ada di sini -->
  </div>

  <!-- Footer Content Section dengan background putih -->
  <section class="footer-content-section">
    <div class="footer-container">
      <div class="footer-profile">
        <h3>Quinn Fashion</h3>
        <p>Quinn Fashion menyediakan berbagai produk fashion berkualitas dengan harga terbaik dan layanan terpercaya.</p>
        <div class="footer-socials">
          <a href="#" aria-label="Instagram" target="_blank"><i class="bx bxl-instagram"></i></a>
          <a href="#" aria-label="YouTube" target="_blank"><i class="bx bxl-youtube"></i></a>
          <a href="#" aria-label="Facebook" target="_blank"><i class="bx bxl-facebook-circle"></i></a>
          <a href="#" aria-label="TikTok" target="_blank"><i class="bx bxl-tiktok"></i></a>
        </div>
      </div>
      <div class="footer-links">
        <div>
          <h4>Informasi</h4>
          <ul>
            <li><a href="#">Tentang Kami</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Karir</a></li>
            <li><a href="#">Pusat Bantuan</a></li>
          </ul>
        </div>
        <div>
          <h4>Sumber Daya</h4>
          <ul>
            <li><a href="#">Support 24/7</a></li>
            <li><a href="#">Panduan Belanja</a></li>
            <li><a href="#">Ketentuan & Kebijakan</a></li>
            <li><a href="#">FAQ</a></li>
          </ul>
        </div>
        <div>
          <h4>Navigasi</h4>
          <ul>
            <li><a href="#">Beranda</a></li>
            <li><a href="#">Produk</a></li>
            <li><a href="#">Tentang Quinn</a></li>
            <li><a href="#">Kontak</a></li>
          </ul>
        </div>
        <div>
          <h4>Official Store</h4>
          <div class="store-logos">
            <a href="https://shopee.co.id/" target="_blank" aria-label="Shopee Official Store">
              <img src="https://upload.wikimedia.org/wikipedia/commons/b/b3/Shopee_logo_white.png" alt="Shopee Logo" />
            </a>
            <a href="https://www.lazada.co.id/" target="_blank" aria-label="Lazada Official Store">
              <img src="https://upload.wikimedia.org/wikipedia/commons/e/e9/Lazada_Logo_2023.png" alt="Lazada Logo" />
            </a>
            <a href="https://www.tokopedia.com/" target="_blank" aria-label="Tokopedia Official Store">
              <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Tokopedia_Logo.svg/2560px-Tokopedia_Logo.svg.png" alt="Tokopedia Logo" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer Copyright dengan background ungu -->
  <footer class="footer-copyright">
    <div class="footer-bottom">
      <p>&copy; 2025 Quinn Fashion. All Rights Reserved.</p>
    </div>
  </footer>

<style>
  /* Tambahan untuk sticky footer */
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }

  body {
    display: flex;
    flex-direction: column;
  }

  .main-content {
    flex: 1;
  }

  /* Footer Content Section - Background Putih */
  .footer-content-section {
    background-color: #ffffff;
    color: #333333;
    padding: 3rem 1rem;
    margin-top: 4rem; /* Jarak dari konten di atasnya */
    font-family: 'Inter', sans-serif;
    border-top: 1px solid #e5e5e5;
  }

  .footer-content-section a {
    color: #666666;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .footer-content-section a:hover {
    color: #ff6f91; /* pink coral saat hover */
    text-decoration: underline;
  }

  .footer-container {
    max-width: 1200px;
    margin: auto;
    display: flex;
    flex-wrap: wrap;
    gap: 2.5rem;
    justify-content: space-between;
    align-items: flex-start;
  }

  .footer-profile {
    flex: 1 1 250px;
    min-width: 250px;
  }

  .footer-profile h3 {
    font-weight: 700;
    margin-bottom: 1rem;
    color: #3b2e5a;
    font-size: 1.6rem;
  }

  .footer-profile p {
    color: #555555;
    line-height: 1.6;
    margin-bottom: 1.2rem;
    font-size: 0.95rem;
  }

  .footer-socials {
    display: flex;
    gap: 0.8rem;
  }

  .footer-socials a {
    display: inline-flex;
    width: 38px;
    height: 38px;
    background-color: #3b2e5a;
    border-radius: 50%;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 1.35rem;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  .footer-socials a:hover {
    background-color: #ff6f91;
    color: white;
    transform: translateY(-3px);
  }

  .footer-links {
    flex: 3 1 650px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 2.5rem;
  }

  .footer-links > div {
    flex: 1 1 140px;
    min-width: 120px;
  }

  .footer-links h4 {
    margin-bottom: 1rem;
    font-weight: 600;
    color: #3b2e5a;
    border-bottom: 2px solid #ff6f91;
    padding-bottom: 0.4rem;
    font-size: 1.15rem;
  }

  .footer-links ul {
    list-style: none;
    padding: 0;
  }

  .footer-links ul li {
    margin-bottom: 0.6rem;
  }

  .footer-links ul li a {
    font-size: 0.95rem;
    line-height: 1.5;
  }

  .store-logos {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .store-logos a {
    display: block;
    padding: 0.5rem;
    text-decoration: none;
    transition: transform 0.2s ease-in-out;
    line-height: 1;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e5e5e5;
  }

  .store-logos a:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .store-logos img {
    width: 90px;
    height: auto;
    display: block;
    max-width: 100%;
    filter: brightness(0.8) contrast(1.1);
    transition: filter 0.2s ease;
  }

  .store-logos a:hover img {
    filter: brightness(1) contrast(1.2);
  }

  /* Footer Copyright - Background Ungu */
  .footer-copyright {
    margin-top: auto; /* Ini yang membuat footer menempel di bawah */
    background-color: #3b2e5a; /* ungu gelap */
    color: #f0e9f5; /* putih lembut */
    padding: 1.5rem 1rem;
    font-family: 'Inter', sans-serif;
  }

  .footer-bottom {
    text-align: center;
    font-size: 0.75rem; /* Ukuran font diperkecil */
    color: #b0a3c2; /* Warna berbeda untuk copyright */
    max-width: 1200px;
    margin: 0 auto;
  }

  .footer-bottom p {
    margin: 0;
  }

  /* Responsive Mobile Styles (max-width: 768px) */
  @media (max-width: 768px) {
    .footer-content-section {
      padding: 2rem 0.5rem; /* Padding footer dikurangi */
      margin-top: 2.5rem; /* Margin di mobile lebih kecil */
    }

    .footer-container {
      flex-direction: column;
      align-items: flex-start;
      gap: 1.5rem; /* Jarak antar bagian utama dikurangi */
    }

    .footer-profile {
      width: 100%;
      min-width: unset;
      padding: 0 0.5rem;
    }

    .footer-profile h3 {
      font-size: 1.2rem; /* Ukuran judul profil dikurangi */
      margin-bottom: 0.5rem;
    }

    .footer-profile p {
      font-size: 0.85rem; /* Ukuran font paragraf dikurangi */
      line-height: 1.4;
      margin-bottom: 0.8rem;
    }

    .footer-socials {
      gap: 0.6rem; /* Jarak antar ikon sosmed dikurangi */
      margin-top: 0.5rem;
    }

    .footer-socials a {
      width: 30px; /* Ukuran ikon sosmed dikurangi */
      height: 30px;
      font-size: 1rem;
    }

    .footer-links {
      width: 100%;
      flex-wrap: nowrap;
      overflow-x: auto;
      padding: 0.4rem 0; /* Padding vertikal dikurangi */
      -webkit-overflow-scrolling: touch;
      margin-top: 0.8rem;
      scroll-snap-type: x mandatory;
      scrollbar-width: none;
      gap: 1.2rem; /* Jarak antar kolom link dikurangi */
    }

    .footer-links::-webkit-scrollbar {
      display: none;
    }

    .footer-links > div {
      flex: 0 0 auto;
      min-width: 55%; /* Kolom link dibuat sedikit lebih kecil agar lebih banyak terlihat */
      max-width: 65%;
      scroll-snap-align: start;
      padding: 0 0.5rem;
    }

    .footer-links > div:first-child {
      padding-left: 0.5rem;
    }

    .footer-links > div:last-child {
      padding-right: 0.5rem;
    }

    .footer-links h4 {
      font-size: 0.95rem; /* Ukuran judul kolom link dikurangi */
      margin-bottom: 0.5rem;
      padding-bottom: 0.2rem;
    }

    .footer-links ul li {
      margin-bottom: 0.3rem; /* Jarak antar item list dikurangi */
    }

    .footer-links ul li a {
      font-size: 0.8rem; /* Ukuran font link dikurangi */
    }

    .store-logos {
      flex-direction: column;
      gap: 0.6rem; /* Jarak antar logo dikurangi */
    }

    .store-logos img {
      width: 65px; /* Lebar logo dikurangi */
    }

    .footer-copyright {
      padding: 1rem 0.5rem;
    }

    .footer-bottom {
      font-size: 0.65rem; /* Ukuran font copyright diperkecil lagi */
      padding: 0 0.5rem;
    }
  }
</style>

</body>
</html>