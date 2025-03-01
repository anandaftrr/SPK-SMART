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
    <style>
        /* Gaya untuk tombol Next dan Previous */
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: black;
            /* Ubah warna ikon */
            border-radius: 50%;
            /* Membuat tombol bulat */
            padding: 15px;
            /* Ukuran tombol */
            width: 50px;
            /* Lebar tombol */
            height: 50px;
            /* Tinggi tombol */
        }

        .carousel-control-prev,
        .carousel-control-next {
            z-index: 10;
            /* Pastikan tombol berada di atas gambar */
            opacity: 0.3;
            /* Pastikan tombol terlihat */
        }

        /* Gaya saat tombol ditekan */
        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background-color: rgba(0, 0, 0, 0.5);
            /* Ubah latar belakang saat hover */
            transition: background-color 0.3s ease;
        }
    </style>

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
                                    <select name="orbitas" id="orbitas" class="form-control" onchange="getADMPeriode(this.value)" required>
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
                        </div>
                        <div id="data-adm">

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
    </script>

    <script>
        function getADMPeriode(idkelperiod = null) {
            let resultArray = idkelperiod.split("|");
            let id_kelurahan = resultArray[0];
            let id_periode = resultArray[1];
            $.ajax({
                url: 'getADM.php', // Endpoint PHP untuk mendapatkan data berdasarkan periode
                type: 'POST',
                data: {
                    id_kelurahan: id_kelurahan,
                    id_periode: id_periode
                },
                dataType: 'json',
                success: function(response) {
                    $('#data-adm').empty();
                    let firstData = true;
                    let dataADM = '';
                    let previousNamaBidang = '';
                    let previousNamaIndikator = '';
                    response.data.forEach((item, index) => {
                        if (item.nama_indikator !== previousNamaIndikator & firstData === false) {
                            dataADM += '</div>';
                        }
                        if (item.nama_bidang !== previousNamaBidang) {
                            dataADM += '<h4 class="mt-3" style="font-weight: bold;">Bidang ' + item.nama_bidang + '</h4>' +
                                '<hr style="border: 1px solid black;">';
                        }
                        if (item.nama_indikator !== previousNamaIndikator) {
                            dataADM += '<div class="border border-secondary rounded m-3 p-2">' +
                                '<h5 style="font-weight: bold;">Indikator: ' + item.nama_indikator + '</h5>';
                        }
                        if (item.id_indikator !== 60) {
                            dataADM += '<span class="ms-3"><strong>' + item.nama_sub_indikator + '</strong></span>';
                        }
                        if (item.id_nilai_sub_indikator === null) {
                            if (item.tutup_periode_administrasi === '0') {
                                dataADM += '<div class="row ms-3 mb-3">' +
                                    '<div class="col">' +
                                    '<a href="/kelurahan/tambah_adm.php?id_kelurahan=' + item.id_kelurahan + '&id_periode=' + item.id_periode + '&id_sub_indikator=' + item.id + '">' +
                                    '<button type="button" class="btn btn-info btn-sm">' +
                                    '<i class="fas fa-plus"> <span>Tambah Data</span></i>' +
                                    '</button>' +
                                    '</a>' +
                                    '</div>' +
                                    '</div>';
                            } else {
                                dataADM += '<table class="ms-3" style="width:100%;">' +
                                    '<tr>' +
                                    '<td style="width: 50%;"><span>-</span></td>' +
                                    '<td style="width: 25%;">Poin : <span>-</span></td>' +
                                    '<td style="width: 25%;">Bukti : -</td>' +
                                    '</tr>' +
                                    '</table>';
                            }
                        } else {
                            let poinShow = (item.id_indikator !== 60) ? '<td style="width: 25%;">Poin : <span>' + item.point + '</span></td>' : '<td style="width: 25%;"></td>';
                            dataADM += '<table style="width: 100%;" class="ms-3 mb-3">' +
                                '<tr>' +
                                '<td style="width: 50%;"><span>' + item.nama_nilai_sub_indikator + '</span></td>' +
                                poinShow;

                            if (item.file_bukti !== null) {
                                dataADM += '<td style="width: 25%;">Bukti : ' +
                                    '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#carouselModal' + item.id + '">' +
                                    '<i class="fas fa-eye"></i>' +
                                    '</button>' +
                                    '<div class="modal fade" id="carouselModal' + item.id + '" tabindex="-1" role="dialog" aria-labelledby="carouselModalLabel" aria-hidden="true">' +
                                    '<div class="modal-dialog modal-lg" role="document">' +
                                    '<div class="modal-content">' +
                                    '<div class="modal-header">' +
                                    '<h5 class="modal-title" id="carouselModalLabel">Bukti' + item.id + '</h5>' +
                                    '</div>' +
                                    '<div class="modal-body">' +
                                    '<!-- Carousel -->' +
                                    '<div id="carouselExampleControls' + item.id + '" class="carousel slide" data-ride="carousel">' +
                                    '<div class="carousel-inner">';
                                item.file_bukti.forEach((fileBukti, index2) => {
                                    console.log(fileBukti.file_bukti);
                                    let carouselItemClass = (index2 === 0) ? 'carousel-item active' : 'carousel-item';
                                    if (fileBukti.file_bukti.split('.').pop().toLowerCase() === 'pdf') {
                                        dataADM += '<div class="' + carouselItemClass + '">' +
                                            '<iframe src="/uploads/file_administrasi/' + fileBukti.file_bukti + '" width="100%" height="500px"></iframe>' +
                                            '</div>';
                                    } else {
                                        dataADM += '<div class="' + carouselItemClass + '">' +
                                            '<img src="/uploads/file_administrasi/' + fileBukti.file_bukti + '" class="d-block w-100" alt="Image 1">' +
                                            '</div>';

                                    }
                                });
                                // dataADM += '<div class="carousel-item active">' +
                                //     '<img src="/gambar/bukti/gambar-1.jpg" class="d-block w-100" alt="Image 1">' +
                                //     '</div>' +
                                //     '<div class="carousel-item">' +
                                //     '<iframe src="/gambar/bukti/pdftes.pdf" width="100%" height="500px"></iframe>' +
                                //     '</div>' +
                                //     '<div class="carousel-item">' +
                                //     '<img src="/gambar/bukti/gambar-3.jpg" class="d-block w-100" alt="Image 3">' +
                                //     '</div>';

                                dataADM += '</div>' +
                                    '<a class="carousel-control-prev" href="#carouselExampleControls' + item.id + '" role="button" data-bs-slide="prev">' +
                                    '<span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
                                    '<span class="sr-only">Previous</span>' +
                                    '</a>' +
                                    '<a class="carousel-control-next" href="#carouselExampleControls' + item.id + '" role="button" data-bs-slide="next">' +
                                    '<span class="carousel-control-next-icon" aria-hidden="true"></span>' +
                                    '<span class="sr-only">Next</span>' +
                                    '</a>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</td>';
                            } else {
                                dataADM += '<td style="width: 25%;"></td>';
                            }
                            dataADM += '</tr>' +
                                '</table>';
                        }
                        previousNamaBidang = item.nama_bidang;
                        previousNamaIndikator = item.nama_indikator;
                        firstData = false;
                    });
                    $('#data-adm').append(dataADM);
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error: ", status, error);
                    alert('Gagal memuat data. Silakan coba lagi.');
                }
            });
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