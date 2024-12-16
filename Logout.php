<?php
session_start();
session_destroy(); // Hapus sesi
header("Location: Login.php");
exit(); // Pastikan untuk keluar setelah redirect
?>