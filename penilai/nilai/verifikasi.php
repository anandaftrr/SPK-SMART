<?php
session_start();
include '../../koneksi.php';

$id_periode = $_GET['id_periode'];
$id_alternatif = $_GET['id_alternatif'];
$back = '/penilai/nilai/detail.php?id_periode=' . $id_periode . '&id_alternatif=' . $id_alternatif;

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

$alternatif = $koneksi->query(
    "SELECT * FROM alternatif WHERE id = $id_alternatif"
)->fetch_assoc();

$id_kelurahan = $alternatif['id_kelurahan'];
$id_periode = $alternatif['id_periode'];
$administrasi = $koneksi->query(
    "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['id_user'];
    foreach ($_POST as $key => $value) {
        $update = $koneksi->query(
            "UPDATE sub_verifikasi_lapangan SET hasil_verifikasi = '$value', verifikasi_oleh = $user_id WHERE id = $key"
        );
    }
    if (isset($update)) {
        if ($update) {
            $success = 'Hasil verifikasi berhasil diubah!';
        } else {
            $failed = 'Hasil verifikasi gagal diubah!';
        }
    }
}

$kelurahan = $koneksi->query(
    "SELECT * FROM kelurahan WHERE id = '$id_kelurahan'"
);
$kelurahan = $kelurahan->fetch_assoc();

