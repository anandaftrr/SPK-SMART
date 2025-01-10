<?php
session_start();
include '../../koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'admin') {
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
        <?php include '../../layouts/admin_sidebar.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Data Alternatif</h1>
                </div>
                <!-- End Page Title -->
                <!-- Home Page -->
                <div class="table-responsive">
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row mt-5">
                                <div class="col">
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <select name="orbitas" id="orbitas" class="form-control" onchange="changeKelurahanPeriode(this.value)" required>
                                            <?php
                                            $periods = $koneksi->query(
                                                'SELECT * FROM periode ORDER BY id DESC'
                                            );
                                            ?>
                                            <?php foreach ($periods as $periode): ?>
                                                <option value="<?= $periode['id'] ?>">Periode <?= $periode['periode'] ?> </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
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
                                        $periods = $koneksi->query(
                                            'SELECT * FROM periode ORDER BY id DESC'
                                        );
                                        $data = $periods->fetch_assoc();

                                        $id_periode = $data['id'];
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
                    <!--Tambah Data-->
                </div>
            </section>
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
        </script>
        <script>
            $(document).ready(function() {
                // Inisialisasi DataTables
                const table = $('#myTable').DataTable();

                // Fungsi untuk mengganti data tabel sesuai periode
                window.changeKelurahanPeriode = function(periodeId) {
                    console.log(periodeId);
                    $.ajax({
                        url: 'get_data.php', // Endpoint PHP untuk mendapatkan data berdasarkan periode
                        type: 'POST',
                        data: {
                            periode: periodeId
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Bersihkan tabel
                            table.clear();

                            // Tambahkan data baru ke tabel
                            response.data.forEach(row => {
                                table.row.add([
                                    row.ranking,
                                    row.kelurahan,
                                    row.total_nilai_akhir,
                                ]);
                            });

                            // Render ulang tabel
                            table.draw();
                        },
                        error: function() {
                            alert('Gagal memuat data. Silakan coba lagi.');
                        }
                    });
                };
            });
        </script>
    </div>
</body>

</html>