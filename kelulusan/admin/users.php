<?php
session_start();

$conn = new mysqli("localhost", "root", "", "kelulusan_mi");

if ($conn->connect_error) {
    die("Koneksi gagal");
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
| AMBIL DATA USERS
|--------------------------------------------------------------------------
*/
$users = $conn->query("
    SELECT *
    FROM users
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data User</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        body{
            background:#0f172a;
            color:white;
            overflow-x:hidden;
        }

        .sidebar{

            width:260px;
            min-height:100vh;

            background:rgba(15,23,42,0.95);

            backdrop-filter:blur(20px);

            position:fixed;

            padding:25px;

            border-right:1px solid rgba(255,255,255,0.08);
        }

        .sidebar a{

            display:block;

            color:#cbd5e1;

            padding:14px;

            border-radius:14px;

            margin-bottom:10px;

            text-decoration:none;

            transition:0.3s;
        }

        .sidebar a:hover{

            background:#22c55e;
            color:white;

            transform:translateX(5px);
        }

        .content{

            margin-left:260px;

            padding:30px;
        }

        .topbar{

            background:rgba(255,255,255,0.05);

            backdrop-filter:blur(10px);

            padding:20px;

            border-radius:20px;

            margin-bottom:30px;
        }

        .card-box{

            background:rgba(255,255,255,0.08);

            backdrop-filter:blur(20px);

            border-radius:20px;

            padding:25px;

            box-shadow:0 0 25px rgba(0,0,0,0.3);

            border:1px solid rgba(255,255,255,0.08);
        }

        .table-custom{

            background:rgba(255,255,255,0.05);

            border-radius:20px;

            overflow:hidden;
        }

        .table{
            color:white;
        }

        .table thead{
            background:#1e293b;
        }

        .badge-admin{
            background:#22c55e;
        }

        .badge-guru{
            background:#3b82f6;
        }

        .badge-siswa{
            background:#f59e0b;
        }

        .btn-custom{

            background:#22c55e;

            border:none;
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
            width="90"
            class="rounded-circle border border-4 border-white shadow"
        >

        <h4 class="mt-3 fw-bold">
            ADMIN PANEL
        </h4>

        <small class="text-secondary">
            MI Ma'arif NU Pandansari
        </small>

    </div>

    <a href="dashboard.php">

        <i class="fa fa-home me-2"></i>
        Dashboard

    </a>

    <a href="siswa.php">

        <i class="fa fa-user-graduate me-2"></i>
        Data Siswa

    </a>

    <a href="users.php">

        <i class="fa fa-users me-2"></i>
        Data User

    </a>

    <a href="../logout.php">

        <i class="fa fa-sign-out-alt me-2"></i>
        Logout

    </a>

</div>

<!-- CONTENT -->
<div class="content">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">

        <div>

            <h3 class="fw-bold">
                Data User
            </h3>

            <small class="text-secondary">
                Kelola akun login sistem
            </small>

        </div>

        <div>

            <a href="tambah_user.php"
               class="btn btn-success btn-custom">

                <i class="fa fa-plus"></i>
                Tambah User

            </a>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card-box">

        <div class="table-responsive table-custom">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php $no = 1; ?>

                    <?php while($row = $users->fetch_assoc()): ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td><?= $row['nama']; ?></td>

                        <td><?= $row['username']; ?></td>

                        <td>

                            <?php if($row['role'] == 'admin'): ?>

                                <span class="badge badge-admin">
                                    ADMIN
                                </span>

                            <?php elseif($row['role'] == 'guru'): ?>

                                <span class="badge badge-guru">
                                    GURU
                                </span>

                            <?php else: ?>

                                <span class="badge badge-siswa">
                                    SISWA
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a href="edit_user.php?id=<?= $row['id']; ?>"
                               class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <a href="hapus_user.php?id=<?= $row['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin hapus user?')">

                                Hapus

                            </a>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>