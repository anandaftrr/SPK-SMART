<?php
session_start();
include 'koneksi.php';

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
        <?php include 'header.php'; ?>
        <?php include 'kelurahan_sidebar.php'; ?>
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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#datawilayahadd" style="position: absolute; top: 10px; right: 35px;">
                Simpan Data
                </button>
                <br>
                    <form action="#" method="post">
                        <div class="form-group p-2 m-1">
                            <h4 style="font-weight: bold;">Identitas Desa/Kelurahan</h4>
                            <hr style="border: 1px solid black;">
                            <label for="kelurahan">Kelurahan</label>
                            <input name="kelurahan" id="kelurahan" class="form-control" required></input>
                        </div>
                        <div class="form-group p-2 m-1">
                            <label for="kode_kelurahan">Kode Kelurahan</label>
                            <input name="kode_kelurahan" id="kode_kelurahan" class="form-control" required></input>
                        </div>
                        <div class="form-group p-2 m-1">
                            <label for="tipologi">Tipologi</label>
                            <select name="tipologi" id="tipologi" class="form-control" required>
                                <option value="">-Pilih-</option>
                            </select>
                        </div>
                        <div class="form-group p-2 m-1">
                            <label for="orbitas">Orbitas Wilayah ke Kabupaten/Kota</label>
                            <select name="orbitas" id="orbitas" class="form-control" required>
                                <option value="">-Pilih-</option>
                            </select>
                        </div>
                        <div class="form-group p-2 m-1">
                            <label for="kondisi_wilayah">Kondisi Wilayah</label>
                            <select name="kondisi_wilayah" id="kondisi_wilayah" class="form-control" required>
                                <option value="">-Pilih-</option>
                            </select>
                        </div>
                        <div class="form-group p-2 m-1">
                            <label for="batas_desa">Batas Desa</label>
                            <select name="batas_desa" id="batas_desa" class="form-control" required>
                                <option value="">-Pilih-</option>
                            </select>
                        </div>
                        <br>
                        <div class="form-group p-2 m-1">
                            <h4 style="font-weight: bold;">Jumlah Komposisi Umur</h4>
                            <hr style="border: 1px solid black;">
                            <label for="umur<15">Kurang dari 15 Tahun</label><br>
                            <table>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Lalu</td>
                                    <td><input name="umur<15_lalu" id="umur<15_lalu" class="form-control" required></input></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Ini</td>
                                    <td><input name="umur<15_ini" id="umur<15_ini" class="form-control" required></input></td>
                                </tr>
                            </table>
                        </div>  
                        <div class="form-group p-2 m-1">
                        <label for="umur15-56">Lebih 15 Tahun Sampai 56 Tahun</label><br>
                            <table>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Lalu</td>
                                    <td><input name="umur15-56_lalu" id="umur15-56_lalu" class="form-control" required></input></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Ini</td>
                                    <td><input name="umur15-56_ini" id="umur15-56_ini" class="form-control" required></input></td>
                                </tr>
                            </table>
                        </div> 
                        <div class="form-group p-2 m-1">
                        <label for="umur>56">Lebih dari 56 Tahun</label><br>
                            <table>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Lalu</td>
                                    <td><input name="umur>56_lalu" id="umur>56_lalu" class="form-control" required></input></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Ini</td>
                                    <td><input name="umur>56_ini" id="umur>56_ini" class="form-control" required></input></td>
                                </tr>
                            </table>
                        </div> 
                        <br>
                        <div class="form-group p-2 m-1">
                            <h4 style="font-weight: bold;">Jumlah Penduduk Menurut Gender</h4>
                            <hr style="border: 1px solid black;">
                            <label for="penduduk_total">Jumlah Penduduk Total</label><br>
                            <table>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Lalu</td>
                                    <td><input name="penduduk_total_lalu" id="penduduk_total_lalu" class="form-control" required></input></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Ini</td>
                                    <td><input name="penduduk_total_ini" id="penduduk_total_ini" class="form-control" required></input></td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-group p-2 m-1">
                            <label for="penduduk_lk">Jumlah Penduduk Laki-Laki</label><br>
                            <table>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Lalu</td>
                                    <td><input name="penduduk_lk_lalu" id="penduduk_lk_lalu" class="form-control" required></input></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Ini</td>
                                    <td><input name="penduduk_lk_ini" id="penduduk_lk_ini" class="form-control" required></input></td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-group p-2 m-1">
                            <label for="penduduk_pr">Jumlah Penduduk Perempuan</label><br>
                            <table>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Lalu</td>
                                    <td><input name="penduduk_pr_lalu" id="penduduk_pr_lalu" class="form-control" required></input></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Ini</td>
                                    <td><input name="penduduk_pr_ini" id="penduduk_pr_ini" class="form-control" required></input></td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-group p-2 m-1">
                            <label for="penduduk_kk">Jumlah Kepala Keluarga</label><br>
                            <table>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Lalu</td>
                                    <td><input name="penduduk_kk_lalu" id="penduduk_kk_lalu" class="form-control" required></input></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: semibold; padding: 10px;" width="120">Tahun Ini</td>
                                    <td><input name="penduduk_kk_ini" id="penduduk_kk_ini" class="form-control" required></input></td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
    
</body>

</html>