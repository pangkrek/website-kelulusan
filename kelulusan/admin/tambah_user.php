<?php
session_start();

$conn = new mysqli("localhost", "root", "", "kelulusan_mi");

if ($conn->connect_error) {
    die("Koneksi database gagal");
}

/*
|--------------------------------------------------------------------------
| PROTEKSI LOGIN ADMIN
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| TAMBAH USER
|--------------------------------------------------------------------------
*/
$message = "";

if (isset($_POST['submit'])) {

    $nama = htmlspecialchars($_POST['nama']);
    $username = htmlspecialchars($_POST['username']);
    $password = md5($_POST['password']);
    $role = htmlspecialchars($_POST['role']);

    /*
    |--------------------------------------------------------------------------
    | CEK USERNAME
    |--------------------------------------------------------------------------
    */
    $cek = $conn->query("
        SELECT *
        FROM users
        WHERE username='$username'
    ");

    if ($cek->num_rows > 0) {

        $message = "
            <div class='alert alert-danger'>
                Username sudah digunakan!
            </div>
        ";

    } else {

        $insert = $conn->query("
            INSERT INTO users
            (
                nama,
                username,
                password,
                role
            )

            VALUES
            (
                '$nama',
                '$username',
                '$password',
                '$role'
            )
        ");

        if ($insert) {

            $message = "
                <div class='alert alert-success'>
                    User berhasil ditambahkan
                </div>
            ";

        } else {

            $message = "
                <div class='alert alert-danger'>
                    Gagal menambahkan user
                </div>
            ";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah User</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        body{
            background:#020617;
            color:white;
            overflow-x:hidden;
            font-family:Arial, Helvetica, sans-serif;
        }

        .sidebar{

            width:270px;
            min-height:100vh;

            background:rgba(15,23,42,0.96);

            backdrop-filter:blur(20px);

            position:fixed;

            padding:25px;

            border-right:1px solid rgba(255,255,255,0.08);
        }

        .sidebar .logo{
            width:90px;
            border-radius:50%;
            border:4px solid white;
        }

        .sidebar a{

            display:flex;
            align-items:center;

            color:#cbd5e1;

            padding:14px 16px;

            border-radius:14px;

            margin-bottom:12px;

            text-decoration:none;

            transition:0.3s;
        }

        .sidebar a:hover{

            background:#22c55e;

            color:white;

            transform:translateX(5px);
        }

        .sidebar a i{
            width:25px;
        }

        .content{

            margin-left:270px;

            padding:30px;
        }

        .topbar{

            background:rgba(255,255,255,0.05);

            backdrop-filter:blur(15px);

            border-radius:20px;

            padding:20px 25px;

            margin-bottom:30px;
        }

        .card-box{

            background:rgba(255,255,255,0.06);

            backdrop-filter:blur(15px);

            border-radius:24px;

            padding:30px;

            border:1px solid rgba(255,255,255,0.05);

            box-shadow:0 0 30px rgba(0,0,0,0.25);

            max-width:700px;
        }

        .form-control,
        .form-select{

            height:50px;

            border-radius:14px;
        }

        .btn-custom{

            background:#22c55e;

            border:none;

            height:50px;

            font-weight:bold;
        }

        .btn-custom:hover{
            background:#16a34a;
        }

    </style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="text-center mb-5">

        <img
            src="../assets/img/logo.png"
            class="logo shadow"
        >

        <h4 class="mt-3 fw-bold">
            ADMIN PANEL
        </h4>

        <small class="text-secondary">
            MI Ma'arif NU Pandansari
        </small>

    </div>

    <a href="dashboard.php">

        <i class="fa fa-home"></i>
        Dashboard

    </a>

    <a href="siswa.php">

        <i class="fa fa-user-graduate"></i>
        Data Siswa

    </a>

    <a href="users.php">

        <i class="fa fa-users"></i>
        Data User

    </a>

    <a href="../logout.php">

        <i class="fa fa-sign-out-alt"></i>
        Logout

    </a>

</div>

<!-- CONTENT -->
<div class="content">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">

        <div>

            <h3 class="fw-bold">
                Tambah User
            </h3>

            <small class="text-secondary">
                Tambahkan akun login baru
            </small>

        </div>

        <div>

            <a href="users.php"
               class="btn btn-secondary">

                <i class="fa fa-arrow-left"></i>
                Kembali

            </a>

        </div>

    </div>

    <!-- FORM -->
    <div class="card-box">

        <?= $message; ?>

        <form method="POST">

            <!-- NAMA -->
            <div class="mb-3">

                <label class="mb-2">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    placeholder="Masukkan nama lengkap"
                    required
                >

            </div>

            <!-- USERNAME -->
            <div class="mb-3">

                <label class="mb-2">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    required
                >

            </div>

            <!-- PASSWORD -->
            <div class="mb-3">

                <label class="mb-2">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                >

            </div>

            <!-- ROLE -->
            <div class="mb-4">

                <label class="mb-2">
                    Role User
                </label>

                <select
                    name="role"
                    class="form-select"
                    required
                >

                    <option value="">
                        -- Pilih Role --
                    </option>

                    <option value="admin">
                        Admin
                    </option>

                    <option value="guru">
                        Guru
                    </option>

                    <option value="siswa">
                        Siswa
                    </option>

                </select>

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                name="submit"
                class="btn btn-success btn-custom w-100"
            >

                <i class="fa fa-save"></i>
                Simpan User

            </button>

        </form>

    </div>

</div>

</body>
</html>