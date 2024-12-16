<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "evoting");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID kandidat dari URL
if (isset($_GET['id'])) {
    $id_kandidat = $_GET['id'];

    // Ambil gambar dari database
    $sql = "SELECT foto FROM kandidat_smk WHERE id_kandidatsmk = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_kandidat);
    $stmt->execute();
    $stmt->bind_result($foto);
    $stmt->fetch();
    $stmt->close();

    // Cek apakah gambar ditemukan
    if ($foto) {
        // Set header untuk menampilkan gambar
        header("Content-Type: image/jpeg"); // Ganti dengan image/png jika formatnya PNG
        echo $foto;
    } else {
        // Jika gambar tidak ditemukan, tampilkan gambar default atau pesan error
        header("HTTP/1.0 404 Not Found");
        echo "Gambar tidak ditemukan.";
    }
} else {
    // Jika ID tidak diberikan, tampilkan pesan error
    header("HTTP/1.0 400 Bad Request");
    echo "ID kandidat tidak diberikan.";
}

$conn->close();
?>