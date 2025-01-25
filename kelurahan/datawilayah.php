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
                <?php if ((isset($_GET['action'])) && ($_GET['status'] == 'success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> Data wilayah kelurahan berhasil <?= ($_GET['action'] == 'add') ? 'disimpan' : 'diubah' ?>!
                    </div>
                <?php endif; ?>
                <?php if ((isset($_GET['add'])) && ($_GET['add'] == 'failed')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Failed!</strong> Data wilayah kelurahan gagal <?= ($_GET['action'] == 'add') ? 'disimpan' : 'diubah' ?>!
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="card-body col-lg-12 col-lg-offset-3">
                        <br>
                        <div class="m-3">
                            <div class="row">
                                <div class="col">
                                    <h4 style="font-weight: bold;">Identitas Desa/Kelurahan</h4>
                                </div>
                                <div class="col-auto">
                                    <a href="/kelurahan/edit_datawilayah.php">
                                        <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#datawilayahedit">
                                            <i class="fas fa-solid fa-edit"></i> Edit Identitas Desa/Kelurahan
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <hr style="border: 1px solid black;">
                            <table class="" style="width: 100%;">
                                <?php
                                $user_id = $_SESSION['id_user'];

                                $result = $koneksi->query(
                                    "SELECT * FROM users RIGHT JOIN kelurahan ON users.id_kelurahan = kelurahan.id WHERE users.id = $user_id;"
                                );

                                $kelurahan = $result->fetch_assoc();

                                // var_dump($kelurahan['kelurahan']);
                                // die();
                                ?>
                                <tr>
                                    <td style="width: 50%;">
                                        <strong>Kode Kelurahan</strong>
                                    </td>
                                    <td style="width: 50%;">
                                        <strong>: <?= (isset($kelurahan['id'])) ? $kelurahan['id'] : '-'; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%;">
                                        <strong>Nama Kelurahan</strong>
                                    </td>
                                    <td style="width: 50%;">
                                        <strong>: <?= (isset($kelurahan['kelurahan'])) ? $kelurahan['kelurahan'] : '-'; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Tipologi</strong>
                                    </td>
                                    <td>
                                        <strong>: <?= (isset($kelurahan['tipologi'])) ? $kelurahan['tipologi'] : '-'; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Orbitas Wilayah ke Kabupaten/Kota</strong>
                                    </td>
                                    <td>
                                        <strong>: <?= (isset($kelurahan['orbitas'])) ? $kelurahan['orbitas'] : '-'; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Kondisi Wilayah</strong>
                                    </td>
                                    <td>
                                        <strong>: <?= (isset($kelurahan['kondisi_wilayah_ibukota'])) ? $kelurahan['kondisi_wilayah_ibukota'] : '-'; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Kondisi Wilayah Bencana</strong>
                                    </td>
                                    <td>
                                        <strong>: <?= (isset($kelurahan['kondisi_wilayah_bencana'])) ? $kelurahan['kondisi_wilayah_bencana'] : '-'; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Batas Desa</strong>
                                    </td>
                                    <td>
                                        <strong>: <?= (isset($kelurahan['batas_desa'])) ? $kelurahan['batas_desa'] : '-'; ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Batas Orbitas</strong>
                                    </td>
                                    <td>
                                        <strong>: <?= (isset($kelurahan['batas_orbitas'])) ? $kelurahan['batas_orbitas'] : '-'; ?></strong>
                                    </td>
                                </tr>
                            </table>
                            <?php if (isset($kelurahan['id'])) : ?>
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
                                                $id_kelurahan = $kelurahan['id'];
                                                ?>
                                                <?php foreach ($periods as $periode): ?>
                                                    <?php
                                                    $id_periode = $periode['id'];
                                                    $kelurahan_periode = $koneksi->query(
                                                        "SELECT * FROM kelurahan_periode WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
                                                    );

                                                    if ($kelurahan_periode->num_rows == 0) {
                                                        $kelurahan_periode = [$id_kelurahan, $id_periode, "-", "-", "-", "-", "-", "-", "-"];
                                                    } else {
                                                        $kelurahan_periode = $kelurahan_periode->fetch_assoc();
                                                    }

                                                    $kelurahan_periode = implode(", ", $kelurahan_periode);

                                                    ?>
                                                    <option value="<?= $kelurahan_periode ?>">Periode <?= $periode['periode'] ?> </option>
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

                                    $kelurahan_periode_first = $koneksi->query(
                                        "SELECT * FROM kelurahan_periode WHERE id_kelurahan = '$id_kelurahan' AND id_periode = $id_periode"
                                    );

                                    $kelurahan_periode_first = $kelurahan_periode_first->fetch_assoc();

                                    ?>
                                    <div class="col-auto">
                                        <a href="#" id="buttonEdit" onclick="periodeADMStat('<?= $id_kelurahan ?>','<?= $id_periode ?>')">
                                            <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#datawilayahedit">
                                                <i class="fas fa-solid fa-edit"></i> Edit
                                            </button>
                                        </a>
                                    </div>
                                </div>
                                <h4 style="font-weight: bold;">Jumlah Komposisi Umur</h4>
                                <hr style="border: 1px solid black;">
                                <table class="" style="width: 100%;">
                                    <tr>
                                        <td style="width: 50%;">
                                            <strong>Kurang dari 15 Tahun</strong>
                                        </td>
                                        <td style="width: 50%;">
                                            <strong>: </strong><span id="usia_kurang_15"><?= $kelurahan_periode_first ? $kelurahan_periode_first['usia_kurang_15'] : '-' ?></span> orang
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Lebih 15 Tahun Sampai 56 Tahun</strong>
                                        </td>
                                        <td>
                                            <strong>: </strong><span id="usia_15_56"><?= $kelurahan_periode_first ? $kelurahan_periode_first['usia_15_56'] : '-' ?></span> orang
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Lebih dari 56 Tahun</strong>
                                        </td>
                                        <td>
                                            <strong>: </strong><span id="usia_lebih_56"><?= $kelurahan_periode_first ? $kelurahan_periode_first['usia_lebih_56'] : '-' ?></span> orang
                                        </td>
                                    </tr>
                                </table>
                                <h4 class="mt-5" style="font-weight: bold;">Jumlah Penduduk Menurut Gender</h4>
                                <hr style="border: 1px solid black;">
                                <table class="" style="width: 100%;">
                                    <tr>
                                        <td style="width: 50%;">
                                            <strong>Jumlah Penduduk Total</strong>
                                        </td>
                                        <td style="width: 50%;">
                                            <strong>: </strong><span id="penduduk_total"><?= $kelurahan_periode_first ? $kelurahan_periode_first['penduduk_total'] : '-' ?></span> orang
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Jumlah Penduduk Laki-Laki</strong>
                                        </td>
                                        <td>
                                            <strong>: </strong><span id="penduduk_laki_laki"><?= $kelurahan_periode_first ? $kelurahan_periode_first['penduduk_laki_laki'] : '-' ?></span> orang
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Jumlah Penduduk Perempuan</strong>
                                        </td>
                                        <td>
                                            <strong>: </strong><span id="penduduk_perempuan"><?= $kelurahan_periode_first ? $kelurahan_periode_first['penduduk_perempuan'] : '-' ?></span> orang
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Jumlah Kepala Keluarga</strong>
                                        </td>
                                        <td>
                                            <strong>: </strong><span id="jumlah_kepala_keluarga"><?= $kelurahan_periode_first ? $kelurahan_periode_first['jumlah_kepala_keluarga'] : '-' ?></span> orang
                                        </td>
                                    </tr>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script>
        function changeKelurahanPeriode(kelurahan_periode = null) {
            // console.log(kelurahan_periode);
            let resultArray = kelurahan_periode.split(", ");

            // Output the resulting array
            // console.log(resultArray[6]);
            document.getElementById("usia_kurang_15").textContent = resultArray[2];
            document.getElementById("usia_15_56").textContent = resultArray[3];
            document.getElementById("usia_lebih_56").textContent = resultArray[4];
            document.getElementById("penduduk_total").textContent = resultArray[5];
            document.getElementById("penduduk_laki_laki").textContent = resultArray[6];
            document.getElementById("penduduk_perempuan").textContent = resultArray[7];
            document.getElementById("jumlah_kepala_keluarga").textContent = resultArray[8];

            // document.getElementById("buttonEdit").setAttribute("href", "/kelurahan/edit_kelurahan_periode.php?id_kelurahan=" + resultArray[0] + "&id_periode=" + resultArray[1]);
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
                            window.location.href = '/kelurahan/edit_kelurahan_periode.php?id_kelurahan=' + kelurahanId + '&id_periode=' + periodeId;
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