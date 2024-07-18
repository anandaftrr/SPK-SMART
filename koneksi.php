<?php 

//atur koneksi ke database
$host_db = "localhost";
$user_db = "root";
$pass_db = "";
$nama_db = "kelurahan_terbaik";

$koneksi = mysqli_connect($host_db, $user_db, $pass_db, $nama_db) or die("Koneksi Database Gagal");

 ?>