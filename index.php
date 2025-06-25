<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "24tiadb";

$conn = mysqli_connect($host, $user, $pass, $db);

if ($conn) {
    echo "Koneksi ke database berhasil!";
} else {
    echo "Koneksi gagal: " . mysqli_connect_error();
}
?>