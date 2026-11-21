
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

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nis = trim($_POST['nis'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$kelas = trim($_POST['kelas'] ?? '');
$jurusan = trim($_POST['jurusan'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

if (!$id || $nis === '' || $nama === '' || $kelas === '' || $jurusan === '') {
    kirimRespons(false, 'Data wajib diisi dengan benar.', 400);
}

if (strlen($nama) < 3) {
    kirimRespons(false, 'Nama siswa minimal 3 karakter.', 400);
}

try {
    $query = "UPDATE siswa
              SET nis = ?, nama = ?, kelas = ?, jurusan = ?, alamat = ?
              WHERE id = ?";

    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('sssssi', $nis, $nama, $kelas, $jurusan, $alamat, $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        // Memastikan data memang ada meskipun nilainya tidak berubah
        $cek = $koneksi->prepare("SELECT id FROM siswa WHERE id = ?");
        $cek->bind_param('i', $id);
        $cek->execute();

        if ($cek->get_result()->num_rows === 0) {
            kirimRespons(false, 'Data siswa tidak ditemukan.', 404);
        }
    }

    kirimRespons(true, 'Data siswa berhasil diperbarui.');

} catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
        kirimRespons(false, 'NIS sudah digunakan oleh siswa lain.', 409);
    }

    kirimRespons(false, 'Terjadi kesalahan saat memperbarui data.', 500);
}
