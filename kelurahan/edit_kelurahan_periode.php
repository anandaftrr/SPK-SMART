<?php
session_start();
include '../koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: /autentikasi/login.php');
    exit();
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'kelurahan') {
    // Jika bukan admin, redirect ke halaman lain atau berikan pesan akses ditolak
    header('Location: /autentikasi/unauthorized.php');
    exit();
}

$id_kelurahan = $_GET['id_kelurahan'];
$id_periode = $_GET['id_periode'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usia_kurang_15 = $_POST['usia_kurang_15'];
    $usia_15_56 = $_POST['usia_15_56'];
    $usia_lebih_56 = $_POST['usia_lebih_56'];
    $penduduk_total = $_POST['penduduk_total'];
    $penduduk_laki_laki = $_POST['penduduk_laki_laki'];
    $penduduk_perempuan = $_POST['penduduk_perempuan'];
    $jumlah_kepala_keluarga = $_POST['jumlah_kepala_keluarga'];


    $kelurahan_periode = $koneksi->query(
        "SELECT * FROM kelurahan_periode WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
    );

    if ($kelurahan_periode->num_rows == 0) {
        $insert = $koneksi->query(
            "INSERT INTO kelurahan_periode (id_kelurahan, id_periode, usia_kurang_15, usia_15_56, usia_lebih_56, penduduk_total, penduduk_laki_laki, penduduk_perempuan, jumlah_kepala_keluarga) VALUES ('$id_kelurahan', $id_periode, $usia_kurang_15, $usia_15_56, $usia_lebih_56, $penduduk_total, $penduduk_laki_laki, $penduduk_perempuan, $jumlah_kepala_keluarga)"
        );

        if ($insert) {
            header('Location: /kelurahan/datawilayah.php?action=add&status=success');
        } else {
            header('Location: /kelurahan/datawilayah.php?action=add&status=failed');
        }
    } else {
        $update = $koneksi->query(
            "UPDATE kelurahan_periode SET usia_kurang_15 = '$usia_kurang_15', usia_15_56 = '$usia_15_56', usia_lebih_56 = '$usia_lebih_56', penduduk_total = '$penduduk_total', penduduk_laki_laki = '$penduduk_laki_laki', penduduk_perempuan = '$penduduk_perempuan', jumlah_kepala_keluarga = '$jumlah_kepala_keluarga' WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
        );

        if ($update) {
            header('Location: /kelurahan/datawilayah.php?action=edit&status=success');
        } else {
            header('Location: /kelurahan/datawilayah.php?action=edit&status=failed');
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

$kelurahan_periode = $koneksi->query(
    "SELECT * FROM kelurahan_periode WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
);

$kelurahan_periode = $kelurahan_periode->fetch_assoc();
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
                    <h1>Data Wilayah</h1>
                    <h3><?= $kelurahan['kelurahan'] ?> Periode <?= $periode['periode'] ?></h3>
                </div>
                <!-- End Page Title -->
                <!-- Home Page -->
                <div class="row">
                    <div class="card-body col-lg-12 col-lg-offset-3">
                        <br>
                        <?php
                        $user_id = $_SESSION['id_user'];

                        $result = $koneksi->query(
                            "SELECT * FROM users RIGHT JOIN kelurahan ON users.id_kelurahan = kelurahan.id WHERE users.id = $user_id;"
                        );

                        $kelurahan = $result->fetch_assoc();
                        ?>
                        <form action="#" method="post">
                            <div class="row">
                                <div class="col">
                                    <h4 style="font-weight: bold;">Jumlah Komposisi Umur</h4>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#datawilayahadd">
                                        Simpan Data
                                    </button>
                                </div>
                            </div>
                            <hr style="border: 1px solid black;">
                            <div class="form-group p-2 m-1">
                                <label for="usia_kurang_15">Kurang dari 15 Tahun</label>
                                <div class="input-group">
                                    <input type="number" min="0" name="usia_kurang_15" id="usia_kurang_15" class="form-control col-md-4" value="<?= $kelurahan_periode ? $kelurahan_periode['usia_kurang_15'] : '' ?>" required></input>
                                    <span class="input-group-text">orang</span>
                                </div>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="usia_15_56">Lebih dari 15 tahun sampai dengan 56 tahun</label>
                                <div class="input-group">
                                    <input type="number" min="0" name="usia_15_56" id="usia_15_56" class="form-control col-md-4" value="<?= $kelurahan_periode ? $kelurahan_periode['usia_15_56'] : '' ?>" required></input>
                                    <span class="input-group-text">orang</span>
                                </div>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="usia_lebih_56">Lebih dari 56 tahun</label>
                                <div class="input-group">
                                    <input type="number" min="0" name="usia_lebih_56" id="usia_lebih_56" class="form-control col-md-4" value="<?= $kelurahan_periode ? $kelurahan_periode['usia_lebih_56'] : '' ?>" required></input>
                                    <span class="input-group-text">orang</span>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col">
                                    <h4 style="font-weight: bold;">Jumlah Penduduk Menurut Gender</h4>
                                </div>
                            </div>
                            <hr style="border: 1px solid black;">
                            <div class="form-group p-2 m-1">
                                <label for="penduduk_total">Jumlah penduduk total </label>
                                <div class="input-group">
                                    <input type="number" min="0" name="penduduk_total" id="penduduk_total" class="form-control col-md-4" value="<?= $kelurahan_periode ? $kelurahan_periode['penduduk_total'] : '' ?>" required></input>
                                    <span class="input-group-text">orang</span>
                                </div>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="penduduk_laki_laki">Jumlah penduduk laki-laki</label>
                                <div class="input-group">
                                    <input type="number" min="0" name="penduduk_laki_laki" id="penduduk_laki_laki" class="form-control col-md-4" value="<?= $kelurahan_periode ? $kelurahan_periode['penduduk_laki_laki'] : '' ?>" required></input>
                                    <span class="input-group-text">orang</span>
                                </div>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="penduduk_perempuan">Jumlah penduduk perempuan</label>
                                <div class="input-group">
                                    <input type="number" min="0" name="penduduk_perempuan" id="penduduk_perempuan" class="form-control col-md-4" value="<?= $kelurahan_periode ? $kelurahan_periode['penduduk_perempuan'] : '' ?>" required></input>
                                    <span class="input-group-text">orang</span>
                                </div>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="jumlah_kepala_keluarga">Jumlah kepala keluarga</label>
                                <div class="input-group">
                                    <input type="number" min="0" name="jumlah_kepala_keluarga" id="jumlah_kepala_keluarga" class="form-control col-md-4" value="<?= $kelurahan_periode ? $kelurahan_periode['jumlah_kepala_keluarga'] : '' ?>" required></input>
                                    <span class="input-group-text">orang</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>