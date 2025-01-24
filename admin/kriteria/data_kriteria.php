<?php
session_start();
include '../../koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: /autentikasi/login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'admin') {
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
        <?php include '../../layouts/admin_sidebar.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Data Kriteria</h1>
                </div>
                <!-- End Page Title -->
                <!-- Home Page -->
                <div class="table-responsive">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="myTable" class="table table-bordered" style="width:100%; text-align: center;">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">Kode Kriteria</th>
                                            <th style="text-align: center;">Kriteria</th>
                                            <th style="text-align: center;">Bobot</th>
                                            <th style="text-align: center;">Subkriteria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td rowspan="3" style="text-align: center; vertical-align: middle;">C1</td>
                                            <td rowspan="3" style="text-align: center; vertical-align: middle;">Presentasi</td>
                                            <td rowspan="3" style="text-align: center; vertical-align: middle;">40</td>
                                            <td>Isi Materi</td>
                                        </tr>
                                        <tr>
                                            <td>Organisir Waktu</td>
                                        </tr>
                                        <tr>
                                            <td>Tanya Jawab</td>
                                        </tr>
                                        <tr>
                                            <td rowspan="3" style="text-align: center; vertical-align: middle;">C2</td>
                                            <td rowspan="3" style="text-align: center; vertical-align: middle;">Wawancara</td>
                                            <td rowspan="3" style="text-align: center; vertical-align: middle;">40</td>
                                            <td>Kerjasama Tim</td>
                                        </tr>
                                        <tr>
                                            <td>Kemampuan Lurah</td>
                                        </tr>
                                        <tr>
                                            <td>Kemampuan Problem Solving</td>
                                        </tr>
                                        <tr>
                                            <td>C3</td>
                                            <td>Klarifikasi Lapangan</td>
                                            <td>50</td>
                                            <td>-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--Tambah Data-->
                </div>
            </section>
        </div>
    </div>
</body>

</html>