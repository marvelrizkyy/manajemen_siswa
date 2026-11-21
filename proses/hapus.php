
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

// Pastikan request menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    kirimRespons(false, 'Metode request tidak diizinkan.', 405);
}

// Ambil ID siswa
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    kirimRespons(false, 'ID siswa tidak valid.', 400);
}

try {
    // Periksa apakah data siswa tersedia
    $cek = $koneksi->prepare(
        "SELECT id FROM siswa WHERE id = ?"
    );

    $cek->bind_param('i', $id);
    $cek->execute();

    if ($cek->get_result()->num_rows === 0) {
        kirimRespons(false, 'Data siswa tidak ditemukan.', 404);
    }

    // Hapus data menggunakan prepared statement
    $query = "DELETE FROM siswa WHERE id = ?";

    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    kirimRespons(true, 'Data siswa berhasil dihapus.');

} catch (mysqli_sql_exception $e) {
    kirimRespons(false, 'Terjadi kesalahan saat menghapus data.', 500);
}
