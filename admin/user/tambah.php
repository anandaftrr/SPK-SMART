<?php
session_start();
include '../../koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: /autentikasi/login.php');
    exit;
}

// Periksa peran pengguna
if ($_SESSION['role'] != 'admin') {
    // Jika bukan admin, redirect ke halaman lain atau berikan pesan akses ditolak
    header('Location: /autentikasi/unauthorized.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = password_hash($password, PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $id_kelurahan = isset($_POST['id_kelurahan']) ? $_POST['id_kelurahan'] : null;

    $checkUsername = $koneksi->query(
        "SELECT * FROM users WHERE username = '$username'"
    );

    if ($checkUsername->num_rows != 0) {
        $messageusername = 'username sudah digunakan!!';
    } else {
        if ($id_kelurahan) {
            $insert = $koneksi->query(
                "INSERT INTO users (id_kelurahan, username, password, role) VALUES ('$id_kelurahan', '$username', '$password', '$role')"
            );
        } else {
            $insert = $koneksi->query(
                "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')"
            );
        }

        if ($insert) {
            header('Location: /admin/user/lihat.php?add=success');
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
        <?php include '../../layouts/header.php'; ?>
        <?php include '../../layouts/admin_sidebar.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Tambah User</h1>
                </div>
                <!-- End Page Title -->
                <!-- Content -->
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                        <?= $success ?>
                    </div>
                <?php endif; ?>
                <form class="mx-3 mt-3" action="" method="POST" onsubmit="checkForm(event)">
                    <div class="form-group col-md-6">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($username) ? $username : '' ?>" required>
                        <?php if (isset($messageusername)): ?>
                            <div id="username" class="invalid text-danger">
                                <?= $messageusername; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" onchange="checkPassword()" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" onchange="checkPassword()" required>

                        <div class="invalid-feedback">
                            Konfirmasi password harus sama dengan password.
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="role">Role</label>
                        <select class="form-control" name="role" id="role" required>
                            <option value="penilai" <?= ((isset($role)) && ($role == 'penilai')) ? 'Selected' : '' ?>>Penilai</option>
                            <option value="kelurahan" <?= ((isset($role)) && ($role == 'kelurahan')) ? 'Selected' : '' ?>>Kelurahan</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <button type="submit" class="btn btn-primary" name="save">Simpan</button>
                    </div>
                </form>
                <!-- End Content -->
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
    </div>
    <script>
        function checkPassword() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                if (document.getElementById('confirm_password').classList.contains('is-valid')) {
                    document.getElementById('confirm_password').classList.remove('is-valid');
                }
                document.getElementById('confirm_password').classList.add('is-invalid'); // Tambahkan kelas invalid
            } else {
                if (document.getElementById('confirm_password').classList.contains('is-invalid')) {
                    document.getElementById('confirm_password').classList.remove('is-invalid');
                }
                document.getElementById('confirm_password').classList.add('is-valid'); // Tambahkan kelas valid jika sama
            }
        }

        function checkForm(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                event.preventDefault();
            }
        }
    </script>
</body>

</html>