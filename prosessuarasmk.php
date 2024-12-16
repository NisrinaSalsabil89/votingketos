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
    if (isset($_POST['id_kandidatsmk']) && isset($_POST['id_pemilihsmk'])) {
        $id_kandidat = $_POST['id_kandidatsmk'];
        $id_pemilih = $_POST['id_pemilihsmk'];

        // Periksa apakah id_kandidat ada di tabel kandidat_smk
        $checkKandidat = $conn->prepare("SELECT * FROM kandidat_smk WHERE id_kandidatsmk = ?");
        $checkKandidat->bind_param("i", $id_kandidat);
        $checkKandidat->execute();
        $resultKandidat = $checkKandidat->get_result();

        if ($resultKandidat->num_rows === 0) {
            echo json_encode(['status' => 'gagal', 'pesan' => 'ID kandidat tidak ditemukan di tabel kandidat_smk.']);
            exit();
        }

        // Lanjutkan dengan logika penyimpanan suara
        $sql = "INSERT INTO hasil_pemilihan (id_kandidatsmk, id_pemilihsmk) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_kandidat, $id_pemilih);

        if ($stmt->execute()) {
            // Jika suara berhasil disimpan, perbarui kolom has_voted
            $updateVoteStatus = $conn->prepare("UPDATE pemilih_smk SET has_voted = 1 WHERE id_pemilihsmk = ?");
            $updateVoteStatus->bind_param("i", $id_pemilih);
            if ($updateVoteStatus->execute()) {
                echo json_encode(['status' => 'sukses', ' pesan' => 'Suara berhasil disimpan dan status pemilih diperbarui.']);
            } else {
                error_log("Gagal memperbarui kolom has_voted: " . $updateVoteStatus->error);
                echo json_encode(['status' => 'gagal', 'pesan' => 'Suara berhasil disimpan, tetapi gagal memperbarui status pemilih: ' . $updateVoteStatus->error]);
            }
        } else {
            echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal menyimpan suara: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['status' => 'gagal', 'pesan' => 'ID kandidat dan ID pemilih harus disediakan.']);
    }
}

// ```php
// Menutup koneksi
if (isset($conn) && $conn) {
    $conn->close();
}
?>