<?php
session_start();

// Sambungkan ke database
include '../koneksi.php';

// Tangkap data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Lakukan sanitasi input
$username = mysqli_real_escape_string($koneksi, $username);
$password = mysqli_real_escape_string($koneksi, $password);

// Query untuk mencari pengguna berdasarkan username dan password
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $koneksi->query($sql);

if ($result->num_rows == 1) {
    // Pengguna ditemukan, ambil data pengguna
    $row = $result->fetch_assoc();

    // Simpan informasi pengguna ke dalam session
    $_SESSION['id_user'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];

    // Redirect ke halaman sesuai peran (role)
    switch ($row['role']) {
        case 'admin':
            header('Location: admin_dashboard.php');
            break;
        case 'pimpinan':
            header('Location: pimpinan_dashboard.php');
            break;
        case 'penilai':
            header('Location: penilai_dashboard.php');
            break;
        case 'kelurahan':
            header('Location: kelurahan_dashboard.php');
            break;
        default:
            // Jika role tidak dikenali, redirect ke halaman default
            header('Location: unauthorized.php');
            break;
    }
} else {
    // Jika pengguna tidak ditemukan, kembalikan ke halaman login
    $_SESSION['login_error'] = "Username atau password salah.";
    header('Location: unauthorized.php');
}

$koneksi->close();
