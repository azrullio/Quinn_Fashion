<?php
session_start();
include 'inc/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $_SESSION['admin'] = $data['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Quinn Fashion</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins&display=swap">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #1a365d; /* Updated background color */
            background-size: cover;
            background-position: center;
        }

        section {
            position: relative;
            max-width: 400px;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            backdrop-filter: blur(25px);
            padding: 2rem 2.5rem;
            box-shadow: 0 0 25px rgba(0,0,0,0.1);
        }

        section h1 {
            font-size: 2rem;
            color: #fff;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .inputbox {
            position: relative;
            margin: 30px 0;
            border-bottom: 2px solid #fff;
        }

        .inputbox label {
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            color: #fff;
            font-size: 1rem;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .inputbox input:focus ~ label,
        .inputbox input:valid ~ label {
            top: -5px;
            font-size: 0.8rem;
        }

        .inputbox input {
            width: 100%;
            height: 40px;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1rem;
            padding: 0 35px 0 5px;
            color: #fff;
            backdrop-filter: none;
        }

        .inputbox ion-icon {
            position: absolute;
            right: 8px;
            color: #fff;
            font-size: 1.2rem;
            top: 10px;
        }

        input:-webkit-autofill {
            background: transparent !important;
            -webkit-box-shadow: 0 0 0px 1000px transparent inset !important;
            -webkit-text-fill-color: #fff !important;
        }

        .forget {
            display: flex;
            justify-content: space-between;
            margin: 10px 0 25px;
            font-size: 0.85rem;
            color: #fff;
        }

        .forget label {
            display: flex;
            align-items: center;
        }

        .forget a {
            color: #fff;
            text-decoration: none;
        }

        .forget a:hover {
            text-decoration: underline;
        }

        button {
            width: 100%;
            height: 40px;
            background-color: #fff;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: 0.3s ease;
        }

        button:hover {
            background-color: rgba(255, 255, 255, 0.7);
        }

        .register {
            text-align: center;
            color: #fff;
            margin-top: 20px;
            font-size: 0.9rem;
        }

        .register a {
            color: #fff;
            font-weight: 600;
            text-decoration: none;
        }

        .register a:hover {
            text-decoration: underline;
        }

        .error-message {
            background-color: rgba(255, 0, 0, 0.7);
            color: #fff;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <section>
        <form method="POST">
            <h1>Admin Login</h1>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="inputbox">
                <ion-icon name="person-outline"></ion-icon>
                <input type="text" name="username" required>
                <label>Username</label>
            </div>

            <div class="inputbox">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input type="password" name="password" required>
                <label>Password</label>
            </div>

            <div class="forget">
                <label><input type="checkbox"> Remember me</label>
                <a href="#">Forgot password?</a>
            </div>

            <button type="submit">Login</button>

            <div class="register">
                <p>I don't have an account <a href="#">Register</a></p>
            </div>
        </form>
    </section>
</body>
</html>