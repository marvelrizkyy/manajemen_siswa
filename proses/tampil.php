
<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../koneksi.php';

try {

    $query = "SELECT id, nis, nama, kelas, jurusan, alamat
              FROM siswa
              ORDER BY id DESC";

    $result = $koneksi->query($query);

    $dataSiswa = [];

    while ($row = $result->fetch_assoc()) {
        $dataSiswa[] = $row;
    }

    echo json_encode([
        'status' => true,
        'message' => 'Data siswa berhasil diambil',
        'data' => $dataSiswa
    ]);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'status' => false,
        'message' => 'Gagal mengambil data siswa'
    ]);
}
