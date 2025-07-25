<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout...</title>
    <meta http-equiv="refresh" content="2;url=login.php"> <!-- Auto redirect -->
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1f4037, #99f2c8);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
        }

        .logout-box {
            text-align: center;
            animation: fadeUp 1.5s ease-out;
        }

        .logout-box h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .logout-box p {
            font-size: 1.2rem;
            opacity: 0.8;
        }

        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="logout-box">
        <h1>Anda telah logout</h1>
        <p>Mengarahkan kembali ke halaman login...</p>
    </div>
</body>
</html>
