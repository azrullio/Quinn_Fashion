<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    list-style: none;
    text-decoration: none;
  }

  body {
    background-color: #000;
    color: #fff;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: end;
  }

  footer {
    display: flex;
    align-items: center;
    width: 100%;
    border-top: 2px solid #fff;
  }

  footer .container {
    padding: 4rem;
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    height: 100%;
    gap: 4rem;
  }

  footer .container .profile {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 25%;
  }

  footer .container .link-container {
    width: 70%;
    display: flex;
    gap: 4rem;
  }

  footer .container .link-container .information,
  footer .container .link-container .resource,
  footer .container .link-container .navigation {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 30%;
  }

  footer .container .profile .logo {
    display: flex;
    align-items: center;
    gap: 4px;
  }

  footer .container .profile .logo img {
    width: 50px;
    height: 50px;
  }

  footer .container .profile .logo a {
    font-size: 24px;
    font-weight: bold;
    color: #fff;
  }

  footer .container .profile p {
    font-weight: 400;
    margin-bottom: 8px;
  }

  footer .container .profile .social-media-container {
    display: flex;
    gap: 8px;
  }

  footer .container .profile .social-media-container .social-media {
    cursor: pointer;
    width: 35px;
    height: 35px;
    padding: 6px;
    border-radius: 50%;
    background-color: #191919;
    transition: transform 1s linear;
  }

  footer .container .profile .social-media-container .social-media:hover {
    transform: rotateY(360deg);
  }

  footer .container .link-container .information h1,
  footer .container .link-container .resource h1,
  footer .container .link-container .navigation h1 {
    font-size: 24px;
    font-weight: bold;
    color: #fff;
    height: 50px;
    display: flex;
    align-items: center;
  }

  footer .container .link-container .information ul,
  footer .container .link-container .resource ul,
  footer .container .link-container .navigation ul {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  footer .container .link-container .information ul li,
  footer .container .link-container .resource ul li,
  footer .container .link-container .navigation ul li {
    cursor: pointer;
  }

  footer .container .link-container .information ul li::after,
  footer .container .link-container .resource ul li::after,
  footer .container .link-container .navigation ul li::after {
    content: "";
    display: block;
    border-bottom: 3px solid #fff;
    width: 40%;
    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.6s linear;
    margin-top: 4px;
  }

  footer .container .link-container .information ul li:hover::after,
  footer .container .link-container .resource ul li:hover::after,
  footer .container .link-container .navigation ul li:hover::after {
    transform: scaleX(1);
    transform-origin: left;
    transition: transform 0.6s linear;
  }

  footer .container .link-container .information ul li a,
  footer .container .link-container .resource ul li a,
  footer .container .link-container .navigation ul li a {
    color: #fff;
  }

  @media screen and (max-width: 1536px) {
    footer .container {
      gap: 2rem;
    }
    footer .container .link-container {
      gap: 2rem;
    }
  }

  @media screen and (max-width: 1024px) {
    footer .container .link-container .information ul li::after,
    footer .container .link-container .resource ul li::after,
    footer .container .link-container .navigation ul li::after {
      width: 60%;
    }
  }

  @media screen and (max-width: 767px) {
    footer .container {
      padding: 1rem 2rem;
    }
    footer .container .profile {
      width: 50%;
    }
    footer .container .link-container {
      flex-direction: column;
      width: 100%;
    }

    footer .container .link-container .information ul li::after,
    footer .container .link-container .resource ul li::after,
    footer .container .link-container .navigation ul li::after {
      width: 100%;
    }
  }

  @media screen and (max-width: 610px) {
    footer .container .profile,
    footer .container .link-container {
      width: 100%;
    }

    footer .container .link-container .information,
    footer .container .link-container .resource,
    footer .container .link-container .navigation {
      width: 30%;
    }
  }

  @media screen and (max-width: 450px) {
    footer .container .link-container .information,
    footer .container .link-container .resource,
    footer .container .link-container .navigation {
      width: 50%;
    }
  }
</style>

<footer>
  <div class="container">
    <div class="profile">
      <div class="logo">
        <img src="logo.png" alt="logo" />
        <a href="#">Novaspace</a>
      </div>
      <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Distinctio quas libero expedita, similique est sed.</p>
      <div class="social-media-container">
        <div class="social-media">
          <box-icon color="#fff" type="logo" name="instagram"></box-icon>
        </div>
        <div class="social-media">
          <box-icon color="#fff" name="youtube" type="logo"></box-icon>
        </div>
        <div class="social-media">
          <box-icon color="#fff" name="facebook-circle" type="logo"></box-icon>
        </div>
        <div class="social-media">
          <box-icon color="#fff" type="logo" name="tiktok"></box-icon>
        </div>
      </div>
    </div>

    <div class="link-container">
      <div class="information">
        <h1>Information</h1>
        <ul>
          <li><a href="#">Our Company</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Shop</a></li>
        </ul>
      </div>

      <div class="resource">
        <h1>Resources</h1>
        <ul>
          <li><a href="#">Support 24/7</a></li>
          <li><a href="#">Help Center</a></li>
          <li><a href="#">Terms & Condition</a></li>
          <li><a href="#">How-to Instructions</a></li>
        </ul>
      </div>

      <div class="navigation">
        <h1>Navigation</h1>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">Planet</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>

<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
