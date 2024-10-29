<?php
session_start();
include '../koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'kelurahan') {
    // Jika bukan admin, redirect ke halaman lain atau berikan pesan akses ditolak
    header('Location: unauthorized.php');
    exit;
}

$id_kelurahan = $_GET['id_kelurahan'];
$id_periode = $_GET['id_periode'];

$kelurahan = $koneksi->query(
    "SELECT * FROM kelurahan WHERE id = '$id_kelurahan'"
);
$kelurahan = $kelurahan->fetch_assoc();

$periode = $koneksi->query(
    "SELECT * FROM periode WHERE id = $id_periode"
);
$periode = $periode->fetch_assoc();

$administrasi = $koneksi->query(
    "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
);

// var_dump($administrasi);
// die();
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
        <?php include '../layouts/kelurahan_sidebar.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Data Administrasi</h1>
                </div>
                <!-- End Page Title -->
                <!-- Home Page -->
                <!-- Home Page -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                            </div>
                            <div class="col-auto">
                                <div class="form-group">
                                    <select name="orbitas" id="orbitas" class="form-control" onchange="changeAdministrasiPeriode(this.value)" required>
                                        <?php
                                        $user_id = $_SESSION['id_user'];

                                        $result = $koneksi->query(
                                            "SELECT * FROM users RIGHT JOIN kelurahan ON users.id_kelurahan = kelurahan.id WHERE users.id = $user_id;"
                                        );

                                        $kelurahan = $result->fetch_assoc();

                                        $periods = $koneksi->query(
                                            'SELECT * FROM periode ORDER BY id DESC'
                                        );
                                        $id_kelurahan = $kelurahan['id'];
                                        ?>
                                        <?php foreach ($periods as $periode): ?>
                                            <?php
                                            $id_periode = $periode['id'];
                                            $administrasi = $koneksi->query(
                                                "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
                                            );

                                            if ($administrasi->num_rows == 0) {
                                                $administrasi = [$id_kelurahan, $id_periode, "-", "-", "-", "-", "-", "-", "-"];
                                            } else {
                                                $administrasi = $administrasi->fetch_assoc();
                                            }

                                            $administrasi = implode(", ", $administrasi);

                                            ?>
                                            <option value="<?= $administrasi ?>">Periode <?= $periode['periode'] ?> </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                            $periods = $koneksi->query(
                                'SELECT * FROM periode ORDER BY id DESC'
                            );
                            $data = $periods->fetch_assoc();

                            $id_periode = $data['id'];

                            $administrasi_first = $koneksi->query(
                                "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
                            );

                            $administrasi_first = $administrasi_first->fetch_assoc();

                            ?>
                            <div class="col-auto">
                                <a href="/kelurahan/edit_adm_kelurahan.php?id_kelurahan=<?= $id_kelurahan ?>&id_periode=<?= $id_periode ?>" id="buttonEdit">
                                    <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#datawilayahedit">
                                        <i class="fas fa-solid fa-edit"></i> Edit
                                    </button>
                                </a>
                            </div>
                        </div>
                        <?php
                        $bidangs = $koneksi->query(
                            "SELECT * FROM bidang"
                        );
                        $indikators = $koneksi->query(
                            "SELECT * FROM indikator"
                        );
                        $sub_indikators = $koneksi->query(
                            "SELECT * FROM sub_indikator"
                        );
                        $nilai_sub_indikators = $koneksi->query(
                            "SELECT * FROM nilai_sub_indikator"
                        );
                        ?>
                        <?php foreach ($bidangs as $bidang): ?>
                            <h4 class="mt-5" style="font-weight: bold;">Bidang <?= $bidang['nama_bidang'] ?></h4>
                            <hr style="border: 1px solid black;">
                            <?php foreach ($indikators as $indikator): ?>
                                <?php if ($indikator['id_bidang'] == $bidang['id']): ?>
                                    <div class="border border-secondary rounded m-3 p-2">
                                        <h5 style="font-weight: bold;">Indikator: <?= $indikator['nama_indikator'] ?></h5>
                                        <?php foreach ($sub_indikators as $sub_indikator): ?>
                                            <?php if ($indikator['id'] == $sub_indikator['id_indikator']): ?>
                                                <div class="form-group p-2 m-1">
                                                    <label for="usia_kurang_15"><?= $sub_indikator['nama_sub_indikator'] ?></label>
                                                    <select class="form-control" name="role" id="role" required>
                                                        <option value="" selected disabled>-Pilih-</option>
                                                        <?php foreach ($nilai_sub_indikators as $nilai_sub_indikator): ?>
                                                            <?php if ($sub_indikator['id'] == $nilai_sub_indikator['id_sub_indikator']): ?>
                                                                <option value="<?= $nilai_sub_indikator['point'] ?>"
                                                                    <?php

                                                                    $adms = $koneksi->query(
                                                                        "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
                                                                    );
                                                                    if ($adms) {
                                                                        foreach ($adms as $adm) {
                                                                            if ($adm['id_nilai_sub_indikator'] == $nilai_sub_indikator['id']) {
                                                                                echo 'selected';
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>><?= $nilai_sub_indikator['nama_nilai_sub_indikator'] ?></option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
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
    </script>

    <script>
        function changeAdministrasiPeriode(kelurahan_periode = null) {
            // console.log(kelurahan_periode);
            let resultArray = kelurahan_periode.split(", ");

            // Output the resulting array
            console.log(resultArray[6]);
            document.getElementById("usia_kurang_15").textContent = resultArray[2];
            document.getElementById("usia_15_56").textContent = resultArray[3];
            document.getElementById("usia_lebih_56").textContent = resultArray[4];
            document.getElementById("penduduk_total").textContent = resultArray[5];
            document.getElementById("penduduk_laki_laki").textContent = resultArray[6];
            document.getElementById("penduduk_perempuan").textContent = resultArray[7];
            document.getElementById("jumlah_kepala_keluarga").textContent = resultArray[8];

            document.getElementById("buttonEdit").setAttribute("href", "/kelurahan/edit_kelurahan_periode.php?id_kelurahan=" + resultArray[0] + "&id_periode=" + resultArray[1]);
        }
    </script>
</body>

</html>