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

$id = $_GET['id'];

$delete = $koneksi->query(
    "DELETE FROM periode WHERE id=$id"
);

if ($delete) {
    header('Location: /admin/periode/lihat.php?action=delete&status=success');
} else {
    header('Location: /admin/periode/lihat.php?action=delete&status=failed');
}
