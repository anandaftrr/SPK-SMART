<?php
session_start();

// Sambungkan ke database
include 'koneksi.php';

// Tangkap data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Lakukan sanitasi input
$username = mysqli_real_escape_string($koneksi, $username);
$password = mysqli_real_escape_string($koneksi, $password);

// Query untuk mencari pengguna berdasarkan username dan password
$sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
$result = $koneksi->query($sql);

if ($result->num_rows == 1) {
    // Pengguna ditemukan, ambil data pengguna
    $row = $result->fetch_assoc();
    
    // Simpan informasi pengguna ke dalam session
    $_SESSION['id_user'] = $row['id_user'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];
    
    // Redirect ke halaman sesuai peran (role)
    switch ($row['role']) {
        case 'admin':
            header('Location: dashboard_admin.php');
            break;
        case 'pimpinan':
            header('Location: dashboard_pimpinan.php');
            break;
        case 'penilai':
            header('Location: dashboard_penilai.php');
            break;
        case 'kelurahan':
            header('Location: dashboard_kelurahan.php');
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
?>
