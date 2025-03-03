<?php
session_start();
include '../koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: /autentikasi/login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'kelurahan') {
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
    <link rel="stylesheet" href="assets/kelurahan.css">

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
                    <h1>Hasil Administrasi</h1>
                </div>
                <!-- End Page Title -->
                <!-- Home Page -->
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="card mt-3">
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
                                                        "SELECT SUM(nilai_sub_indikator.point) AS total_nilai_akhir FROM administrasi, nilai_sub_indikator WHERE administrasi.id_kelurahan = '$id_kelurahan' AND administrasi.id_periode = $id_periode AND administrasi.id_nilai_sub_indikator = nilai_sub_indikator.id"
                                                    );
                                                    $administrasi = $administrasi->fetch_assoc();

                                                    $kel_rec = $koneksi->query(
                                                        "SELECT kelurahan.id, kelurahan.kelurahan, SUM(nilai_sub_indikator.point) AS total_nilai_akhir FROM administrasi JOIN nilai_sub_indikator ON administrasi.id_nilai_sub_indikator = nilai_sub_indikator.id JOIN kelurahan ON administrasi.id_kelurahan = kelurahan.id WHERE administrasi.id_periode = $id_periode GROUP BY administrasi.id_kelurahan, kelurahan.kelurahan ORDER BY total_nilai_akhir DESC LIMIT 4;"
                                                    );

                                                    $rekomendasi = 0;

                                                    foreach ($kel_rec as $kel) {
                                                        if ($kel['id'] == $id_kelurahan) {
                                                            $rekomendasi = 1;
                                                        }
                                                    }

                                                    $val_administrasi = [$id_kelurahan, $id_periode];
                                                    if ($administrasi['total_nilai_akhir'] == null) {
                                                        $val_administrasi[] = "Periode " . $periode['periode'];
                                                        $val_administrasi[] = "-";
                                                    } else {
                                                        $val_administrasi[] = "Periode " . $periode['periode'];
                                                        $val_administrasi[] = $administrasi['total_nilai_akhir'];
                                                    }

                                                    $val_administrasi[] = $rekomendasi;

                                                    $val_administrasi = implode("| ", $val_administrasi);

                                                    ?>
                                                    <option value="<?= $val_administrasi ?>">Periode <?= $periode['periode'] ?> </option>
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
                                        "SELECT SUM(nilai_sub_indikator.point) AS total_nilai_akhir FROM administrasi, nilai_sub_indikator WHERE administrasi.id_kelurahan = '$id_kelurahan' AND administrasi.id_periode = $id_periode AND administrasi.id_nilai_sub_indikator = nilai_sub_indikator.id"
                                    );
                                    $administrasi_first = $administrasi_first->fetch_assoc();

                                    $kelurahan = $koneksi->query(
                                        "SELECT * FROM kelurahan WHERE id = '$id_kelurahan'"
                                    )->fetch_assoc();

                                    ?>
                                </div>
                                <center>
                                    <h4>Total Nilai Akhir <?= $kelurahan['kelurahan'] ?> <span id="periode">Periode <?= $data['periode'] ?></span></h4>
                                </center>
                                <center>
                                    <h1 id="total_nilai_akhir" class="pagetitle p-2"><?= $administrasi_first['total_nilai_akhir'] ? $administrasi_first['total_nilai_akhir'] : '-' ?></h1>
                                </center>
                                <center>
                                    <?php
                                    $kel_rec_first = $koneksi->query(
                                        "SELECT kelurahan.id, kelurahan.kelurahan, SUM(nilai_sub_indikator.point) AS total_nilai_akhir FROM administrasi JOIN nilai_sub_indikator ON administrasi.id_nilai_sub_indikator = nilai_sub_indikator.id JOIN kelurahan ON administrasi.id_kelurahan = kelurahan.id WHERE administrasi.id_periode = $id_periode GROUP BY administrasi.id_kelurahan, kelurahan.kelurahan ORDER BY total_nilai_akhir DESC LIMIT 4;"
                                    );

                                    $rekomendasi_first = 0;

                                    foreach ($kel_rec_first as $kel_first) {
                                        if ($kel_first['id'] == $id_kelurahan) {
                                            $rekomendasi_first = 1;
                                        }
                                    }
                                    ?>
                                    <?php if ($rekomendasi_first == 1): ?>
                                        <h4 id="stat-rec">
                                            <span class="badge rounded-pill bg-primary">Rekomendasi</span>
                                        </h4>
                                    <?php else: ?>
                                        <h4 id="stat-rec">
                                            <span class="badge rounded-pill bg-danger">Tidak Rekomendasi</span>
                                        </h4>
                                    <?php endif; ?>
                                </center>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- End Home Page -->
            </section>
            <br>
        </div>
    </div>
    <script>
        function changeAdministrasiPeriode(administrasi = null) {
            let resultArray = administrasi.split("| ");

            document.getElementById("periode").textContent = resultArray[2];
            document.getElementById("total_nilai_akhir").textContent = resultArray[3];

            if (resultArray[4] === "1") {
                $('#stat-rec').empty();
                $('#stat-rec').append('<span class="badge rounded-pill bg-primary">Rekomendasi</span>');
            } else {
                $('#stat-rec').empty();
                $('#stat-rec').append('<span class="badge rounded-pill bg-danger">Tidak Rekomendasi</span>');
            }
        }
    </script>

</body>

</html>