<?php
session_start();

$conn = new mysqli("localhost", "root", "", "kelulusan_mi");

if ($conn->connect_error) {
    die("Koneksi database gagal");
}

/*
|--------------------------------------------------------------------------
| PROTEKSI LOGIN GURU
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['user']['role'] != 'guru') {
    header("Location: ../login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/
$filter_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';

$where = "WHERE 1=1";

if ($filter_kelas != '') {
    $where .= " AND kelas='$filter_kelas'";
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA SISWA
|--------------------------------------------------------------------------
*/
$siswa = $conn->query("
    SELECT *
    FROM siswa
    $where
    ORDER BY kelas ASC, nama ASC
");

/*
|--------------------------------------------------------------------------
| STATISTIK
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

$total_belum = $conn->query("
    SELECT COUNT(*) as total
    FROM siswa
    WHERE status_kelulusan='BELUM DIPROSES'
")->fetch_assoc()['total'];

$total_laki = $conn->query("
    SELECT COUNT(*) as total
    FROM siswa
    WHERE jenis_kelamin='Laki-laki'
")->fetch_assoc()['total'];

$total_perempuan = $conn->query("
    SELECT COUNT(*) as total
    FROM siswa
    WHERE jenis_kelamin='Perempuan'
")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard Guru</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

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
        }

        .sidebar{

            width:270px;
            min-height:100vh;

            position:fixed;

            background:#0f172a;

            padding:25px;

            border-right:1px solid rgba(255,255,255,0.06);
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

            margin-left:270px;

            padding:30px;
        }

        .card-box{

            background:rgba(255,255,255,0.06);

            border-radius:24px;

            padding:25px;

            border:1px solid rgba(255,255,255,0.05);

            box-shadow:0 0 30px rgba(0,0,0,0.25);
        }

        .table{
            color:white;
        }

        .table thead{
            background:#1e293b;
        }

        .badge-lulus{
            background:#22c55e;
        }

        .badge-tidak{
            background:#ef4444;
        }

        .badge-proses{
            background:#f59e0b;
        }

        .topbar{

            background:rgba(255,255,255,0.05);

            padding:20px;

            border-radius:20px;

            margin-bottom:30px;
        }

        .search-box{

            background:rgba(255,255,255,0.04);

            border-radius:20px;

            padding:20px;
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
            PANEL GURU
        </h4>

        <small class="text-secondary">
            MI Ma'arif NU Pandansari
        </small>

    </div>

    <a href="dashboard.php">

        <i class="fa fa-home"></i>
        Dashboard

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

            <h2 class="fw-bold">
                Dashboard Guru
            </h2>

            <small class="text-secondary">
                Kelola nilai & kelulusan siswa
            </small>

        </div>

        <div>

            <span class="badge bg-success p-2">

                <?= strtoupper($_SESSION['user']['username']); ?>

            </span>

        </div>

    </div>

    <!-- STATISTIK -->
    <div class="row g-4 mb-4">

        <div class="col-md-2">

            <div class="card-box text-center">

                <h2><?= $total_siswa; ?></h2>

                <small>Total Siswa</small>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card-box text-center">

                <h2><?= $total_laki; ?></h2>

                <small>Laki-laki</small>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card-box text-center">

                <h2><?= $total_perempuan; ?></h2>

                <small>Perempuan</small>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card-box text-center">

                <h2><?= $total_lulus; ?></h2>

                <small>Lulus</small>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card-box text-center">

                <h2><?= $total_tidak; ?></h2>

                <small>Tidak Lulus</small>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card-box text-center">

                <h2><?= $total_belum; ?></h2>

                <small>Belum Diproses</small>

            </div>

        </div>

    </div>

    <!-- FILTER -->
    <div class="search-box mb-4">

        <form method="GET">

            <div class="row">

                <div class="col-md-10">

                    <select
                        name="kelas"
                        class="form-select"
                    >

                        <option value="">
                            Semua Kelas
                        </option>

                        <option value="1A">1A</option>
                        <option value="1B">1B</option>

                        <option value="2A">2A</option>
                        <option value="2B">2B</option>

                        <option value="3A">3A</option>
                        <option value="3B">3B</option>

                        <option value="4A">4A</option>
                        <option value="4B">4B</option>

                        <option value="5A">5A</option>
                        <option value="5B">5B</option>

                        <option value="6A">6A</option>
                        <option value="6B">6B</option>
                        <option value="6C">6C</option>

                    </select>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-success w-100">

                        <i class="fa fa-search"></i>
                        Filter

                    </button>

                </div>

            </div>

        </form>

    </div>

    <!-- DATA SISWA -->
    <div class="card-box">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="fw-bold">
                    Data Nilai Siswa
                </h4>

                <small class="text-secondary">
                    Guru dapat menginput nilai siswa
                </small>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Gender</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php $no = 1; ?>

                    <?php while($row = $siswa->fetch_assoc()): ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td><?= $row['nis']; ?></td>

                        <td><?= $row['nama']; ?></td>

                        <td><?= $row['kelas']; ?></td>

                        <td><?= $row['jenis_kelamin']; ?></td>

                        <td>

                            <?php if($row['nilai'] == NULL): ?>

                                <span class="text-warning">
                                    Belum Ada
                                </span>

                            <?php else: ?>

                                <span class="fw-bold">
                                    <?= $row['nilai']; ?>
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php if($row['status_kelulusan'] == 'LULUS'): ?>

                                <span class="badge badge-lulus">
                                    LULUS
                                </span>

                            <?php elseif($row['status_kelulusan'] == 'TIDAK LULUS'): ?>

                                <span class="badge badge-tidak">
                                    TIDAK LULUS
                                </span>

                            <?php else: ?>

                                <span class="badge badge-proses">
                                    BELUM DIPROSES
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a href="input_nilai.php?id=<?= $row['id']; ?>"
                               class="btn btn-success btn-sm">

                                <i class="fa fa-edit"></i>
                                Input Nilai

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