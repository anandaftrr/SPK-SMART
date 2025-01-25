<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $periode = $_POST['periode'] ?? null;

    if ($periode === null) {
        echo json_encode(['error' => 'Periode tidak ditemukan dalam permintaan.']);
        exit;
    }

    // Query dengan prepared statement
    $query = $koneksi->prepare(
        'SELECT * FROM periode WHERE id = ?'
    );

    // Eksekusi query
    $query->bind_param('i', $periode);
    $query->execute();
    $result = $query->get_result();

    $data = $result->fetch_assoc();
    // Ambil data hasil query
    // $data = [];
    // $data[] = $result->fetch_assoc();
    // $i = 0;
    // while ($row = $result->fetch_assoc()) {
    //     $data[] = $row;
    //     $data[$i]['ranking'] = $i + 1;
    //     $i++;
    // }

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode(['data' => $data]);
    exit;
}
