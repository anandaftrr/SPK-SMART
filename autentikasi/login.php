<?php
session_start();
include '../koneksi.php';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemilihan Kelurahan Terbaik</title>
    <link rel="shortcut icon" type="x-ixon" href="../gambar/Logo Pemkot Padang.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet" href="../assets/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
</head>
<style>
    .divider:after,
    .divider:before {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
    }
</style>

<body>
    <div class="container">
        <div class="login">
            <form role="form" id="loginform" action="process_login.php" method="post">
                <h1>LOGIN</h1>
                <hr>
                <p>Sistem Pendukung Keputusan Pemilihan Kelurahan Terbaik Kota Padang</p>

                <label for="">Username</label>

                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input id="login-username" type="text" class="input" name="username" required>

                <label for="">Password</label>
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input id="login-password" type="password" class="input" name="password" required>

                <div style="margin-top:25px" class="form-group">
                    <div class="col-sm-12 controls">
                        <input type="submit" class="btn btn-success" value="Login" name="login" />
                    </div>
                </div>
            </form>
        </div>
        <div class="right">
            <img src="../gambar/Login.jpg" alt="">
        </div>
    </div>
</body>

</html>