<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "db_manajemen_siswa";

try {
    $koneksi = new mysqli(
        $host,
        $user,
        $password,
        $database
    );

    // Mengatur charset agar mendukung karakter UTF-8
    $koneksi->set_charset("utf8mb4");

} catch (mysqli_sql_exception $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}   