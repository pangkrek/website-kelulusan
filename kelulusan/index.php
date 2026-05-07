<?php
$conn = new mysqli("localhost", "root", "", "kelulusan_mi");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$hasil = null;
$error = null;

if (isset($_GET['nis'])) {

    $nis = $_GET['nis'];

    $stmt = $conn->prepare("SELECT nis, nama, status_kelulusan FROM siswa WHERE nis = ?");
    $stmt->bind_param("s", $nis);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hasil = $result->fetch_assoc();
    } else {
        $error = "Data siswa tidak ditemukan";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pengumuman Kelulusan</title>

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

            color:white;
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

        .card-glass{

            background: rgba(15,23,42,0.75);

            backdrop-filter: blur(20px);

            border-radius: 24px;

            padding: 35px;

            width: 100%;
            max-width: 450px;

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
            font-size:26px;
            font-weight:bold;
        }

        .btn-custom{
            background:#22c55e;
            color:white;
            font-weight:bold;
            transition:0.3s;
            height:50px;
            border:none;
        }

        .btn-custom:hover{
            background:#16a34a;
            transform:scale(1.02);
        }

        .form-control{
            height:50px;
            border-radius:14px;
            text-align:center;
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

    <div class="card-glass mx-auto text-center">

        <!-- LOGO -->
        <img src="assets/img/logo.png" class="logo mb-3">

        <!-- TITLE -->
        <h2 class="title">📢 Pengumuman Kelulusan</h2>

        <p class="text-gray-300 mb-4">
            MI Ma'arif NU Pandansari
        </p>

        <!-- FORM -->
        <form method="GET">

            <input
                type="text"
                name="nis"
                class="form-control mb-3"
                placeholder="Masukkan NIS"
                required
            >

            <button class="btn btn-custom w-100">
                Cek Kelulusan
            </button>

        </form>

        <!-- HASIL -->
        <?php if($hasil): ?>

            <div class="bg-white text-dark p-4 rounded-4 mt-4 shadow">

                <h4 class="fw-bold mb-3">
                    Hasil Kelulusan
                </h4>

                <p>
                    Nama:
                    <strong><?= htmlspecialchars($hasil['nama']); ?></strong>
                </p>

                <p>
                    NIS:
                    <strong><?= htmlspecialchars($hasil['nis']); ?></strong>
                </p>

                <?php if($hasil['status_kelulusan'] == 'LULUS'): ?>

                    <div class="alert alert-success mt-3 fw-bold">
                        🎉 SELAMAT ANDA LULUS
                    </div>

                <?php elseif($hasil['status_kelulusan'] == 'TIDAK LULUS'): ?>

                    <div class="alert alert-danger mt-3 fw-bold">
                        MOHON MAAF ANDA TIDAK LULUS
                    </div>

                <?php else: ?>

                    <div class="alert alert-warning mt-3 fw-bold">
                        BELUM DIPROSES
                    </div>

                <?php endif; ?>

            </div>

        <?php elseif($error): ?>

            <div class="alert alert-danger mt-4">
                <?= $error; ?>
            </div>

        <?php endif; ?>

        <!-- FOOTER -->
        <div class="footer mt-4">
            © 2026 MI Ma'arif NU Pandansari
        </div>

    </div>

</div>

<!-- AUDIO -->
<audio autoplay loop>
    <source src="assets/audio/kelulusan.mp3" type="audio/mpeg">
</audio>

<!-- PARTICLES JS -->
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

<!-- CONFETTI -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<?php if($hasil && $hasil['status_kelulusan'] == 'LULUS'): ?>

<script>

confetti({

    particleCount: 250,
    spread: 120,
    origin: { y: 0.6 }

});

</script>

<?php endif; ?>

</body>
</html>