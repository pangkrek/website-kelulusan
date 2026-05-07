<?php
session_start();

$conn = new mysqli("localhost", "root", "", "kelulusan_mi");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$error = "";

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = $conn->query("
        SELECT * FROM users
        WHERE username='$username'
        AND password='$password'
    ");

    if ($query->num_rows > 0) {

        $user = $query->fetch_assoc();

        $_SESSION['user'] = $user;

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        }

        elseif ($user['role'] == 'guru') {
            header("Location: guru/dashboard.php");
        }

        else {
            header("Location: siswa/dashboard.php");
        }

    } else {
        $error = "Username atau password salah";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Sistem Kelulusan</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{

            background:
            linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)),
            url('assets/img/sekolah.jpg');

            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;

            min-height:100vh;

            display:flex;
            align-items:center;
            justify-content:center;

            overflow:hidden;

            font-family:Arial, Helvetica, sans-serif;
        }

        #particles-js{
            position:fixed;
            width:100%;
            height:100%;
            z-index:-1;
            top:0;
            left:0;
        }

        .card-login{

            background: rgba(15,23,42,0.75);

            backdrop-filter: blur(20px);

            border-radius: 24px;

            padding: 35px;

            width: 100%;
            max-width: 420px;

            color:white;

            box-shadow:
                0 0 30px rgba(0,0,0,0.5),
                0 0 60px rgba(34,197,94,0.2);

            border:1px solid rgba(255,255,255,0.1);
        }

        .logo{
            width:100px;
            height:100px;
            object-fit:cover;
            border-radius:50%;
            border:4px solid white;
            box-shadow:0 0 20px rgba(255,255,255,0.3);
        }

        .title{
            font-size:24px;
            font-weight:bold;
        }

        .form-control{
            height:50px;
            border-radius:14px;
        }

        .btn-login{

            background:#22c55e;
            border:none;

            height:50px;

            font-weight:bold;

            transition:0.3s;
        }

        .btn-login:hover{
            background:#16a34a;
            transform:scale(1.02);
        }

        .footer{
            font-size:12px;
            color:#cbd5e1;
        }

    </style>

</head>

<body>

<!-- PARTICLES -->
<div id="particles-js"></div>

<div class="container">

    <div class="card-login mx-auto text-center">

        <!-- LOGO -->
        <img
            src="assets/img/logo.png"
            class="logo mb-3"
        >

        <!-- TITLE -->
        <h2 class="title">
            🔐 Login Sistem
        </h2>

        <p class="text-gray-300 mb-4">
            MI Ma'arif NU Pandansari
        </p>

        <!-- ERROR -->
        <?php if($error): ?>

            <div class="alert alert-danger">
                <?= $error; ?>
            </div>

        <?php endif; ?>

        <!-- FORM -->
        <form method="POST">

            <input
                type="text"
                name="username"
                class="form-control mb-3"
                placeholder="Masukkan Username"
                required
            >

            <input
                type="password"
                name="password"
                class="form-control mb-3"
                placeholder="Masukkan Password"
                required
            >

            <button
                name="login"
                class="btn btn-success btn-login w-100"
            >
                Login
            </button>

        </form>

        <!-- FOOTER -->
        <div class="footer mt-4">
            © 2026 MI Ma'arif NU Pandansari
        </div>

    </div>

</div>

<!-- PARTICLES -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<script>

particlesJS("particles-js", {

    "particles": {

        "number": {
            "value": 80
        },

        "color": {
            "value": "#ffffff"
        },

        "shape": {
            "type": "circle"
        },

        "opacity": {
            "value": 0.5
        },

        "size": {
            "value": 3
        },

        "move": {
            "enable": true,
            "speed": 2
        }

    }

});

</script>

</body>
</html>