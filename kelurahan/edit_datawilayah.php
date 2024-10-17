<?php
session_start();
include '../koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: login.php');
    exit();
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'kelurahan') {
    // Jika bukan admin, redirect ke halaman lain atau berikan pesan akses ditolak
    header('Location: unauthorized.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['id_user'];

    $tipologi = $_POST['tipologi'];
    $orbitas = $_POST['orbitas'];
    $kondisi_wilayah_ibukota = $_POST['kondisi_wilayah_ibukota'];
    $kondisi_wilayah_bencana = $_POST['kondisi_wilayah_bencana'];
    $batas_desa = $_POST['batas_desa'];
    $batas_orbitas = $_POST['batas_orbitas'];

    $result = $koneksi->query(
        "SELECT * FROM users WHERE id=$user_id"
    );

    $user = $result->fetch_assoc();

    if ($user['id_kelurahan']) {
        $id_kelurahan = $user['id_kelurahan'];
        $update = $koneksi->query(
            "UPDATE kelurahan SET tipologi = '$tipologi', orbitas = '$orbitas', kondisi_wilayah_ibukota = '$kondisi_wilayah_ibukota', kondisi_wilayah_bencana = '$kondisi_wilayah_bencana', batas_desa = '$batas_desa', batas_orbitas = '$batas_orbitas' WHERE id = '$id_kelurahan'"
        );

        if ($update) {
            header('Location: /kelurahan/datawilayah.php?action=edit&status=success');
        } else {
            header('Location: /kelurahan/datawilayah.php?action=edit&status=failed');
        }
    } else {

        $id = $_POST['id'];
        $nama_kelurahan = $_POST['kelurahan'];

        $checkKodeKelurahan = $koneksi->query(
            "SELECT * FROM kelurahan WHERE id='$id'"
        );

        if ($checkKodeKelurahan->num_rows == 1) {
            $messageKodeKelurahan = 'Kode kelurahan sudah ada!';
        }

        $checkNamaKelurahan = $koneksi->query(
            "SELECT * FROM kelurahan WHERE kelurahan='$nama_kelurahan'"
        );

        if ($checkNamaKelurahan->num_rows == 1) {
            $messageNamaKelurahan = 'Nama kelurahan sudah ada!';
        }

        if (($checkKodeKelurahan->num_rows == 0) && ($checkNamaKelurahan->num_rows == 0)) {

            $insert = $koneksi->query(
                "INSERT INTO kelurahan (id, kelurahan, tipologi, orbitas, kondisi_wilayah_ibukota, kondisi_wilayah_bencana, batas_desa, batas_orbitas) VALUES ('$id', '$nama_kelurahan', '$tipologi', '$orbitas', '$kondisi_wilayah_ibukota', '$kondisi_wilayah_bencana', '$batas_desa', '$batas_orbitas')"
            );

            $update = $koneksi->query(
                "UPDATE users SET id_kelurahan = '$id' WHERE id = $user_id"
            );

            if ($insert) {
                header('Location: /kelurahan/datawilayah.php?action=add&status=success');
            } else {
                header('Location: /kelurahan/datawilayah.php?action=add&status=failed');
            }
        }
    }
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
        <?php include '../layouts/kelurahan_sidebar.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Data Wilayah</h1>
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
                                    <h4 style="font-weight: bold;">Identitas Desa/Kelurahan</h4>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#datawilayahadd">
                                        Simpan Data
                                    </button>
                                </div>
                            </div>
                            <hr style="border: 1px solid black;">
                            <div class="form-group p-2 m-1">
                                <label for="kode_kelurahan">Kode Kelurahan</label>
                                <input name="id" id="kode_kelurahan" class="form-control" value="<?= isset($id) ? $id : '' ?><?= $kelurahan ? $kelurahan['id'] : ''; ?>" <?= $kelurahan ? 'disabled' : ''; ?> required></input>
                                <?php if (isset($messageKodeKelurahan)): ?>
                                    <div class="form-text text-danger">
                                        <?= $messageKodeKelurahan; ?>
                                    </div>
                                <?php endif; ?>
                                <?= $kelurahan ? '' : '<div class="form-text text-danger">Kode kelurahan tidak dapat diubah, mohon isi dengan benar!</div>'; ?>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="kelurahan">Nama Kelurahan</label>
                                <input name="kelurahan" id="kelurahan" class="form-control" value="<?= isset($nama_kelurahan) ? $nama_kelurahan : '' ?><?= $kelurahan ? $kelurahan['kelurahan'] : ''; ?>" <?= $kelurahan ? 'disabled' : ''; ?> required></input>
                                <?php if (isset($messageNamaKelurahan)): ?>
                                    <div class="form-text text-danger">
                                        <?= $messageNamaKelurahan; ?>
                                    </div>
                                <?php endif; ?>
                                <?= $kelurahan ? '' : '<div class="form-text text-danger">Nama kelurahan tidak dapat diubah, mohon isi dengan benar!</div>'; ?>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="tipologi">Tipologi</label>
                                <select name="tipologi" id="tipologi" class="form-control" required>
                                    <option value="" selected disabled>-Pilih-</option>
                                    <option value="Pantai" <?= (isset($tipologi) && ($tipologi == 'Pantai')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['tipologi'] == 'Pantai')) ? 'selected' : ''; ?>>Pantai</option>
                                    <option value="Dataran Rendah" <?= (isset($tipologi) && ($tipologi == 'Dataran Rendah')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['tipologi'] == 'Dataran Rendah')) ? 'selected' : ''; ?>>Dataran Rendah</option>
                                    <option value="Pegunungan" <?= (isset($tipologi) && ($tipologi == 'Pegunungan')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['tipologi'] == 'Pegunungan')) ? 'selected' : ''; ?>>Pegunungan</option>
                                    <option value="Pertanian" <?= (isset($tipologi) && ($tipologi == 'Pertanian')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['tipologi'] == 'Pertanian')) ? 'selected' : ''; ?>>Pertanian</option>
                                </select>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="orbitas">Orbitas Wilayah ke Kabupaten/Kota</label>
                                <select name="orbitas" id="orbitas" class="form-control" required>
                                    <option value="" selected disabled>-Pilih-</option>
                                    <option value="Lebih dari 6 jam" <?= (isset($orbitas) && ($orbitas == 'Lebih dari 6 jam')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['orbitas'] == 'Lebih dari 6 jam')) ? 'selected' : ''; ?>>Lebih dari 6 jam</option>
                                    <option value="5 sampai 6 jam" <?= (isset($orbitas) && ($orbitas == '5 sampai 6 jam')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['orbitas'] == '5 sampai 6 jam')) ? 'selected' : ''; ?>>5 sampai 6 jam</option>
                                    <option value="3 sampai 4 jam" <?= (isset($orbitas) && ($orbitas == '3 sampai 4 jam')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['orbitas'] == '3 sampai 4 jam')) ? 'selected' : ''; ?>>3 sampai 4 jam</option>
                                    <option value="1 sampai 2 jam" <?= (isset($orbitas) && ($orbitas == '1 sampai 2 jam')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['orbitas'] == '1 sampai 2 jam')) ? 'selected' : ''; ?>>1 sampai 2 jam</option>
                                    <option value="Kurang dari 1 jam" <?= (isset($orbitas) && ($orbitas == 'Kurang dari 1 jam')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['orbitas'] == 'Kurang dari 1 jam')) ? 'selected' : ''; ?>>Kurang dari 1 jam</option>
                                </select>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="kondisi_wilayah">Kondisi Wilayah</label>
                                <select name="kondisi_wilayah_ibukota" id="kondisi_wilayah" class="form-control" required>
                                    <option value="" selected disabled>-Pilih-</option>
                                    <option value="Ada di ibukota" <?= (isset($kondisi_wilayah_ibukota) && ($kondisi_wilayah_ibukota == 'Ada di ibukota')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['kondisi_wilayah_ibukota'] == 'Ada di ibukota')) ? 'selected' : ''; ?>>Ada di ibukota</option>
                                    <option value="Di luar ibukota" <?= (isset($kondisi_wilayah_ibukota) && ($kondisi_wilayah_ibukota == 'Di luar ibukota')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['kondisi_wilayah_ibukota'] == 'Di luar ibukota')) ? 'selected' : ''; ?>>Di luar ibukota</option>
                                </select>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="kondisi_wilayah_bencana">Kondisi Wilayah Bencana</label>
                                <select name="kondisi_wilayah_bencana" id="kondisi_wilayah_bencana" class="form-control" required>
                                    <option value="" selected disabled>-Pilih-</option>
                                    <option value="Rawan" <?= (isset($kondisi_wilayah_bencana) && ($kondisi_wilayah_bencana == 'Rawan')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['kondisi_wilayah_bencana'] == 'Rawan')) ? 'selected' : ''; ?>>Rawan</option>
                                    <option value="Tidak rawan" <?= (isset($kondisi_wilayah_bencana) && ($kondisi_wilayah_bencana == 'Tidak rawan')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['kondisi_wilayah_bencana'] == 'Tidak rawan')) ? 'selected' : ''; ?>>Tidak rawan</option>
                                </select>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="batas_desa">Batas Desa</label>
                                <select name="batas_desa" id="batas_desa" class="form-control" required>
                                    <option value="" selected disabled>-Pilih-</option>
                                    <option value="Ada" <?= (isset($batas_desa) && ($batas_desa == 'Ada')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['batas_desa'] == 'Ada')) ? 'selected' : ''; ?>>Ada</option>
                                    <option value="Tidak ada" <?= (isset($batas_desa) && ($batas_desa == 'Tidak ada')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['batas_desa'] == 'Tidak ada')) ? 'selected' : ''; ?>>Tidak ada</option>
                                </select>
                            </div>
                            <div class="form-group p-2 m-1">
                                <label for="batas_orbitas">Batas Orbitas</label>
                                <select name="batas_orbitas" id="batas_orbitas" class="form-control" required>
                                    <option value="" selected disabled>-Pilih-</option>
                                    <option value="Ada" <?= (isset($batas_orbitas) && ($batas_orbitas == 'Ada')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['batas_orbitas'] == 'Ada')) ? 'selected' : ''; ?>>Ada</option>
                                    <option value="Tidak ada" <?= (isset($batas_orbitas) && ($batas_orbitas == 'Tidak ada')) ? 'selected' : ''; ?> <?= (($kelurahan) && ($kelurahan['batas_orbitas'] == 'Tidak ada')) ? 'selected' : ''; ?>>Tidak ada</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>

</html>