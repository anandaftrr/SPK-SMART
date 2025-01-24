<?php
session_start();
include '../../koneksi.php';
include '../../proses_smart/proses_smart.php';

$id_periode = $_GET['id_periode'];
list($penilaian, $min_col, $max_col, $norm_kriteriaArray, $nilai_utility, $nilai_akhir) = smartMethod($id_periode);
$back = '/penilai/nilai/nilai.php?id_periode=' . $id_periode;

$periode = $koneksi->query(
    "SELECT * FROM periode WHERE id = '$id_periode'"
)->fetch_assoc();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'penilai') {
    // Jika bukan admin, redirect ke halaman lain atau berikan pesan akses ditolak
    header('Location: unauthorized.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan Kelurahan Terbaik</title>
    <link rel="shortcut icon" type="x-ixon" href="gambar/Logo Pemkot Padang.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.0.5/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.0.5/dist/js/adminlte.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/classic/ckeditor.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include '../../layouts/header.php'; ?>
        <?php include '../../layouts/penilai_sidebar_periode.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Hasil SMART Periode <?= $periode['periode'] ?></h1>
                </div>
                <!-- End Page Title -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Nilai Akhir</h5>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="myTable1" class="table table-bordered" style="width:100%">
                                        <thead>
                                            <tr align="center" class="table-secondary">
                                                <th rowspan="2">Alternatif</th>
                                                <th colspan="3">Kriteria</th>
                                                <th rowspan="2">Nilai Akhir</th>
                                                <th rowspan="2">Ranking</th>
                                            </tr>
                                            <tr align="center" class="table-secondary">
                                                <th>C1</th>
                                                <th>C2</th>
                                                <th>C3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            uasort($nilai_akhir, function ($a, $b) {
                                                return $b['total'] <=> $a['total'];  // Mengurutkan dari yang terkecil ke terbesar
                                            });
                                            $i = 1;
                                            ?>
                                            <?php foreach ($nilai_akhir as $key => $value) : ?>
                                                <tr align="center">
                                                    <?php
                                                    $alternatif = $koneksi->query(
                                                        "SELECT alternatif.*, kelurahan.kelurahan FROM alternatif LEFT JOIN kelurahan ON kelurahan.id = alternatif.id_kelurahan WHERE alternatif.id = $key;"
                                                    )->fetch_assoc();
                                                    ?>
                                                    <td>
                                                        <?= $alternatif['kelurahan'] ?>
                                                    </td>
                                                    <?php foreach ($nilai_akhir[$key] as $key2 => $value2) : ?>
                                                        <?php
                                                        $kriteria = $koneksi->query(
                                                            "SELECT * FROM kriteria"
                                                        );
                                                        ?>
                                                        <?php foreach ($kriteria as $krite) : ?>
                                                            <?php if ($krite['id'] == $key2): ?>
                                                                <td><?= $value2 ?></td>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                    <td><?= $nilai_akhir[$key]['total'] ?></td>
                                                    <td><?= $i ?></td>
                                                </tr>
                                            <?php
                                                $i++;
                                            endforeach;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="/penilai/normalisasi/normalisasi.php?id_periode=<?= $_GET['id_periode'] ?>" class="btn btn-primary float-end">
                                <i class="fas fa-arrow-left"></i> Normalisasi
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
        // Membuat data table memiliki fungsi show dan search
        $(document).ready(function() {
            $('#myTable').DataTable(); //Mengubah tabel dengan ID myTable menjadi tabel yang interaktif dengan fitur pencarian, paginasi, dan pengurutan.
        });


        ClassicEditor
            .create(document.querySelector('#detail'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });

        function checkTutupAdmin() {
            Swal.fire({
                icon: 'warning',
                title: 'Periode administrasi belum ditutup!',
                text: 'Anda belum bisa memberikan penilaian pada periode ini.',
                confirmButtonText: 'OK',
                allowOutsideClick: false, // Mencegah menutup dengan klik di luar modal
            });
        }
    </script>
</body>

</html>