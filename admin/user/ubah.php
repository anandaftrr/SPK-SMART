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

    $id_kelurahan = isset($_POST['id_kelurahan']) ? $_POST['id_kelurahan'] : null;

    $checkUsername = $koneksi->query(
        "SELECT * FROM users WHERE username = '$username'"
    );

    $id = $_GET['id'];

    $result = $koneksi->query(
        "SELECT * FROM users WHERE id=$id"
    );

    $user = $result->fetch_assoc();

    if (($checkUsername->num_rows != 0) && ($user['username'] != $username)) {
        $messageusername = 'username sudah digunakan!!';
    } else {
        $password = $_POST['password'];
        if ($password == '') {
            $update = $koneksi->query(
                "UPDATE users SET username='$username' WHERE id=$id"
            );
            $success = "Berhasil mengubah username!";
        } else {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $update = $koneksi->query(
                "UPDATE users SET username='$username', password='$password' WHERE id=$id"
            );
            $success = "Berhasil mengubah username dan password!";
        }
    }
}
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $result = $koneksi->query(
        "SELECT * FROM users WHERE id=$id"
    );

    $user = $result->fetch_assoc();
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
    <style>
        /* Hidden by default */
        .hidden {
            display: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include '../../layouts/header.php'; ?>
        <?php include '../../layouts/admin_sidebar.php'; ?>
        <div class="content-wrapper">
            <section class="content">
                <!-- Page Title -->
                <div class="pagetitle p-2">
                    <h1>Ubah User</h1>
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
                        <input type="text" class="form-control" id="username" name="username" value="<?= (isset($username) ? $username : (isset($user['username']) ? $user['username'] : '')) ?>" required>
                        <?php if (isset($messageusername)): ?>
                            <div id="username" class="invalid text-danger">
                                <?= $messageusername; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="role">Role</label>
                        <select class="form-control" name="role" id="role" disabled required>
                            <option value=""><?= $user['role']; ?></option>
                        </select>
                    </div>
                    <div class="form-group col-md-6 ms-2 mt-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="checkPWForm" onchange="showPWForm()">
                            <label class=" form-check-label" for="checkPWForm">
                                Ubah password user ini
                            </label>
                        </div>
                    </div>
                    <div id="pwInput" class="hidden">
                        <div class="form-group col-md-6">
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" onchange="checkPassword()">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="confirm_password">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" onchange="checkPassword()">
                            <div class="invalid-feedback">
                                Konfirmasi password harus sama dengan password.
                            </div>
                        </div>
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

        function showPWForm() {
            // Get the checkbox element
            var checkbox = document.getElementById('checkPWForm');

            // Get the div that contains the extra input fields
            var extraInputs = document.getElementById('pwInput');

            // If the checkbox is checked, show the inputs, otherwise hide them
            if (checkbox.checked) {
                extraInputs.classList.remove('hidden');
            } else {
                extraInputs.classList.add('hidden');
            }
        }
    </script>
</body>

</html>