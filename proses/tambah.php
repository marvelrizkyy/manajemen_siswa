
<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../koneksi.php';

function kirimRespons($status, $message, $kode = 200)
{
    http_response_code($kode);
    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    kirimRespons(false, 'Metode request tidak diizinkan.', 405);
}

$nis = trim($_POST['nis'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$kelas = trim($_POST['kelas'] ?? '');
$jurusan = trim($_POST['jurusan'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

if ($nis === '' || $nama === '' || $kelas === '' || $jurusan === '') {
    kirimRespons(false, 'NIS, nama, kelas, dan jurusan wajib diisi.', 400);
}

if (strlen($nama) < 3) {
    kirimRespons(false, 'Nama siswa minimal 3 karakter.', 400);
}

try {
    $query = "INSERT INTO siswa (nis, nama, kelas, jurusan, alamat)
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('sssss', $nis, $nama, $kelas, $jurusan, $alamat);
    $stmt->execute();

    kirimRespons(true, 'Data siswa berhasil ditambahkan.');
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        kirimRespons(false, 'NIS sudah terdaftar. Gunakan NIS yang berbeda.', 409);
    }

    kirimRespons(false, 'Terjadi kesalahan saat menyimpan data.', 500);
}