$periode = $koneksi->query(
    "SELECT * FROM periode WHERE id = $id_periode"
);
$periode = $periode->fetch_assoc();

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
                    <h1>Verifikasi Lapangan <?= $kelurahan['kelurahan'] ?> Periode <?= $periode['periode'] ?></h1>
                </div>
                <!-- End Page Title -->
                <!-- Home Page -->
                <div class="card">
                    <div class="card-body">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> <?= $success ?>
                            </div>
                        <?php elseif (isset($failed)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Failed!</strong> <?= $failed ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="post">
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            $total_nilai = $koneksi->query(
                                                "SELECT SUM(nilai_sub_indikator.point) AS total_nilai FROM sub_verifikasi_lapangan LEFT JOIN nilai_sub_indikator ON nilai_sub_indikator.id = sub_verifikasi_lapangan.id_nilai_sub_indikator WHERE sub_verifikasi_lapangan.id_alternatif = $id_alternatif AND sub_verifikasi_lapangan.tak_bernilai = '0' AND sub_verifikasi_lapangan.hasil_verifikasi = '1';"
                                            )->fetch_assoc();

                                            $sub_ver_all = $koneksi->query(
                                                "SELECT COUNT(id) AS total_row FROM sub_verifikasi_lapangan WHERE id_alternatif = $id_alternatif;"
                                            )->fetch_assoc();

                                            $sub_ver_not_null = $koneksi->query(
                                                "SELECT COUNT(id) AS total_row FROM sub_verifikasi_lapangan WHERE id_alternatif = $id_alternatif AND hasil_verifikasi IS NOT null;"
                                            )->fetch_assoc();

                                            ?>
                                            <h5>Nilai Verifikasi Lapangan: <?= $total_nilai['total_nilai'] ? $total_nilai['total_nilai'] : '-' ?></h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <span>Status: <?= ($sub_ver_all['total_row'] == $sub_ver_not_null['total_row']) ? '<span class="badge rounded-pill bg-primary">Terverifikasi semua</span>' : '<span class="badge rounded-pill bg-danger">Belum terverifikasi semua</span>' ?></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                    </div>
                                </div>
                                <?php

                                $administrasi_first = $koneksi->query(
                                    "SELECT * FROM sub_verifikasi_lapangan WHERE id_alternatif = $id_alternatif"
                                );

                                ?>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#datawilayahadd">
                                        Simpan Data
                                    </button>
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
                            $i = 1;
                            ?>
                            <?php foreach ($bidangs as $bidang): ?>
                                <h4 class="mt-5" style="font-weight: bold;">Bidang <?= $bidang['nama_bidang'] ?></h4>
                                <hr style="border: 1px solid black;">
                                <?php foreach ($indikators as $indikator): ?>
                                    <?php if ($indikator['id_bidang'] == $bidang['id']): ?>
                                        <div class="border border-secondary rounded m-3 p-2">
                                            <h5 style="font-weight: bold;">Indikator: <?= $indikator['nama_indikator'] ?></h5>
                                            <?php if ($indikator['id'] == '60'): ?>
                                                <?php if ($administrasi_first->num_rows == 0): ?>
                                                    <div class="form-group p-2 m-1">
                                                        <table style="width: 100%;">
                                                            <tr>
                                                                <td style="width: 50%;"><span id="nilai<?= $i ?>">-</span></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <?php

                                                    $adm_tak_bernilai = $koneksi->query(
                                                        "SELECT sub_verifikasi_lapangan.*, users.username FROM sub_verifikasi_lapangan LEFT JOIN users ON users.id = sub_verifikasi_lapangan.verifikasi_oleh WHERE id_alternatif = $id_alternatif AND tak_bernilai = '1'"
                                                    )->fetch_assoc();

                                                    if ($adm_tak_bernilai['id_nilai_sub_indikator'] == '372') {
                                                        $pencaharian = 'Pertanian';
                                                    } elseif ($adm_tak_bernilai['id_nilai_sub_indikator'] == '373') {
                                                        $pencaharian = 'Industri';
                                                    } elseif ($adm_tak_bernilai['id_nilai_sub_indikator'] == '374') {
                                                        $pencaharian = 'Jasa';
                                                    }

                                                    if ($adm_tak_bernilai['hasil_verifikasi'] == null) {
                                                        echo '<div class="form-group p-2 m-1"><table style="width: 100%;"><tr><td style="width: 40%;"><span id="nilai' . $i . '">' . $pencaharian . '</span></td><td style="width: 50%;">Hasil verifikasi : <div class="form-check d-inline ms-3"><input class="form-check-input" type="radio" name="' . $adm_tak_bernilai['id'] . '" id="tgender' . $i . '" value="1"><label class="form-check-label" for="tgender' . $i . '">Benar</label></div><div class="form-check d-inline ms-3"><input class="form-check-input" type="radio" name="' . $adm_tak_bernilai['id'] . '" id="fgender' . $i . '" value="0"><label class="form-check-label" for="fgender' . $i . '">Salah</label></div></td></tr></table></div>';
                                                    } else {
                                                        if ($adm_tak_bernilai['hasil_verifikasi'] == '1') {
                                                            echo '<div class="form-group p-2 m-1"><table style="width: 100%;"><tr><td style="width: 40%;"><span id="nilai' . $i . '">' . $pencaharian . '</span></td><td style="width: 20%;">Hasil verifikasi : <span class="badge rounded-pill bg-success">Benar</span></td><td style="width: 30%;">Verifikasi oleh : ' . $adm_tak_bernilai['username'] . '</td></tr></table></div>';
                                                        } elseif ($adm_tak_bernilai['hasil_verifikasi'] == '0') {
                                                            echo '<div class="form-group p-2 m-1"><table style="width: 100%;"><tr><td style="width: 40%;"><span id="nilai' . $i . '">' . $pencaharian . '</span></td><td style="width: 20%;">Hasil verifikasi : <span class="badge rounded-pill bg-danger">Salah</span></td><td style="width: 30%;">Verifikasi oleh : ' . $adm_tak_bernilai['username'] . '</td></tr></table></div>';
                                                        }
                                                    }

                                                    ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php foreach ($sub_indikators as $sub_indikator): ?>
                                                    <?php if ($indikator['id'] == $sub_indikator['id_indikator']): ?>
                                                        <div class="form-group p-2 m-1">
                                                            <label for="usia_kurang_15"><?= $sub_indikator['nama_sub_indikator'] ?></label>
                                                            <?php foreach ($nilai_sub_indikators as $nilai_sub_indikator): ?>
                                                                <?php if ($sub_indikator['id'] == $nilai_sub_indikator['id_sub_indikator']): ?>
                                                                    <?php

                                                                    $adms = $koneksi->query(
                                                                        "SELECT sub_verifikasi_lapangan.*, users.username FROM sub_verifikasi_lapangan LEFT JOIN users ON users.id = sub_verifikasi_lapangan.verifikasi_oleh WHERE id_alternatif = $id_alternatif;"
                                                                    );
                                                                    if ($adms) {
                                                                        foreach ($adms as $adm) {
                                                                            if ($adm['id_nilai_sub_indikator'] == $nilai_sub_indikator['id']) {
                                                                                if ($adm['hasil_verifikasi'] == null) {
                                                                                    echo '<table style="width: 100%;"><tr><td style="width: 30%;"><span id="nilai' . (($i == 171) ? $i + 1 : $i) . '">' . $nilai_sub_indikator['nama_nilai_sub_indikator'] . '</span></td><td style="width: 10%;">Poin : <span id="poin' . (($i == 171) ? $i + 1 : $i) . '">' . $nilai_sub_indikator['point'] . '</span></td><td style="width: 50%;">Hasil verifikasi : <div class="form-check d-inline ms-3"><input class="form-check-input" type="radio" name="' . $adm['id'] . '" id="tgender' . $i . '" value="1"><label class="form-check-label" for="tgender' . $i . '">Benar</label></div><div class="form-check d-inline ms-3"><input class="form-check-input" type="radio" name="' . $adm['id'] . '" id="fgender' . $i . '" value="0"><label class="form-check-label" for="fgender' . $i . '">Salah</label></div></td></tr></table>';
                                                                                } else {
                                                                                    if ($adm['hasil_verifikasi'] == '1') {
                                                                                        echo '<table style="width: 100%;"><tr><td style="width: 30%;"><span id="nilai' . (($i == 171) ? $i + 1 : $i) . '">' . $nilai_sub_indikator['nama_nilai_sub_indikator'] . '</span></td><td style="width: 10%;">Poin : <span id="poin' . (($i == 171) ? $i + 1 : $i) . '">' . $nilai_sub_indikator['point'] . '</span></td><td style="width: 20%;">Hasil verifikasi : <span class="badge rounded-pill bg-success">Benar</span></td><td style="width: 30%;">Verifikasi oleh : ' . $adm['username'] . '</td></tr></table>';
                                                                                    } elseif ($adm['hasil_verifikasi'] == '0') {
                                                                                        echo '<table style="width: 100%;"><tr><td style="width: 30%;"><span id="nilai' . (($i == 171) ? $i + 1 : $i) . '">' . $nilai_sub_indikator['nama_nilai_sub_indikator'] . '</span></td><td style="width: 10%;">Poin : <span id="poin' . (($i == 171) ? $i + 1 : $i) . '">' . $nilai_sub_indikator['point'] . '</span></td><td style="width: 20%;">Hasil verifikasi : <span class="badge rounded-pill bg-danger">Salah</span></td><td style="width: 30%;">Verifikasi oleh : ' . $adm['username'] . '</td></tr></table>';
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <?php
                                                        if ($i == 171) {
                                                            $i += 2;
                                                        } else {
                                                            $i++;
                                                        }
                                                        ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </form>
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