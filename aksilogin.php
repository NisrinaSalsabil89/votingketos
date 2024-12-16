<?php

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "evoting");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

session_start(); // Pindahkan session_start() ke atas untuk memastikan sesi dimulai

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek login admin
    $sqladm = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    $hitung_admin = mysqli_num_rows($sqladm);
    
    if ($hitung_admin > 0) {
        $radm = mysqli_fetch_array($sqladm);
        $_SESSION['role'] = 'adminsmp';
        $_SESSION["useradm"] = $radm["username"];
        $_SESSION["passadm"] = $radm["password"];
        echo "<meta http-equiv='Refresh' Content='1; URL=dashboard_admin.php'>";
    } 
    // Cek login pemilih
    else {
        $sqluser = mysqli_query($conn, "SELECT * FROM admin_smk WHERE username='$username' AND password='$password'");
        $hitung_user = mysqli_num_rows($sqluser);
        
        if ($hitung_user > 0) {
            $ruser = mysqli_fetch_array($sqluser); // Perbaiki variabel $radm menjadi $ruser
            $_SESSION['role'] = 'adminsmk';
            $_SESSION["usersmk"] = $ruser["username"]; // Perbaiki variabel $radm menjadi $ruser
            $_SESSION["passsmk"] = $ruser["password"]; // Perbaiki variabel $radm menjadi $ruser
            echo "<meta http-equiv='Refresh' Content='1; URL=dashboard_adminsmk.php'>";
        } 
        else {
            echo "<meta http-equiv='Refresh' Content='1; URL=index.php?page=login'>";
        }
    }
}
?>