<?php
header('Content-Type: application/json');

// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "evoting"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die(json_encode(['status' => 'gagal', 'pesan' => 'Koneksi ke database gagal: ' . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_kandidat']) && isset($_POST['id_pemilih'])) {
        $id_kandidat = $_POST['id_kandidat'];
        $id_pemilih = $_POST['id_pemilih'];

        // Log untuk debugging
        error_log("ID Kandidat: " . $id_kandidat);
        error_log("ID Pemilih: " . $id_pemilih);

        // Lanjutkan dengan logika penyimpanan suara
        $sql = "INSERT INTO suara (id_kandidat, id_pemilih) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_kandidat, $id_pemilih);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'sukses', 'pesan' => 'Suara berhasil disimpan.']);
        } else {
            echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal menyimpan suara: ' . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'gagal', 'pesan' => 'ID kandidat atau ID pemilih tidak ditemukan.']);
    }
} else {
    echo json_encode(['status' => 'gagal', 'pesan' => 'Metode permintaan tidak valid.']);
}

// ```php
// Menutup koneksi
if (isset($conn) && $conn) {
    $conn->close();
}
?>