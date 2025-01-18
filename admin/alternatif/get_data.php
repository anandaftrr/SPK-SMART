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
        'SELECT alternatif.*, kelurahan.kelurahan FROM alternatif JOIN kelurahan ON alternatif.id_kelurahan = kelurahan.id WHERE alternatif.id_periode = ? ORDER BY kelurahan.kelurahan ASC'
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
        $data[$i]['no'] = $i + 1;
        $i++;
    }

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode(['data' => $data]);
    exit;
}
