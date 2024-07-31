<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'admin') {
    // Jika bukan admin, redirect ke halaman lain atau berikan pesan akses ditolak
    header('Location: unauthorized.php');
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
        <?php include 'admin_sidebar.php'; ?>
        <div class="content-wrapper">
            <section class="content">
            <br>
            <!-- Page Title -->
            <div class="pagetitle p-2">
                <h1>Kelola User</h1>
            </div>
            <!-- End Page Title -->
            <!-- Home Page -->
            <div class="table-responsive">
                <div class="card mt-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped" style="width:100%" >
                <thead>
                    <tr>
                        <th style="text-align: center;">Username</th>
                        <th style="text-align: center;">Password</th>
                        <th style="text-align: center;">Role</th>
                        <th style="text-align: center;">Edit</th>
                        <th style="text-align: center;">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = $koneksi->query(
                        'SELECT * FROM users ORDER BY id_user ASC'
                    );
                    foreach ($users as $key) {

                        $nama = 'userdel' . $key['id_user'];
                        $alamat = 'userdel';
                        ?>
                        <tr align="center">
                            <td>
                                <?= $key['username'] ?>
                            </td>
                            <td>
                                <?= $key['password'] ?>
                            </td>
                            <td>
                                <?= $key['role'] ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#useredit<?= $key[
                                    'id_user'
                                ] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="<?= $nama ?>()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php
                    }
                    ?> 
                </tbody>
            </table>
            <br>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adminadd">
                    Tambah Data
                </button>
            </div>
        </div>
    </div>
</div>
<!--Tambah Data-->
</div>
</div> 

<script> // Membuat data table memiliki fungsi show dan search
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
    </div>
</body>

</html>