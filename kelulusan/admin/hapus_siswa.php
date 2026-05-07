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
| CEK ID SISWA
|--------------------------------------------------------------------------
*/
if (!isset($_GET['id'])) {
    header("Location: siswa.php");
    exit;
}

$id = $_GET['id'];

/*
|--------------------------------------------------------------------------
| CEK DATA SISWA
|--------------------------------------------------------------------------
*/
$cek = $conn->query("
    SELECT *
    FROM siswa
    WHERE id='$id'
");

if ($cek->num_rows < 1) {
    header("Location: siswa.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| HAPUS DATA SISWA
|--------------------------------------------------------------------------
*/
$hapus = $conn->query("
    DELETE FROM siswa
    WHERE id='$id'
");

if ($hapus) {

    $_SESSION['success'] = "Data siswa berhasil dihapus";

} else {

    $_SESSION['error'] = "Gagal menghapus data siswa";

}

/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
*/
header("Location: siswa.php");
exit;
?>