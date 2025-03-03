<?php
include '../koneksi.php';
$id_periode = $_GET['id_periode'];

$periode = $koneksi->query(
    "SELECT * FROM periode WHERE id = $id_periode"
)->fetch_assoc();

require('fpdf186/fpdf.php');

$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);

$pdf->Image('../gambar/Logo Pemkot Padang.png', 15, 10, 20);
$pdf->Cell(0, 10, 'Pemerintah Kota Padang', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 5, 'Jl. Bagindo Aziz Chan No.1, Aie Pacah, Kec. Koto Tangah,', 0, 1, 'C');
$pdf->Cell(0, 5, 'Kota Padang, Sumatera Barat 25176', 0, 1, 'C');
$pdf->Cell(0, 5, 'Telepon: 0751 4640800, Email: diskominfo@padang.go.id', 0, 1, 'C');

$pdf->Line(10, 40, 200, 40);

$pdf->Ln(10);

$pdf->SetFont('Arial', 'BU', 14);
$pdf->Cell(0, 10, 'HASIL PEMILIHAN KELURAHAN TERBAIK DI KOTA PADANG', 0, 1, 'C');
$pdf->Cell(0, 5, 'PERIODE TAHUN ' . $periode['periode'] . ' MENGGUNAKAN METODE SMART', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Nomor: 123/SK/2025', 0, 1, 'C');
$pdf->Ln(10);

$width = 171;
$x = (210 - $width) / 2;
$pdf->SetX($x);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(21, 10, 'Rangking', 1, 0, 'C');
$pdf->Cell(100, 10, 'Kelurahan', 1, 0, 'C');
$pdf->Cell(50, 10, 'Nilai', 1, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$nilai = $koneksi->query(
    "SELECT kelurahan.kelurahan, hasil.hasil FROM hasil LEFT JOIN alternatif ON alternatif.id = hasil.id_alternatif LEFT JOIN periode ON periode.id = alternatif.id_periode LEFT JOIN kelurahan ON kelurahan.id = alternatif.id_kelurahan WHERE periode.id = $id_periode ORDER BY hasil DESC;"
);
$rangking = 1;
foreach ($nilai as $nil) {

    $pdf->SetX($x);
    $pdf->Cell(21, 10, $rangking, 1, 0, 'C');
    $pdf->Cell(100, 10, $nil['kelurahan'], 1, 0, 'C');
    $pdf->Cell(50, 10, $nil['hasil'], 1, 1, 'C');

    $rangking += 1;
}

$pdf->Ln(10);

$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(0, 5, 'Ditetapkan di: Padang,', 0, 1, 'L');
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(0, 5, 'Pada: ' . date('d-m-Y') . ',', 0, 1, 'L');
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(0, 5, 'Jabatan,', 0, 1, 'L');
$pdf->Ln(10);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(0, 5, 'Tanda Tangan,', 0, 1, 'L');
$pdf->Ln(10);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, 'Nama', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(10, 5, '', 0, 0);
$pdf->Cell(0, 5, 'NIP ', 0, 1, 'L');

$pdf->Output('I', 'surat_keputusan.pdf');
