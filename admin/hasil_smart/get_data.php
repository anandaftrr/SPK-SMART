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
        'SELECT kelurahan.kelurahan, hasil.hasil FROM hasil LEFT JOIN alternatif ON alternatif.id = hasil.id_alternatif LEFT JOIN periode ON periode.id = alternatif.id_periode LEFT JOIN kelurahan ON kelurahan.id = alternatif.id_kelurahan WHERE periode.id = ? ORDER BY hasil DESC'
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

    $data_periode = $koneksi->query(
        "SELECT * FROM periode WHERE id = $periode"
    )->fetch_assoc();

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode(['data' => $data, 'periode' => $data_periode]);
    exit;
}
