<?php

session_start();

if ($_SESSION['role'] == 'admin') {
    header('Location: admin/dashboard.php');
} else {
    header('Location: autentikasi/login.php');
}
