<?php

session_start();

include 'config/base_url.php';

if ($_SESSION['role'] == 'admin') {
    header('Location: ' . $baseURL . '../admin/dashboard');
} else {
    header('Location: ' . $baseURL . '../autentikasi/login.php');
}
