<?php
session_start();

$conn = new mysqli("localhost", "root", "", "kelulusan_mi");

if ($conn->connect_error) {
    die("Koneksi database gagal");
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
| CEK ID
|--------------------------------------------------------------------------
*/
if (!isset($_GET['id'])) {
    header("Location: siswa.php");
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
    header("Location: siswa.php");
    exit;
}

$data = $siswa->fetch_assoc();

/*
|--------------------------------------------------------------------------
| UPDATE DATA
|--------------------------------------------------------------------------
*/
$message = "";

if (isset($_POST['submit'])) {

    $nis = htmlspecialchars($_POST['nis']);
    $nama = htmlspecialchars($_POST['nama']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $kelas = htmlspecialchars($_POST['kelas']);
    $tahun_ajaran = htmlspecialchars($_POST['tahun_ajaran']);
    $status_kelulusan = htmlspecialchars($_POST['status_kelulusan']);

    /*
    |--------------------------------------------------------------------------
    | UPDATE QUERY
    |--------------------------------------------------------------------------
    */
    $update = $conn->query("
        UPDATE siswa
        SET
            nis='$nis',
            nama='$nama',
            jenis_kelamin='$jenis_kelamin',
            kelas='$kelas',
            tahun_ajaran='$tahun_ajaran',
            status_kelulusan='$status_kelulusan'
        WHERE id='$id'
    ");

    if ($update) {

        $message = "
            <div class='alert alert-success'>
                Data siswa berhasil diperbarui
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
                Gagal memperbarui data siswa
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

    <title>Edit Siswa</title>

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

            max-width:800px;
        }

        .form-control,
        .form-select{

            height:52px;

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

            padding:20px;

            border-radius:20px;

            margin-bottom:30px;
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
                Edit Siswa
            </h3>

            <small class="text-secondary">
                Perbarui data siswa
            </small>

        </div>

        <div>

            <a href="siswa.php"
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

            <!-- NIS -->
            <div class="mb-3">

                <label class="mb-2">
                    NIS
                </label>

                <input
                    type="text"
                    name="nis"
                    class="form-control"
                    value="<?= $data['nis']; ?>"
                    required
                >

            </div>

            <!-- NAMA -->
            <div class="mb-3">

                <label class="mb-2">
                    Nama Siswa
                </label>

                <input
                    type="text"
                    name="nama"
                    class="form-control"
                    value="<?= $data['nama']; ?>"
                    required
                >

            </div>

            <!-- JENIS KELAMIN -->
            <div class="mb-3">

                <label class="mb-2">
                    Jenis Kelamin
                </label>

                <select
                    name="jenis_kelamin"
                    class="form-select"
                    required
                >

                    <option value="Laki-laki"
                    <?= $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>

                        Laki-laki

                    </option>

                    <option value="Perempuan"
                    <?= $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>

                        Perempuan

                    </option>

                </select>

            </div>

            <!-- KELAS -->
<div class="mb-3">

    <label class="mb-2">
        Kelas
    </label>

    <select
        name="kelas"
        class="form-select"
        required
    >

        <option value="1A"
        <?= $data['kelas'] == '1A' ? 'selected' : ''; ?>>
            1A
        </option>

        <option value="1B"
        <?= $data['kelas'] == '1B' ? 'selected' : ''; ?>>
            1B
        </option>

        <option value="2A"
        <?= $data['kelas'] == '2A' ? 'selected' : ''; ?>>
            2A
        </option>

        <option value="2B"
        <?= $data['kelas'] == '2B' ? 'selected' : ''; ?>>
            2B
        </option>

        <option value="3A"
        <?= $data['kelas'] == '3A' ? 'selected' : ''; ?>>
            3A
        </option>

        <option value="3B"
        <?= $data['kelas'] == '3B' ? 'selected' : ''; ?>>
            3B
        </option>

        <option value="4A"
        <?= $data['kelas'] == '4A' ? 'selected' : ''; ?>>
            4A
        </option>

        <option value="4B"
        <?= $data['kelas'] == '4B' ? 'selected' : ''; ?>>
            4B
        </option>

        <option value="5A"
        <?= $data['kelas'] == '5A' ? 'selected' : ''; ?>>
            5A
        </option>

        <option value="5B"
        <?= $data['kelas'] == '5B' ? 'selected' : ''; ?>>
            5B
        </option>

        <option value="6A"
        <?= $data['kelas'] == '6A' ? 'selected' : ''; ?>>
            6A
        </option>

        <option value="6B"
        <?= $data['kelas'] == '6B' ? 'selected' : ''; ?>>
            6B
        </option>

        <option value="6C"
        <?= $data['kelas'] == '6C' ? 'selected' : ''; ?>>
            6C
        </option>

    </select>

</div>

            <!-- TAHUN AJARAN -->
            <div class="mb-3">

                <label class="mb-2">
                    Tahun Ajaran
                </label>

                <input
                    type="text"
                    name="tahun_ajaran"
                    class="form-control"
                    value="<?= $data['tahun_ajaran']; ?>"
                    required
                >

            </div>

            <!-- STATUS -->
            <div class="mb-4">

                <label class="mb-2">
                    Status Kelulusan
                </label>

                <select
                    name="status_kelulusan"
                    class="form-select"
                    required
                >

                    <option value="LULUS"
                    <?= $data['status_kelulusan'] == 'LULUS' ? 'selected' : ''; ?>>
                        LULUS
                    </option>

                    <option value="TIDAK LULUS"
                    <?= $data['status_kelulusan'] == 'TIDAK LULUS' ? 'selected' : ''; ?>>
                        TIDAK LULUS
                    </option>

                    <option value="BELUM DIPROSES"
                    <?= $data['status_kelulusan'] == 'BELUM DIPROSES' ? 'selected' : ''; ?>>
                        BELUM DIPROSES
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
                Update Data Siswa

            </button>

        </form>

    </div>

</div>

</body>
</html>