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
| CEK ID SISWA
|--------------------------------------------------------------------------
*/
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| AMBIL DATA SISWA
|--------------------------------------------------------------------------
*/
$siswa = $conn->query("
    SELECT *
    FROM siswa
    WHERE id='$id'
");

if ($siswa->num_rows < 1) {
    header("Location: dashboard.php");
    exit;
}

$data = $siswa->fetch_assoc();

/*
|--------------------------------------------------------------------------
| SIMPAN NILAI
|--------------------------------------------------------------------------
*/
$message = "";

if (isset($_POST['submit'])) {

    $nilai = $_POST['nilai'];

    /*
    |--------------------------------------------------------------------------
    | STATUS OTOMATIS
    |--------------------------------------------------------------------------
    */
    if ($nilai >= 75) {

        $status = "LULUS";

    } else {

        $status = "TIDAK LULUS";
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE DATABASE
    |--------------------------------------------------------------------------
    */
    $update = $conn->query("
        UPDATE siswa
        SET
            nilai='$nilai',
            status_kelulusan='$status'
        WHERE id='$id'
    ");

    if ($update) {

        $message = "
            <div class='alert alert-success'>
                Nilai berhasil disimpan
            </div>
        ";

        // refresh data
        $siswa = $conn->query("
            SELECT *
            FROM siswa
            WHERE id='$id'
        ");

        $data = $siswa->fetch_assoc();

    } else {

        $message = "
            <div class='alert alert-danger'>
                Gagal menyimpan nilai
            </div>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Input Nilai</title>

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

            padding:30px;

            border:1px solid rgba(255,255,255,0.05);

            box-shadow:0 0 30px rgba(0,0,0,0.25);

            max-width:700px;
        }

        .form-control{

            height:55px;

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

        .topbar{

            background:rgba(255,255,255,0.05);

            border-radius:20px;

            padding:20px;

            margin-bottom:30px;
        }

        .status-lulus{
            color:#22c55e;
            font-weight:bold;
        }

        .status-tidak{
            color:#ef4444;
            font-weight:bold;
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

            <h3 class="fw-bold">
                Input Nilai Siswa
            </h3>

            <small class="text-secondary">
                Guru dapat menginput nilai siswa
            </small>

        </div>

        <div>

            <a href="dashboard.php"
               class="btn btn-secondary">

                <i class="fa fa-arrow-left"></i>
                Kembali

            </a>

        </div>

    </div>

    <!-- FORM -->
    <div class="card-box">

        <?= $message; ?>

        <div class="mb-4">

            <h4 class="fw-bold">
                <?= $data['nama']; ?>
            </h4>

            <small class="text-secondary">
                NIS : <?= $data['nis']; ?>
            </small>

            <br>

            <small class="text-secondary">
                Kelas : <?= $data['kelas']; ?>
            </small>

        </div>

        <form method="POST">

            <!-- NILAI -->
            <div class="mb-4">

                <label class="mb-2">
                    Input Nilai
                </label>

                <input
                    type="number"
                    name="nilai"
                    class="form-control"
                    min="0"
                    max="100"
                    value="<?= $data['nilai']; ?>"
                    placeholder="Masukkan nilai siswa"
                    required
                >

            </div>

            <!-- STATUS -->
            <div class="mb-4">

                <label class="mb-2">
                    Status Kelulusan
                </label>

                <div>

                    <?php if($data['status_kelulusan'] == 'LULUS'): ?>

                        <span class="status-lulus">
                            LULUS
                        </span>

                    <?php elseif($data['status_kelulusan'] == 'TIDAK LULUS'): ?>

                        <span class="status-tidak">
                            TIDAK LULUS
                        </span>

                    <?php else: ?>

                        <span class="text-warning fw-bold">
                            BELUM DIPROSES
                        </span>

                    <?php endif; ?>

                </div>

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                name="submit"
                class="btn btn-success btn-custom w-100"
            >

                <i class="fa fa-save"></i>
                Simpan Nilai

            </button>

        </form>

    </div>

</div>

</body>
</html>