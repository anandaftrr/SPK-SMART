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
    header('Location: /autentikasi/login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'penilai') {
    // Jika bukan admin, redirect ke halaman lain atau berikan pesan akses ditolak
    header('Location: /autentikasi/unauthorized.php');
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
                    <h1>Hasil Normalisasi Periode <?= $periode['periode'] ?></h1>
                </div>
                <!-- End Page Title -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Normalisasi Kriteria</h5>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="myTable1" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr align="center">
                                                <th>Kode</th>
                                                <th>Kriteria</th>
                                                <th>Jenis</th>
                                                <th>Bobot</th>
                                                <th>Normalisasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($norm_kriteriaArray as $krit) : ?>
                                                <tr align="center">
                                                    <td>C<?= $krit['id'] ?></td>
                                                    <td><?= $krit['nama'] ?></td>
                                                    <td><?= $krit['jenis'] ?></td>
                                                    <td><?= $krit['bobot'] ?></td>
                                                    <td><?= $krit['normalisasi'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Data Alternatif</h5>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="myTable1" class="table table-bordered" style="width:100%">
                                        <thead>
                                            <tr align="center" class="table-secondary">
                                                <th rowspan="2">Alternatif</th>
                                                <th colspan="3">Kriteria</th>
                                            </tr>
                                            <tr align="center" class="table-secondary">
                                                <th>C1</th>
                                                <th>C2</th>
                                                <th>C3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $penilaian_sort = $koneksi->query(
                                                "SELECT alternatif.*, kelurahan.kelurahan FROM alternatif LEFT JOIN kelurahan ON kelurahan.id = alternatif.id_kelurahan WHERE id_periode = $id_periode ORDER BY kelurahan.kelurahan;"
                                            );
                                            ?>
                                            <?php foreach ($penilaian_sort as $p_sort) : ?>
                                                <?php foreach ($penilaian as $key => $value) : ?>
                                                    <?php if ($p_sort['id'] == $key): ?>
                                                        <tr align="center">
                                                            <?php
                                                            $alternatif = $koneksi->query(
                                                                "SELECT alternatif.*, kelurahan.kelurahan FROM alternatif LEFT JOIN kelurahan ON kelurahan.id = alternatif.id_kelurahan WHERE alternatif.id = $key;"
                                                            )->fetch_assoc();
                                                            ?>
                                                            <td><?= $alternatif['kelurahan'] ?></td>
                                                            <?php foreach ($penilaian[$key] as $key2 => $value2) : ?>
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
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <thead style="background-color: #bfbfbf;">
                                            <tr align="center" class="table-secondary">
                                                <th class="table-secondary">MIN</th>
                                                <?php foreach ($min_col as $min): ?>
                                                    <td><?= $min ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                            <tr align="center" class="table-secondary">
                                                <th class="table-secondary">MAX</th>
                                                <?php foreach ($max_col as $max): ?>
                                                    <td><?= $max ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Nilai Utility</h5>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="myTable1" class="table table-bordered" style="width:100%">
                                        <thead>
                                            <tr align="center" class="table-secondary">
                                                <th rowspan="2">Alternatif</th>
                                                <th colspan="3">Kriteria</th>
                                            </tr>
                                            <tr align="center" class="table-secondary">
                                                <th>C1</th>
                                                <th>C2</th>
                                                <th>C3</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $nilai_utility_sort = $koneksi->query(
                                                "SELECT alternatif.*, kelurahan.kelurahan FROM alternatif LEFT JOIN kelurahan ON kelurahan.id = alternatif.id_kelurahan WHERE id_periode = $id_periode ORDER BY kelurahan.kelurahan;"
                                            );
                                            ?>
                                            <?php foreach ($nilai_utility_sort as $nu_sort) : ?>
                                                <?php foreach ($nilai_utility as $key => $value) : ?>
                                                    <?php if ($nu_sort['id'] == $key): ?>
                                                        <tr align="center">
                                                            <?php
                                                            $alternatif = $koneksi->query(
                                                                "SELECT alternatif.*, kelurahan.kelurahan FROM alternatif LEFT JOIN kelurahan ON kelurahan.id = alternatif.id_kelurahan WHERE alternatif.id = $key;"
                                                            )->fetch_assoc();
                                                            ?>
                                                            <td><?= $alternatif['kelurahan'] ?></td>
                                                            <?php foreach ($nilai_utility[$key] as $key2 => $value2) : ?>
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
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="/penilai/hasil_smart/hasil_smart.php?id_periode=<?= $_GET['id_periode'] ?>" class="btn btn-primary float-end">
                                Hasil <i class="fas fa-arrow-right"></i>
                            </a>
                            <a href="/penilai/nilai/nilai.php?id_periode=<?= $_GET['id_periode'] ?>" class="btn btn-primary float-end me-3">
                                <i class="fas fa-arrow-left"></i> Penilaian
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