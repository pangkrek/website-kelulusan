<?php
session_start();

$conn = new mysqli("localhost", "root", "", "kelulusan_mi");

if ($conn->connect_error) {
    die("Koneksi gagal");
}

/*
|--------------------------------------------------------------------------
| PROTEKSI LOGIN
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
| FILTER
|--------------------------------------------------------------------------
*/
$filter_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
$filter_jk = isset($_GET['jk']) ? $_GET['jk'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

$where = "WHERE 1=1";

if ($filter_kelas != '') {
    $where .= " AND kelas='$filter_kelas'";
}

if ($filter_jk != '') {
    $where .= " AND jenis_kelamin='$filter_jk'";
}

if ($filter_status != '') {
    $where .= " AND status_kelulusan='$filter_status'";
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA SISWA
|--------------------------------------------------------------------------
*/
$data_siswa = $conn->query("
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
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Siswa</title>

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
        }

        .sidebar{
            width:270px;
            min-height:100vh;
            position:fixed;
            background:#0f172a;
            padding:25px;
        }

        .sidebar a{
            display:block;
            color:#cbd5e1;
            padding:14px;
            margin-bottom:10px;
            border-radius:14px;
            text-decoration:none;
            transition:0.3s;
        }

        .sidebar a:hover{
            background:#22c55e;
            color:white;
        }

        .content{
            margin-left:270px;
            padding:30px;
        }

        .card-box{
            background:rgba(255,255,255,0.06);
            border-radius:24px;
            padding:25px;
            border:1px solid rgba(255,255,255,0.06);
            box-shadow:0 0 30px rgba(0,0,0,0.2);
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

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                Data Siswa
            </h2>

            <small class="text-secondary">
                Kelola data siswa dan kelulusan
            </small>

        </div>

        <a href="tambah_siswa.php"
           class="btn btn-success">

            <i class="fa fa-plus"></i>
            Tambah Siswa

        </a>

    </div>

    <!-- STATISTIK -->
    <div class="row g-4 mb-4">

        <div class="col-md-2">

            <div class="card-box text-center">

                <h3><?= $total_siswa; ?></h3>

                <small>Total Siswa</small>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card-box text-center">

                <h3><?= $total_laki; ?></h3>

                <small>Laki-laki</small>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card-box text-center">

                <h3><?= $total_perempuan; ?></h3>

                <small>Perempuan</small>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card-box text-center">

                <h3><?= $total_lulus; ?></h3>

                <small>Lulus</small>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card-box text-center">

                <h3><?= $total_tidak; ?></h3>

                <small>Tidak Lulus</small>

            </div>

        </div>

        <div class="col-md-2">

            <div class="card-box text-center">

                <h3><?= $total_belum; ?></h3>

                <small>Belum Diproses</small>

            </div>

        </div>

    </div>

    <!-- FILTER -->
    <div class="card-box mb-4">

        <form method="GET">

            <div class="row">

                <div class="col-md-3">

    <select
        name="kelas"
        class="form-select"
    >

        <option value="">
            Semua Kelas
        </option>

        <option value="1A"
        <?= $filter_kelas == '1A' ? 'selected' : ''; ?>>
            1A
        </option>

        <option value="1B"
        <?= $filter_kelas == '1B' ? 'selected' : ''; ?>>
            1B
        </option>

        <option value="2A"
        <?= $filter_kelas == '2A' ? 'selected' : ''; ?>>
            2A
        </option>

        <option value="2B"
        <?= $filter_kelas == '2B' ? 'selected' : ''; ?>>
            2B
        </option>

        <option value="3A"
        <?= $filter_kelas == '3A' ? 'selected' : ''; ?>>
            3A
        </option>

        <option value="3B"
        <?= $filter_kelas == '3B' ? 'selected' : ''; ?>>
            3B
        </option>

        <option value="4A"
        <?= $filter_kelas == '4A' ? 'selected' : ''; ?>>
            4A
        </option>

        <option value="4B"
        <?= $filter_kelas == '4B' ? 'selected' : ''; ?>>
            4B
        </option>

        <option value="5A"
        <?= $filter_kelas == '5A' ? 'selected' : ''; ?>>
            5A
        </option>

        <option value="5B"
        <?= $filter_kelas == '5B' ? 'selected' : ''; ?>>
            5B
        </option>

        <option value="6A"
        <?= $filter_kelas == '6A' ? 'selected' : ''; ?>>
            6A
        </option>

        <option value="6B"
        <?= $filter_kelas == '6B' ? 'selected' : ''; ?>>
            6B
        </option>

        <option value="6C"
        <?= $filter_kelas == '6C' ? 'selected' : ''; ?>>
            6C
        </option>

    </select>

</div>

                <div class="col-md-3">

                    <select
                        name="jk"
                        class="form-select"
                    >

                        <option value="">
                            Semua Gender
                        </option>

                        <option value="Laki-laki">
                            Laki-laki
                        </option>

                        <option value="Perempuan">
                            Perempuan
                        </option>

                    </select>

                </div>

                <div class="col-md-3">

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option value="LULUS">
                            LULUS
                        </option>

                        <option value="TIDAK LULUS">
                            TIDAK LULUS
                        </option>

                        <option value="BELUM DIPROSES">
                            BELUM DIPROSES
                        </option>

                    </select>

                </div>

                <div class="col-md-3">

                    <button class="btn btn-success w-100">

                        <i class="fa fa-search"></i>
                        Filter Data

                    </button>

                </div>

            </div>

        </form>

    </div>

    <!-- TABLE -->
    <div class="card-box">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php $no = 1; ?>

                    <?php while($row = $data_siswa->fetch_assoc()): ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td><?= $row['nis']; ?></td>

                        <td><?= $row['nama']; ?></td>

                        <td><?= $row['kelas']; ?></td>

                        <td><?= $row['jenis_kelamin']; ?></td>

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

                            <a href="edit_siswa.php?id=<?= $row['id']; ?>"
                               class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <a href="hapus_siswa.php?id=<?= $row['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin hapus data?')">

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