<?php
// $id_periode = 3;
// list($penilaian, $min_col, $max_col, $norm_kriteriaArray, $nilai_utility, $nilai_akhir) = smartMethod($id_periode);
// print_r($penilaian);
// echo '-----------';
// print_r($min_col);
// echo '-----------';
// print_r($max_col);
// echo '-----------';
// print_r($norm_kriteriaArray);
// echo '-----------';
// print_r($nilai_utility);
// echo '-----------';
// print_r($nilai_akhir);
// echo '-----------';
function smartMethod($id_periode = null)
{
    include '../../koneksi.php';
    $kriteria = $koneksi->query(
        "SELECT * FROM kriteria"
    );
    $kriteriaArray = array();
    while ($row = $kriteria->fetch_assoc()) {
        $kriteriaArray[] = $row;
    }

    $alternatif = $koneksi->query(
        "SELECT * FROM alternatif WHERE id_periode = $id_periode"
    );
    $alternatifArray = array();
    while ($row = $alternatif->fetch_assoc()) {
        $alternatifArray[] = $row;
    }

    $penilaian = array();
    foreach ($kriteriaArray as $kri) {
        foreach ($alternatifArray as $alt) {
            if ($kri['nama'] == 'Presentasi') {
                $sub_presentasi = $koneksi->query(
                    "SELECT * FROM sub_presentasi WHERE id_alternatif = " . $alt['id'] . ";"
                )->fetch_assoc();
                $nilai_presentasi = round(($sub_presentasi['isi_materi'] + $sub_presentasi['organisir_waktu'] + $sub_presentasi['tanya_jawab']) / 3, 3);
                $koneksi->query(
                    "DELETE FROM penilaian WHERE id_alternatif = " . $alt['id'] . " AND id_kriteria= " . $kri['id'] . ";"
                );
                $koneksi->query(
                    "INSERT INTO penilaian(id_alternatif, id_kriteria, nilai) VALUES (" . $alt['id'] . "," . $kri['id'] . ",$nilai_presentasi)"
                );
                $penilaian[$alt['id']][$kri['id']] = $nilai_presentasi;
            } elseif ($kri['nama'] == 'Wawancara') {
                $sub_wawancara = $koneksi->query(
                    "SELECT * FROM sub_wawancara WHERE id_alternatif = " . $alt['id'] . ";"
                )->fetch_assoc();
                $nilai_wawancara = round(($sub_wawancara['kerjasama_tim'] + $sub_wawancara['kemampuan_lurah'] + $sub_wawancara['kemampuan_problem_solving']) / 3, 3);
                $koneksi->query(
                    "DELETE FROM penilaian WHERE id_alternatif = " . $alt['id'] . " AND id_kriteria= " . $kri['id'] . ";"
                );
                $koneksi->query(
                    "INSERT INTO penilaian(id_alternatif, id_kriteria, nilai) VALUES (" . $alt['id'] . "," . $kri['id'] . ",$nilai_wawancara)"
                );
                $penilaian[$alt['id']][$kri['id']] = $nilai_wawancara;
            } elseif ($kri['nama'] == 'Verifikasi Lapangan') {
                $total_nilai = $koneksi->query(
                    "SELECT SUM(nilai_sub_indikator.point) AS total_nilai FROM sub_verifikasi_lapangan LEFT JOIN nilai_sub_indikator ON nilai_sub_indikator.id = sub_verifikasi_lapangan.id_nilai_sub_indikator WHERE sub_verifikasi_lapangan.id_alternatif = " . $alt['id'] . " AND sub_verifikasi_lapangan.tak_bernilai = '0' AND sub_verifikasi_lapangan.hasil_verifikasi = '1';"
                )->fetch_assoc();
                $koneksi->query(
                    "DELETE FROM penilaian WHERE id_alternatif = " . $alt['id'] . " AND id_kriteria= " . $kri['id'] . ";"
                );
                $koneksi->query(
                    "INSERT INTO penilaian(id_alternatif, id_kriteria, nilai) VALUES (" . $alt['id'] . "," . $kri['id'] . "," . $total_nilai['total_nilai'] . ")"
                );
                $penilaian[$alt['id']][$kri['id']] = $total_nilai['total_nilai'];
            }
        }
    }

    $norm_kriteria = $koneksi->query(
        "SELECT 
        kriteria.*, 
        ROUND(kriteria.bobot / total_bobot, 3) AS normalisasi
    FROM 
        kriteria, 
        (SELECT SUM(bobot) AS total_bobot FROM kriteria) AS total;"
    );
    $norm_kriteriaArray = array();
    while ($row = $norm_kriteria->fetch_assoc()) {
        $norm_kriteriaArray[] = $row;
    }

    foreach ($kriteriaArray as $kri) {
        $col = array_column($penilaian, $kri['id']);
        $min_col[$kri['id']] = min($col);
        $max_col[$kri['id']] = max($col);
    }

    $nilai_utility = array();
    foreach ($penilaian as $key => $value) {
        foreach ($penilaian[$key] as $key2 => $value2) {
            $nilai_utility[$key][$key2] = round((($penilaian[$key][$key2] - $min_col[$key2]) / ($max_col[$key2] - $min_col[$key2])), 3);
        }
    }

    $nilai_akhir = array();
    foreach ($nilai_utility as $key => $value) {
        foreach ($nilai_utility[$key] as $key2 => $value2) {
            $nilai_akhir[$key][$key2] = round($norm_kriteriaArray[$key2 - 1]['normalisasi'] * $nilai_utility[$key][$key2], 3);
        }
    }

    foreach ($nilai_akhir as $key => $value) {
        $nilai_akhir[$key]['total'] = array_sum($nilai_akhir[$key]);
        $koneksi->query(
            "DELETE FROM hasil WHERE id_alternatif = " . $key . ";"
        );
        $koneksi->query(
            "INSERT INTO hasil(id_alternatif, hasil) VALUES (" . $key . "," . $nilai_akhir[$key]['total'] . ")"
        );
    }

    // print_r($nilai_akhir);
    return [$penilaian, $min_col, $max_col, $norm_kriteriaArray, $nilai_utility, $nilai_akhir];
}
