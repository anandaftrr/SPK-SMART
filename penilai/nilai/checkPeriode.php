<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_periode = $_POST['id_periode'] ?? null;
    if ($id_periode === null) {
        echo json_encode(['error' => 'Periode tidak ditemukan dalam permintaan.']);
        exit;
    }

    $periode = $koneksi->prepare('SELECT * FROM periode WHERE id = ?');

    $periode->bind_param('i', $id_periode);

    $periode->execute();
    $periode = $periode->get_result();
    $periode = $periode->fetch_assoc();

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode(['data' => $periode]);
    exit;
}
