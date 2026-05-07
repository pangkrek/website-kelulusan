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
| STATISTIK DASHBOARD
|--------------------------------------------------------------------------
*/
$total_siswa = $conn->query("
    SELECT COUNT(*) as total
    FROM siswa
")->fetch_assoc()['total'];

$total_lulus = $conn->query("
    SELECT COUNT(*) as total
    FROM siswa
    WHERE status_kelulusan='LULUS'
")->fetch_assoc()['total'];

$total_tidak = $conn->query("
    SELECT COUNT(*) as total
    FROM siswa
    WHERE status_kelulusan='TIDAK LULUS'
")->fetch_assoc()['total'];

$total_user = $conn->query("
    SELECT COUNT(*) as total
    FROM users
")->fetch_assoc()['total'];

$recent_siswa = $conn->query("
    SELECT *
    FROM siswa
    ORDER BY id DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

            border:1px solid rgba(255,255,255,0.05);
        }

        .card-box{

            background:rgba(255,255,255,0.06);

            backdrop-filter:blur(15px);

            border-radius:24px;

            padding:25px;

            border:1px solid rgba(255,255,255,0.05);

            box-shadow:0 0 30px rgba(0,0,0,0.25);

            transition:0.3s;
        }

        .card-box:hover{
            transform:translateY(-5px);
        }

        .icon-box{

            width:65px;
            height:65px;

            border-radius:18px;

            display:flex;
            align-items:center;
            justify-content:center;

            font-size:24px;
        }

        .table{

            color:white;
        }

        .table thead{
            background:#1e293b;
        }

        .table-responsive{
            border-radius:20px;
            overflow:hidden;
        }

        .badge-lulus{
            background:#22c55e;
        }

        .badge-tidak{
            background:#ef4444;
        }

        .welcome-box{

            background:
            linear-gradient(
                135deg,
                #22c55e,
                #16a34a
            );

            border-radius:25px;

            padding:30px;

            position:relative;

            overflow:hidden;
        }

        .welcome-box::before{

            content:'';

            position:absolute;

            width:200px;
            height:200px;

            background:rgba(255,255,255,0.1);

            border-radius:50%;

            right:-50px;
            top:-50px;
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

    <a href="#">

        <i class="fa fa-chart-line"></i>
        Statistik

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
                Selamat Datang,
                <?= $_SESSION['user']['nama']; ?>
            </h3>

            <small class="text-secondary">
                Sistem Kelulusan MI Ma'arif NU Pandansari
            </small>

        </div>

        <div>

            <span class="badge bg-success p-2">
                <?= strtoupper($_SESSION['user']['role']); ?>
            </span>

        </div>

    </div>

    <!-- WELCOME -->
    <div class="welcome-box mb-4">

        <h2 class="fw-bold">
            🎓 Dashboard Kelulusan
        </h2>

        <p class="mb-0">
            Kelola data siswa, user, dan hasil kelulusan dengan mudah.
        </p>

    </div>

    <!-- CARD -->
    <div class="row g-4">

        <div class="col-md-3">

            <div class="card-box">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-secondary">
                            Total Siswa
                        </small>

                        <h2 class="fw-bold mt-2">
                            <?= $total_siswa; ?>
                        </h2>

                    </div>

                    <div class="icon-box bg-primary">

                        <i class="fa fa-user-graduate"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card-box">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-secondary">
                            Siswa Lulus
                        </small>

                        <h2 class="fw-bold mt-2">
                            <?= $total_lulus; ?>
                        </h2>

                    </div>

                    <div class="icon-box bg-success">

                        <i class="fa fa-circle-check"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card-box">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-secondary">
                            Tidak Lulus
                        </small>

                        <h2 class="fw-bold mt-2">
                            <?= $total_tidak; ?>
                        </h2>

                    </div>

                    <div class="icon-box bg-danger">

                        <i class="fa fa-circle-xmark"></i>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card-box">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-secondary">
                            Total User
                        </small>

                        <h2 class="fw-bold mt-2">
                            <?= $total_user; ?>
                        </h2>

                    </div>

                    <div class="icon-box bg-warning">

                        <i class="fa fa-users"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- CHART -->
    <div class="row mt-4">

        <div class="col-md-6">

            <div class="card-box">

                <h5 class="fw-bold mb-4">
                    Statistik Kelulusan
                </h5>

                <canvas id="myChart"></canvas>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card-box">

                <h5 class="fw-bold mb-4">
                    Aktivitas Sistem
                </h5>

                <ul class="list-group">

                    <li class="list-group-item bg-dark text-white border-secondary">
                        ✅ Admin login sistem
                    </li>

                    <li class="list-group-item bg-dark text-white border-secondary">
                        🎓 Data siswa berhasil dimuat
                    </li>

                    <li class="list-group-item bg-dark text-white border-secondary">
                        📊 Statistik diperbarui
                    </li>

                </ul>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card-box mt-4">

        <div class="d-flex justify-content-between mb-4">

            <h5 class="fw-bold">
                Siswa Terbaru
            </h5>

            <a href="siswa.php"
               class="btn btn-success">

                Lihat Semua

            </a>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    <?php $no = 1; ?>

                    <?php while($row = $recent_siswa->fetch_assoc()): ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td><?= $row['nis']; ?></td>

                        <td><?= $row['nama']; ?></td>

                        <td><?= $row['kelas']; ?></td>

                        <td>

                            <?php if($row['status_kelulusan'] == 'LULUS'): ?>

                                <span class="badge badge-lulus">
                                    LULUS
                                </span>

                            <?php else: ?>

                                <span class="badge badge-tidak">
                                    TIDAK LULUS
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- CHART -->
<script>

const ctx = document.getElementById('myChart');

new Chart(ctx, {

    type: 'doughnut',

    data: {

        labels: ['Lulus', 'Tidak Lulus'],

        datasets: [{

            data: [
                <?= $total_lulus; ?>,
                <?= $total_tidak; ?>
            ],

            backgroundColor: [
                '#22c55e',
                '#ef4444'
            ]

        }]
    }

});

</script>

</body>
</html>