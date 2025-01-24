<?php
session_start();
include '../koneksi.php';

$id_periode = $_GET['id_periode'];

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: /autentikasi/login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'pimpinan') {
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
        <?php include '../layouts/header.php'; ?>
        <?php include '../layouts/pimpinan_sidebar_proses.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <?php
                $periode = $koneksi->query(
                    "SELECT * FROM periode WHERE id = $id_periode;"
                );

                $periode = $periode->fetch_assoc();
                ?>
                <div class="pagetitle p-2">
                    <h1>Hasil Administrasi Periode <?= $periode ? $periode['periode'] : '-' ?></h1>
                </div>
                <!-- End Page Title -->
                <div class="table-responsive">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped" style="width:100%; text-align: center;">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">Rangking</th>
                                            <th style="text-align: center;">Kelurahan</th>
                                            <th style="text-align: center;">Total Nilai Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $items = $koneksi->query(
                                            "SELECT kelurahan.kelurahan, SUM(nilai_sub_indikator.point) AS total_nilai_akhir FROM administrasi JOIN nilai_sub_indikator ON administrasi.id_nilai_sub_indikator = nilai_sub_indikator.id JOIN kelurahan ON administrasi.id_kelurahan = kelurahan.id WHERE administrasi.id_periode = $id_periode GROUP BY administrasi.id_kelurahan, kelurahan.kelurahan ORDER BY total_nilai_akhir DESC;"
                                        );
                                        ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($items as $item): ?>
                                            <tr align="center">
                                                <td>
                                                    <?= $no ?>
                                                </td>
                                                <td>
                                                    <?= $item['kelurahan'] ?>
                                                </td>
                                                <td>
                                                    <?= $item['total_nilai_akhir'] ?>
                                                </td>
                                            </tr>
                                            <?php $no++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>