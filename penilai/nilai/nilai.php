<?php
session_start();
include '../../koneksi.php';
$id_periode = $_GET['id_periode'];
$back = '/penilai/periode/lihat.php';

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
    <?php if ($periode['tutup_periode_administrasi'] == '0') : ?>
        <script>
            // Menjalankan SweetAlert secara otomatis saat halaman dimuat
            window.onload = function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Periode administrasi belum ditutup!',
                    text: 'Anda belum bisa memberikan penilaian pada periode ini.',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false, // Mencegah menutup dengan klik di luar modal
                }).then((result) => {
                    // Redirect ke halaman tertentu setelah pesan ditutup
                    if (result.isConfirmed) {
                        window.location.href = '/penilai/periode/lihat.php'; // Ganti URL dengan halaman tujuan
                    }
                });
            };
        </script>
    <?php endif; ?>
    <div class="wrapper">
        <?php include '../../layouts/header.php'; ?>
        <?php include '../../layouts/penilai_sidebar_periode.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Data Penilaian Periode <?= $periode['periode'] ?></h1>
                </div>
                <!-- End Page Title -->
                <div class="card">
                    <div class="card-body">
                        <?php
                        $alternatives = $koneksi->query(
                            'SELECT alternatif.*, kelurahan.kelurahan FROM alternatif JOIN kelurahan ON kelurahan.id = alternatif.id_kelurahan WHERE id_periode = ' . $id_periode . ' ORDER BY kelurahan.kelurahan;'
                        );
                        ?>
                        <?php if ($alternatives->num_rows == 0): ?>
                            <div class="row" id="notif_data">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Warning!</strong> Data alternatif kosong pada periode ini.
                                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="table-responsive">
                            <table id="myTable1" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">No</th>
                                        <th style="text-align: center;">Alternatif</th>
                                        <th style="text-align: center;">Presentasi</th>
                                        <th style="text-align: center;">Wawancara</th>
                                        <th style="text-align: center;">Klarifikasi Lapangan</th>
                                        <th style="text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($alternatives as $alternative): ?>
                                        <tr align="center">
                                            <td><?= $no ?></td>
                                            <td>
                                                <?= $alternative['kelurahan'] ?>
                                            </td>
                                            <td>
                                                <?php

                                                $sub_presentasi = $koneksi->query(
                                                    'SELECT * FROM sub_presentasi WHERE id_alternatif = ' . $alternative['id'] . ';'
                                                )->fetch_assoc();
                                                if ($sub_presentasi) {
                                                    echo round(($sub_presentasi['isi_materi'] + $sub_presentasi['organisir_waktu'] + $sub_presentasi['tanya_jawab']) / 3, 3);
                                                } else {
                                                    echo '-';
                                                }

                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                $sub_wawancara = $koneksi->query(
                                                    'SELECT * FROM sub_wawancara WHERE id_alternatif = ' . $alternative['id'] . ';'
                                                )->fetch_assoc();
                                                if ($sub_wawancara) {
                                                    echo round(($sub_wawancara['kerjasama_tim'] + $sub_wawancara['kemampuan_lurah'] + $sub_wawancara['kemampuan_problem_solving']) / 3, 3);
                                                } else {
                                                    echo '-';
                                                }

                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $total_nilai = $koneksi->query(
                                                    "SELECT SUM(nilai_sub_indikator.point) AS total_nilai FROM sub_verifikasi_lapangan LEFT JOIN nilai_sub_indikator ON nilai_sub_indikator.id = sub_verifikasi_lapangan.id_nilai_sub_indikator WHERE sub_verifikasi_lapangan.id_alternatif = " . $alternative['id'] . " AND sub_verifikasi_lapangan.tak_bernilai = '0' AND sub_verifikasi_lapangan.hasil_verifikasi = '1';"
                                                )->fetch_assoc();

                                                $sub_ver_all = $koneksi->query(
                                                    "SELECT COUNT(id) AS total_row FROM sub_verifikasi_lapangan WHERE id_alternatif = " . $alternative['id'] . ";"
                                                )->fetch_assoc();

                                                $sub_ver_not_null = $koneksi->query(
                                                    "SELECT COUNT(id) AS total_row FROM sub_verifikasi_lapangan WHERE id_alternatif = " . $alternative['id'] . " AND hasil_verifikasi IS NOT null;"
                                                )->fetch_assoc();

                                                ?>
                                                <?= $total_nilai['total_nilai'] ? $total_nilai['total_nilai'] : '-' ?><br>
                                                <?= ($sub_ver_all['total_row'] == $sub_ver_not_null['total_row']) ? '<span class="badge rounded-pill bg-primary">Terverifikasi semua</span>' : '<span class="badge rounded-pill bg-danger">Belum terverifikasi semua</span>' ?>
                                            </td>
                                            <td>
                                                <a href="/penilai/nilai/detail.php?id_periode=<?= $id_periode ?>&id_alternatif=<?= $alternative['id'] ?>">
                                                    <button type="button" class="btn btn-info btn-sm">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $no++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-auto">
                            <a href="/penilai/normalisasi/normalisasi.php?id_periode=<?= $_GET['id_periode'] ?>" class="btn btn-primary float-end">
                                Normalisasi <i class="fas fa-arrow-right"></i>
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