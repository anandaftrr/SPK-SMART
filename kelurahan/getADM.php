<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kelurahan = $_POST['id_kelurahan'] ?? null;
    $id_periode = $_POST['id_periode'] ?? null;

    if ($id_kelurahan === null) {
        echo json_encode(['error' => 'Kelurahan tidak ditemukan dalam permintaan.']);
        exit;
    }
    if ($id_periode === null) {
        echo json_encode(['error' => 'Periode tidak ditemukan dalam permintaan.']);
        exit;
    }

    $periode = $koneksi->prepare('SELECT * FROM periode WHERE id = ?');

    $periode->bind_param('i', $id_periode);

    $periode->execute();
    $periode = $periode->get_result();
    $periode = $periode->fetch_assoc();

    // Query dengan prepared statement
    $query = $koneksi->prepare(
        'SELECT * FROM administrasi a LEFT JOIN nilai_sub_indikator nsi ON nsi.id = a.id_nilai_sub_indikator LEFT JOIN periode p ON p.id = a.id_periode WHERE a.id_kelurahan = ? AND a.id_periode = ?'
    );

    // Bind parameter secara terpisah
    $query->bind_param('si', $id_kelurahan, $id_periode); // 's' untuk VARCHAR dan 'i' untuk INTEGER


    // Eksekusi query
    $query->execute();
    $result = $query->get_result();

    // Ambil data hasil query
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Tambahkan data ke dalam array
    }

    $query2 = $koneksi->prepare(
        'SELECT i.id_bidang, b.nama_bidang, si.id_indikator, i.nama_indikator, si.id, si.nama_sub_indikator, si.butuh_bukti FROM sub_indikator si LEFT JOIN indikator i ON i.id = si.id_indikator LEFT JOIN bidang b ON b.id = i.id_bidang;'
    );

    // Eksekusi query
    $query2->execute();
    $result2 = $query2->get_result();

    // Ambil data hasil query
    $data2 = [];
    while ($row = $result2->fetch_assoc()) {
        $data2[] = $row; // Tambahkan data ke dalam array
    }

    // Iterasi array $data2 dan $data
    foreach ($data2 as &$subIndikator) {
        $matched = false;  // Menandai jika ditemukan kecocokan

        // Cari data yang sesuai berdasarkan id_sub_indikator
        foreach ($data as $nilaiSubIndikator) {
            if ($subIndikator['id'] == $nilaiSubIndikator['id_sub_indikator']) {

                $query3 = $koneksi->prepare(
                    'SELECT * FROM administrasi_bukti WHERE id_administrasi = ' . $nilaiSubIndikator['id_administrasi'] . ';'
                );
                // Eksekusi query
                $query3->execute();
                $result3 = $query3->get_result();

                // Ambil data hasil query
                $file_bukti = [];
                while ($row = $result3->fetch_assoc()) {
                    $file_bukti[] = $row; // Tambahkan data ke dalam array
                }

                // Simpan nilai_nilai_sub_indikator, nama_nilai_sub_indikator dan point ke dalam $data2
                $subIndikator['id_periode'] = $id_periode;
                $subIndikator['tutup_periode_administrasi'] = $periode['tutup_periode_administrasi'];
                $subIndikator['id_kelurahan'] = $id_kelurahan;
                $subIndikator['id_nilai_sub_indikator'] = $nilaiSubIndikator['id_nilai_sub_indikator'];
                $subIndikator['nama_nilai_sub_indikator'] = $nilaiSubIndikator['nama_nilai_sub_indikator'];
                $subIndikator['point'] = $nilaiSubIndikator['point'];
                if (empty($file_bukti)) {
                    $subIndikator['file_bukti'] = null;
                } else {
                    $subIndikator['file_bukti'] = $file_bukti;
                }
                $matched = true;  // Tandai ada kecocokan
                break;  // Keluar dari loop setelah menemukan kecocokan
            }
        }

        // Jika tidak ditemukan kecocokan, set nilai menjadi null
        if (!$matched) {
            $subIndikator['id_periode'] = $id_periode;
            $subIndikator['tutup_periode_administrasi'] = $periode['tutup_periode_administrasi'];
            $subIndikator['id_kelurahan'] = $id_kelurahan;
            $subIndikator['id_nilai_sub_indikator'] = null;
            $subIndikator['nama_nilai_sub_indikator'] = null;
            $subIndikator['point'] = null;
            $subIndikator['file_bukti'] = null;
        }
    }

    // Mengelompokkan data berdasarkan id_indikator
    $groupedData = [];
    foreach ($data2 as $index => $item) {
        $groupedData[$item['id_indikator']][] = [
            'index' => $index, // Menyimpan indeks asli
            'item' => $item
        ];
    }

    // Proses untuk menghapus data dengan id_indikator 60 yang memiliki id_nilai_sub_indikator null
    foreach ($groupedData as $id_indikator => $items) {
        // Cek hanya untuk id_indikator 60
        if ($id_indikator === 60) {
            // Jika terdapat lebih dari satu item dengan id_indikator yang sama
            if (count($items) > 1) {
                // Memeriksa apakah ada item dengan id_nilai_sub_indikator tidak null
                $hasNonNull = false;
                foreach ($items as $item) {
                    if ($item['item']['id_nilai_sub_indikator'] !== null) {
                        $hasNonNull = true;
                        break;
                    }
                }

                // Jika ada item dengan id_nilai_sub_indikator yang tidak null, sisakan yang itu
                if ($hasNonNull) {
                    foreach ($items as $item) {
                        if ($item['item']['id_nilai_sub_indikator'] === null) {
                            unset($data2[$item['index']]); // Hapus data yang id_nilai_sub_indikator null
                        }
                    }
                } else {
                    // Jika semua item memiliki id_nilai_sub_indikator null, sisakan satu
                    $first = true;
                    foreach ($items as $item) {
                        if ($first) {
                            $first = false;
                        } else {
                            unset($data2[$item['index']]); // Hapus data selain yang pertama
                        }
                    }
                }
            }
        }
    }

    $data2 = array_values($data2);

    // Output JSON
    header('Content-Type: application/json');
    echo json_encode(['data' => $data2]);
    exit;
}
