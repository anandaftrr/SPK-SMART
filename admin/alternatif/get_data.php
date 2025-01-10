<?php
include '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $periode = $_POST['periode'] ?? null;

    if ($periode === null) {
        echo json_encode(['error' => 'Periode tidak ditemukan dalam permintaan.']);
        exit;
    }

    // Query dengan prepared statement
    $query = $koneksi->prepare(
        'SELECT kelurahan.kelurahan, 
                SUM(nilai_sub_indikator.point) AS total_nilai_akhir 
         FROM administrasi 
         JOIN nilai_sub_indikator ON administrasi.id_nilai_sub_indikator = nilai_sub_indikator.id 
         JOIN kelurahan ON administrasi.id_kelurahan = kelurahan.id 
         WHERE administrasi.id_periode = ? 
         GROUP BY administrasi.id_kelurahan, kelurahan.kelurahan 
         ORDER BY total_nilai_akhir DESC'
    );

    // Eksekusi query
    $query->bind_param('i', $periode);
    $query->execute();
    $result = $query->get_result();

    // Ambil data hasil query
    $data = [];
    $i = 0;
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
        $data[$i]['ranking'] = $i + 1;
        $i++;
    }

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode(['data' => $data]);
    exit;
}
