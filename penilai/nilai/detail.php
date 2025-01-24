<?php
session_start();
include '../../koneksi.php';
$id_periode = $_GET['id_periode'];
$id_alternatif = $_GET['id_alternatif'];
$back = '/penilai/nilai/nilai.php?id_periode=' . $id_periode;

$alternative = $koneksi->query(
    'SELECT alternatif.*, kelurahan.kelurahan, periode.periode FROM alternatif JOIN kelurahan ON kelurahan.id = alternatif.id_kelurahan JOIN periode ON periode.id = alternatif.id_periode WHERE alternatif.id = ' . $id_alternatif . ';'
)->fetch_assoc();
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

if ((isset($_GET['action'])) && ($_GET['action'] == 'editPresentasi')) {
    $sub_presentasi_cek = $koneksi->query(
        "SELECT * FROM sub_presentasi WHERE id_alternatif = $id_alternatif"
    );

    if ($sub_presentasi_cek->num_rows == 1) {
        $update = $koneksi->query(
            "UPDATE sub_presentasi SET isi_materi = " . $_POST['isi_materi'] . ", organisir_waktu = " . $_POST['organisir_waktu'] . ", tanya_jawab = " . $_POST['tanya_jawab'] . " WHERE id_alternatif = $id_alternatif"
        );
    } else {
        $insert = $koneksi->query(
            "INSERT INTO sub_presentasi (id_alternatif, isi_materi, organisir_waktu, tanya_jawab) VALUES ($id_alternatif, " . $_POST['isi_materi'] . ", " . $_POST['organisir_waktu'] . ", " . $_POST['tanya_jawab'] . ")"
        );
    }
    if ((isset($update) && $update) || (isset($insert) && $insert)) {
        $success = 'Nilai presentasi berhasil diubah!';
    } else {
        $failed = 'Nilai presentasi gagal diubah!';
    }
}
if ((isset($_GET['action'])) && ($_GET['action'] == 'editWawancara')) {
    $sub_wawancara_cek = $koneksi->query(
        "SELECT * FROM sub_wawancara WHERE id_alternatif = $id_alternatif"
    );

    if ($sub_wawancara_cek->num_rows == 1) {
        $update = $koneksi->query(
            "UPDATE sub_wawancara SET kerjasama_tim = " . $_POST['kerjasama_tim'] . ", kemampuan_lurah = " . $_POST['kemampuan_lurah'] . ", kemampuan_problem_solving = " . $_POST['kemampuan_problem_solving'] . " WHERE id_alternatif = $id_alternatif"
        );
    } else {
        $insert = $koneksi->query(
            "INSERT INTO sub_wawancara (id_alternatif, kerjasama_tim, kemampuan_lurah, kemampuan_problem_solving) VALUES ($id_alternatif, " . $_POST['kerjasama_tim'] . ", " . $_POST['kemampuan_lurah'] . ", " . $_POST['kemampuan_problem_solving'] . ")"
        );
    }
    if ((isset($update) && $update) || (isset($insert) && $insert)) {
        $success = 'Nilai wawancara berhasil diubah!';
    } else {
        $failed = 'Nilai wawancara gagal diubah!';
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
        <?php include '../../layouts/header.php'; ?>
        <?php include '../../layouts/penilai_sidebar_periode.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <br>
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Detail Penilaian <?= $alternative['kelurahan'] ?> Periode <?= $alternative['periode'] ?></h1>
                </div>
                <!-- End Page Title -->
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
                        <div class="row">
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Presentasi</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end mb-3">
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editPresentasi">
                                                <i class="fas fa-solid fa-edit"></i> Edit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="myTable1" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr align="center">
                                                <th>Isi Materi</th>
                                                <th>Organisir Waktu</th>
                                                <th>Tanya Jawab</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr align="center">
                                                <?php
                                                $sub_presentasi = $koneksi->query(
                                                    'SELECT * FROM sub_presentasi WHERE id_alternatif = ' . $id_alternatif . ';'
                                                )->fetch_assoc();
                                                ?>
                                                <td><?= $sub_presentasi ? $sub_presentasi['isi_materi'] : '-' ?></td>
                                                <td><?= $sub_presentasi ? $sub_presentasi['organisir_waktu'] : '-' ?></td>
                                                <td><?= $sub_presentasi ? $sub_presentasi['tanya_jawab'] : '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td align="right"><strong>AVG: <?= $sub_presentasi ? round(($sub_presentasi['isi_materi'] + $sub_presentasi['organisir_waktu'] + $sub_presentasi['tanya_jawab']) / 3, 3) : '-' ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Wawancara</h5>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end mb-3">
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editWawancara">
                                                <i class="fas fa-solid fa-edit"></i> Edit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="myTable1" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr align="center">
                                                <th>Kerjasama Tim</th>
                                                <th>Kemampuan Lurah</th>
                                                <th>Kemampuan Problem Solving</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sub_wawancara = $koneksi->query(
                                                'SELECT * FROM sub_wawancara WHERE id_alternatif = ' . $id_alternatif . ';'
                                            )->fetch_assoc();
                                            ?>
                                            <tr align="center">
                                                <td><?= $sub_wawancara ? $sub_wawancara['kerjasama_tim'] : '-' ?></td>
                                                <td><?= $sub_wawancara ? $sub_wawancara['kemampuan_lurah'] : '-' ?></td>
                                                <td><?= $sub_wawancara ? $sub_wawancara['kemampuan_problem_solving'] : '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td align="right"><strong>AVG: <?= $sub_wawancara ? round(($sub_wawancara['kerjasama_tim'] + $sub_wawancara['kemampuan_lurah'] + $sub_wawancara['kemampuan_problem_solving']) / 3, 3) : '-' ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
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
                                <h5>Verifikasi Lapangan: <?= $total_nilai['total_nilai'] ? $total_nilai['total_nilai'] : '-' ?></h5>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end">
                                    <a href="/penilai/nilai/verifikasi.php?id_periode=<?= $id_periode ?>&id_alternatif=<?= $id_alternatif ?>">
                                        <button type="button" class="btn btn-success btn-sm">
                                            <i class="fas fa-solid fa-edit"></i> Verifikasi
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <span>Status: <?= ($sub_ver_all['total_row'] == $sub_ver_not_null['total_row']) ? '<span class="badge rounded-pill bg-primary">Terverifikasi semua</span>' : '<span class="badge rounded-pill bg-danger">Belum terverifikasi semua</span>' ?></span>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Modal Edit Presentasi-->
            <div class="modal fade" id="editPresentasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Nilai Presentasi</h5>
                        </div>
                        <div class="modal-body">
                            <form class="mx-3" action="detail.php?id_periode=<?= $id_periode ?>&id_alternatif=<?= $id_alternatif ?>&action=editPresentasi" method="POST" onsubmit="">
                                <div class="form-group">
                                    <label for="periode">Isi Materi</label>
                                    <input type="number" class="form-control" id="isi_materi" name="isi_materi" min="1" max="100" value="<?= $sub_presentasi ? $sub_presentasi['isi_materi'] : '' ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="periode">Organisir Waktu</label>
                                    <input type="number" class="form-control" id="organisir_waktu" name="organisir_waktu" min="1" max="100" value="<?= $sub_presentasi ? $sub_presentasi['organisir_waktu'] : '' ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="periode">Tanya Jawab</label>
                                    <input type="number" class="form-control" id="tanya_jawab" name="tanya_jawab" min="1" max="100" value="<?= $sub_presentasi ? $sub_presentasi['tanya_jawab'] : '' ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <button type="submit" class="btn btn-primary" name="save">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Edit Wawancara-->
            <div class="modal fade" id="editWawancara" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Nilai Wawancara</h5>
                        </div>
                        <div class="modal-body">
                            <form class="mx-3" action="detail.php?id_periode=<?= $id_periode ?>&id_alternatif=<?= $id_alternatif ?>&action=editWawancara" method="POST" onsubmit="">
                                <div class="form-group">
                                    <label for="periode">Kerjasama Tim</label>
                                    <input type="number" class="form-control" id="kerjasama_tim" name="kerjasama_tim" min="1" max="100" value="<?= $sub_wawancara ? $sub_wawancara['kerjasama_tim'] : '' ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="periode">Kemampuan Lurah</label>
                                    <input type="number" class="form-control" id="kemampuan_lurah" name="kemampuan_lurah" min="1" max="100" value="<?= $sub_wawancara ? $sub_wawancara['kemampuan_lurah'] : '' ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="periode">Kemampuan Problem Solving</label>
                                    <input type="number" class="form-control" id="kemampuan_problem_solving" name="kemampuan_problem_solving" min="1" max="100" value="<?= $sub_wawancara ? $sub_wawancara['kemampuan_problem_solving'] : '' ?>" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <button type="submit" class="btn btn-primary" name="save">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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