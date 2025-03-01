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

$user_id = $_SESSION['id_user'];

$users = $koneksi->query(
    "SELECT * FROM users WHERE id = $user_id;"
)->fetch_assoc();

$id_kelurahan = $users['id_kelurahan'];

$administrasi = $koneksi->query(
    "SELECT * FROM administrasi WHERE id_kelurahan = '$id_kelurahan';"
)

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
                <?php if ((isset($_GET['action'])) && ($_GET['status'] == 'success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Data administrasi kelurahan berhasil <?= ($_GET['action'] == 'add') ? 'diperbarui' : 'diubah' ?>!
                    </div>
                <?php endif; ?>
                <?php if ((isset($_GET['add'])) && ($_GET['add'] == 'failed')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Failed!</strong> Data administrasi kelurahan gagal <?= ($_GET['action'] == 'add') ? 'diperbarui' : 'diubah' ?>!
                    </div>
                <?php endif; ?>
                <!-- End Page Title -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="orbitas" id="orbitas" class="form-control" onchange="changeAdministrasiPeriode(this.value)" required>
                                        <option value="" selected disabled>--- Pilih Periode ---</option>
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
                                            <option value="<?= $id_kelurahan . '|' . $periode['id'] ?>">Periode <?= $periode['periode'] ?> </option>
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

                            ?>
                            <div class="col">
                                <a href="#" id="buttonEdit" onclick="periodeADMStat('<?= $id_kelurahan ?>','<?= $id_periode ?>')">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#datawilayahedit">
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
                        $i = 1;
                        ?>

                        <?php foreach ($bidangs as $bidang): ?>
                            <h4 class="mt-3" style="font-weight: bold;">Bidang <?= $bidang['nama_bidang'] ?></h4>
                            <hr style="border: 1px solid black;">
                            <?php foreach ($indikators as $indikator): ?>
                                <?php if ($indikator['id_bidang'] == $bidang['id']): ?>
                                    <div class="border border-secondary rounded m-3 p-2">
                                        <h5 style="font-weight: bold;">Indikator: <?= $indikator['nama_indikator'] ?></h5>
                                        <?php foreach ($sub_indikators as $sub_indikator): ?>
                                            <?php if ($sub_indikator['id_indikator'] == $indikator['id']): ?>
                                                <span class="ms-3"><strong><?= $sub_indikator['nama_sub_indikator'] ?></strong></span>
                                                <!-- <p class="ms-3">ADA </p> -->
                                                <table style="width: 100%;" class="ms-3 mb-3">
                                                    <tr>
                                                        <td style="width: 50%;"><span>Nama Nilai</span></td>
                                                        <td style="width: 25%;">Poin : <span>Nilai Poin</span></td>
                                                        <td style="width: 25%;">Bukti :
                                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#carouselModal<?= $indikator['id'] ?>">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <div class="modal fade" id="carouselModal<?= $indikator['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="carouselModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="carouselModalLabel">Bukti</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <!-- Carousel -->
                                                                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                                                                <div class="carousel-inner">
                                                                                    <div class="carousel-item active">
                                                                                        <img src="/gambar/bukti/gambar-1.jpg" class="d-block w-100" alt="Image 1">
                                                                                    </div>
                                                                                    <div class="carousel-item active">
                                                                                        <iframe src="/gambar/bukti/pdftes.pdf" width="100%" height="500px"></iframe>
                                                                                    </div>
                                                                                    <div class="carousel-item active">
                                                                                        <img src="/gambar/bukti/gambar-3.jpg" class="d-block w-100" alt="Image 3">
                                                                                    </div>
                                                                                </div>
                                                                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                                                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                                    <span class="sr-only">Previous</span>
                                                                                </a>
                                                                                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                                                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                                    <span class="sr-only">Next</span>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <div class="row ms-3 mb-3">
                                                    <div class="col">
                                                        <a href="/kelurahan/tambah_adm.php?id_kelurahan=<?= $id_kelurahan ?>&id_periode=3&id_sub_indikator=id_sub_indikator">
                                                            <button type="button" class="btn btn-info btn-sm">
                                                                <i class="fas fa-plus"> <span>Tambah Data</span></i>
                                                            </button>
                                                        </a>
                                                    </div>
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
        function changeAdministrasiPeriode(administrasi = null) {
            // console.log(kelurahan_periode);
            let resultArray = administrasi.split("| ");

            <?php

            for ($i = 1; $i <= 179; $i++) {
                echo 'document.getElementById("nilai' . $i . '").textContent = resultArray[' . ($i * 2) . '];';
                if ($i != 171) {
                    echo 'document.getElementById("poin' . $i . '").textContent = resultArray[' . (($i * 2) + 1) . '];';
                }
            }

            ?>

            // Output the resulting array
            // console.log(resultArray[6]);
            // document.getElementById("usia_kurang_15").textContent = resultArray[2];
            // document.getElementById("usia_15_56").textContent = resultArray[3];
            // document.getElementById("usia_lebih_56").textContent = resultArray[4];
            // document.getElementById("penduduk_total").textContent = resultArray[5];
            // document.getElementById("penduduk_laki_laki").textContent = resultArray[6];
            // document.getElementById("penduduk_perempuan").textContent = resultArray[7];
            // document.getElementById("jumlah_kepala_keluarga").textContent = resultArray[8];

            // document.getElementById("buttonEdit").setAttribute("href", "/kelurahan/edit_adm_kelurahan.php?id_kelurahan=" + resultArray[0] + "&id_periode=" + resultArray[1]);
            document.getElementById("buttonEdit").setAttribute("onclick", "periodeADMStat('" + resultArray[0] + "','" + resultArray[1] + "')");
        }
    </script>
    <script>
        $(document).ready(function() {
            // Fungsi untuk mengganti data tabel sesuai periode
            window.periodeADMStat = function(kelurahanId, periodeId) {
                $.ajax({
                    url: 'periodeADMStat.php', // Endpoint PHP untuk mendapatkan data berdasarkan periode
                    type: 'POST',
                    data: {
                        periode: periodeId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.data.tutup_periode_administrasi === "1") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Periode administrasi telah ditutup!',
                                text: 'Anda tidak bisa mengubah data administrasi pada periode ini.',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false, // Mencegah menutup dengan klik di luar modal
                            });
                        } else {
                            window.location.href = '/kelurahan/edit_adm_kelurahan.php?id_kelurahan=' + kelurahanId + '&id_periode=' + periodeId;
                        }
                        // // Bersihkan tabel
                        // table.clear();

                        // // Tambahkan data baru ke tabel
                        // response.data.forEach(row => {
                        //     table.row.add([
                        //         row.ranking,
                        //         row.kelurahan,
                        //         row.hasil,
                        //     ]);
                        // });

                        // // Render ulang tabel
                        // table.draw();
                    },
                    error: function() {
                        alert('Gagal memuat data. Silakan coba lagi.');
                    }
                });
            };
        });
    </script>
</body>

</html>