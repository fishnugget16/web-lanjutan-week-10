<?php

/**
 * File: logout.php
 * Menghapus seluruh session otentikasi pengguna dan mengarahkan kembali ke login.php
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();

session_destroy();

header("Location: login.php");

exit;