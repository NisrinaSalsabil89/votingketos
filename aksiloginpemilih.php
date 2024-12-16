<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "evoting");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

session_start(); // Pindahkan session_start() ke atas untuk memastikan sesi dimulai

if (isset($_POST['login'])) {
    $pin = $_POST['pin'];

    // Cek login admin
    $sqladm = mysqli_query($conn, "SELECT * FROM pemilih WHERE pin ='$pin'");
    $hitung_admin = mysqli_num_rows($sqladm);
    
    if ($hitung_admin > 0) {
        $radm = mysqli_fetch_array($sqladm);
        
        // Cek apakah admin sudah memberikan suara
        if ($radm['has_voted'] == 1) {
            echo "<script>alert('Anda sudah memberikan suara, tidak dapat login kembali.');</script>";
            echo "<meta http-equiv='Refresh' Content='1; URL=login_pemilih.php?page=login_pemilih'>";
            exit();
        }

        $_SESSION['role'] = 'dassmp';
        $_SESSION['id_pemilih'] = $radm["id_pemilih"]; // Ganti $ruser dengan $radm
        $_SESSION["pinsmp"] = $radm["pin"];
        echo "<meta http-equiv='Refresh' Content='1; URL=dashboard_pengguna.php'>";
    } 
    // Cek login pemilih
    else {
        $sqluser = mysqli_query($conn, "SELECT * FROM pemilih_smk WHERE pin='$pin'");
        $hitung_user = mysqli_num_rows($sqluser);
        
        if ($hitung_user > 0) {
            $ruser = mysqli_fetch_array($sqluser);
            
            // Cek apakah pemilih sudah memberikan suara
            if ($ruser['has_voted'] == 1) {
                echo "<script>alert('Anda sudah memberikan suara, tidak dapat login kembali.');</script>";
                echo "<meta http-equiv='Refresh' Content='1; URL=login_pemilih.php?page=login_pemilih'>";
                exit();
            }

            $_SESSION['role'] = 'dassmk';
            $_SESSION['id_pemilihsmk'] = $ruser["id_pemilihsmk"]; // Simpan id_pemilih ke dalam sesi
            $_SESSION["pinsmk"] = $ruser["pin"];
            echo "<meta http-equiv='Refresh' Content='1; URL=dashboard_SMK.php'>";
        } 
        else {
            echo "<meta http-equiv='Refresh' Content='1; URL=login_pemilih.php?page=login_pemilih'>";
        }
    }
}
?>