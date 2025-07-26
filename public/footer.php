<footer>
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
    </div>
  </div>
</footer>

<style>
  /* Base styles for desktop */
  footer {
    margin-top: 3rem;
    background-color: #1a365d;
    color: #f7fafc;
    padding: 3rem 1rem;
    font-family: 'Poppins', sans-serif;
  }

  footer a {
    color: #f7fafccc; /* Link color for better contrast */
    text-decoration: none;
    transition: color 0.3s ease;
  }

  footer a:hover {
    color: #e53e3e; /* Accent color on hover */
    text-decoration: underline;
  }

  .footer-container {
    max-width: 1200px;
    margin: auto;
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    justify-content: space-between;
  }

  .footer-profile {
    flex: 1 1 280px;
    min-width: 280px;
  }

  .footer-profile h3 {
    font-weight: 700;
    margin-bottom: 1rem;
    color: #f7fafc;
  }

  .footer-profile p {
    color: #f7fafccc;
    line-height: 1.5;
    margin-bottom: 1rem;
  }

  .footer-socials {
    display: flex;
    gap: 1rem;
  }

  .footer-socials a {
    display: inline-flex;
    width: 36px;
    height: 36px;
    background-color: #2d5a87;
    border-radius: 50%;
    justify-content: center;
    align-items: center;
    color: #f7fafc;
    font-size: 1.25rem;
    transition: background-color 0.3s ease;
  }

  .footer-socials a:hover {
    background-color: #e53e3e;
    color: white;
  }

  .footer-links {
    flex: 2 1 600px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 2rem;
  }

  .footer-links > div {
    min-width: 140px;
  }

  .footer-links h4 {
    margin-bottom: 1rem;
    font-weight: 600;
    color: #f7fafc;
    border-bottom: 2px solid #e53e3e;
    padding-bottom: 0.3rem;
  }

  .footer-links ul {
    list-style: none;
    padding: 0;
  }

  .footer-links ul li {
    margin-bottom: 0.5rem;
  }

  /* Responsive Mobile Styles (max-width: 768px) */
  @media (max-width: 768px) {
    footer {
      padding: 1.5rem 0.75rem; /* Reduced overall padding for a tighter mobile fit */
    }

    .footer-container {
      flex-direction: column;
      align-items: flex-start; /* Align to start for mobile stacked view */
      text-align: left; /* Align text to left */
      gap: 1rem; /* Reduced gap between main sections */
    }

    .footer-profile {
      flex: unset;
      width: 100%;
      max-width: none;
      padding: 0 0.5rem; /* Consistent horizontal padding for profile content */
    }

    .footer-profile h3 {
      font-size: 1.2rem;
      margin-bottom: 0.4rem; /* Reduced margin below heading */
    }

    .footer-profile p {
      font-size: 0.85rem;
      line-height: 1.4; /* Slightly tighter line height */
      margin-bottom: 0.8rem; /* Adjusted margin below paragraph */
    }

    .footer-socials {
      justify-content: flex-start;
      gap: 0.6rem; /* Slightly smaller gap for social icons */
      margin-top: 0.5rem; /* Small margin above social icons */
    }

    .footer-socials a {
      width: 30px; /* Slightly smaller social icons */
      height: 30px;
      font-size: 1rem; /* Smaller icon font size */
    }

    .footer-links {
      flex: unset;
      width: 100%;
      display: flex;
      flex-wrap: nowrap;
      justify-content: flex-start;
      overflow-x: auto;
      padding: 0.5rem 0; /* Padding for scrollbar */
      -webkit-overflow-scrolling: touch;
      margin-top: 1rem; /* Space between profile and links */
      scroll-snap-type: x mandatory;
      scrollbar-width: none; /* Hide scrollbar for Firefox */
    }

    .footer-links::-webkit-scrollbar {
      display: none; /* Hide scrollbar for Webkit browsers */
    }

    .footer-links > div {
      flex: 0 0 auto;
      min-width: 70%; /* Show roughly one and a half columns at a time */
      max-width: 80%;
      scroll-snap-align: start;
      margin-right: 1.2rem; /* Slightly reduced space between categories */
      padding: 0 0.5rem; /* Consistent horizontal padding within each category */
    }

    /* Adjust padding for the first and last scrolling column to align with overall footer padding */
    .footer-links > div:first-child {
      padding-left: 0.75rem; /* Align first column with footer's left padding */
    }
    .footer-links > div:last-child {
      margin-right: 0.75rem; /* Ensure last column has consistent spacing from right edge */
    }

    .footer-links h4 {
      font-size: 0.9rem;
      margin-bottom: 0.4rem; /* Reduced margin below heading */
      border-bottom: 1px solid #e53e3e;
      padding-bottom: 0.15rem; /* Slightly thinner padding below border */
    }

    .footer-links ul li {
      margin-bottom: 0.2rem; /* Reduced spacing between list items for compactness */
    }

    .footer-links ul li a {
      font-size: 0.8rem;
    }
  }
</style>

<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>