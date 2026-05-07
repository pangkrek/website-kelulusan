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
| HAPUS DATA SISWA
|--------------------------------------------------------------------------
*/
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    $hapus = $conn->query("
        DELETE FROM siswa
        WHERE id='$id'
    ");

    if ($hapus) {
        header("Location: siswa.php");
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| AMBIL DATA SISWA
|--------------------------------------------------------------------------
*/
$data_siswa = $conn->query("
    SELECT *
    FROM siswa
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind -->
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

        .badge-lulus{
            background:#22c55e;
        }

        .badge-tidak{
            background:#ef4444;
        }

        .topbar{

            background:rgba(255,255,255,0.05);

            backdrop-filter:blur(10px);

            padding:20px;

            border-radius:20px;

            margin-bottom:30px;
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
                Data Siswa
            </h3>

            <small class="text-secondary">
                Kelola data siswa MI Ma'arif NU Pandansari
            </small>

        </div>

        <div>

            <a href="tambah_siswa.php"
               class="btn btn-success btn-custom">

                <i class="fa fa-plus"></i>
                Tambah Siswa

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
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Tahun Ajaran</th>
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

                        <td><?= $row['tahun_ajaran']; ?></td>

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

                                <span class="badge bg-warning">
                                    BELUM DIPROSES
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <a href="edit_siswa.php?id=<?= $row['id']; ?>"
                               class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <a href="siswa.php?hapus=<?= $row['id']; ?>"
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