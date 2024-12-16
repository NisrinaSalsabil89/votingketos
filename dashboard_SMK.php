<?php
// Memulai sesi
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "evoting");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data kandidat dari database
$sql = "SELECT * FROM kandidat_smk";
$result = $conn->query($sql);

// Ambil id_pemilih dari sesi
$id_pemilihsmk = isset($_SESSION['id_pemilihsmk']) ? $_SESSION['id_pemilihsmk'] : null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>E-Voting</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  
  <style>
      .candidates-container {
          display: flex;
          justify-content: center; /* Mengatur agar kandidat berada di tengah */
          flex-wrap: wrap; /* Membuat kandidat membungkus jika tidak muat dalam satu baris */
      }
      .candidate {
          display: flex;
          flex-direction: column; /* Menjaga gambar di atas nama */
          align-items: center; /* Menjaga agar gambar dan nama rata tengah */
          margin: 20px; /* Jarak antar kandidat, bisa disesuaikan */
      }
      .candidate img {
          width: 280px; /* Ukuran gambar */
          height: auto; /* Menjaga rasio gambar */
          cursor: pointer; /* Menunjukkan bahwa gambar dapat diklik */
          object-fit: cover; /* Memastikan gambar menutupi area tanpa distorsi */
      }
      .candidate-name {
          font-size: 1.2em; /* Ukuran font untuk nama kandidat */
          text-align: center; /* Menjaga nama kandidat rata tengah */
      }

      .notifikasi {
          display: none; /* Sembunyikan notifikasi secara default */
          position: fixed;
          bottom: 20px; /* Jarak dari bawah */
          right: 20px; /* Jarak dari kanan */
          background-color: #4CAF50; /* Warna latar belakang */
          color: white; /* Warna teks */
          padding: 15px; /* Padding */
          border-radius: 5px; /* Sudut melengkung */
          z-index: 1000; /* Pastikan di atas elemen lain */
          transition: opacity 0.5s; /* Transisi untuk efek fade */
          opacity: 0; /* Mulai dengan transparan */
      }

      .notifikasi.show {
          display: block; /* Tampilkan notifikasi */
          opacity: 1; /* Buat notifikasi terlihat */
      }
  </style>

<div id="notifikasi" class="notifikasi"></div>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <img src="assets/img/images.png" alt="">
        <img src="assets/img/logosis.jpg" alt="">
        <h1 class="sitename">E-VOTING SMKI AL-FAQIH MALANG</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <button class="btn btn-danger btn-logout" onclick="logout()">Keluar</button>
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">
      <div class="hero-bg">
        <img src="assets/img/hero-bg-light.webp" alt="">
      </div>
      <div class="container text-center">
        <h2>Kandidat Ketua dan Wakil Osis</h2>
        <div class="candidates-container">
            <?php
            if ($result->num_rows > 0) {
                // Output data dari setiap baris
                while($row = $result->fetch_assoc()) {
                    echo "<div class='candidate' onclick='pilihKandidat(" . $row['id_kandidatsmk'] . ")'>";
                    echo "<img src ='getimagesmk.php?id=" . $row['id_kandidatsmk'] . "' alt='" . $row['nama_kandidat'] . "'>";
                    echo "<span class='candidate-name'>" . $row['nama_kandidat'] . "</span>";
                    echo "</div>";
                }
            } else {
                echo "<div>Tidak ada kandidat yang ditemukan.</div>";
            } 
            ?>
        </div>
      </div>
    </section><!-- /Hero Section -->

  <footer id="footer" class="footer position-relative light-background">
    <div class="container footer-top">
        <div class="footer-about">
          <div class="footer-contact">
            <p>Jalan Utara Makam No.45 Sukoanyar, Kab. Malang</p> 
            <p>Email : smpialfaqihmalang@gmail.com</p>
            <p>(0341) 785 695</p>
          </div>
        </div>
    </div>
  </footer>

  <!-- Scroll to top button -->
  <a href="#" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
const id_pemilihsmk = <?php echo json_encode($id_pemilihsmk); ?>; // Mengambil dari PHP
</script>
  <script >
let hasVoted = false; // Variabel untuk menyimpan status pemilihan

  function pilihKandidat(id_kandidatsmk) {
    console.log("ID Kandidat yang dipilih: " + id_kandidatsmk);
    console.log("ID Pemilih: " + id_pemilihsmk); // Debugging
    $.ajax({
        type: "POST",
        url: "prosessuarasmk.php",
        data: { id_kandidatsmk: id_kandidatsmk, id_pemilihsmk: id_pemilihsmk }, // Kirim id_kandidat dan id_pemilih
        dataType: "json",
        success: function(response) {
            if (response.status === "sukses") {
                showNotification("Suara Anda telah berhasil disimpan!"); // Menggunakan notifikasi
                hasVoted = true; // Set status pemilihan
                logout(); // Panggil fungsi logout setelah pemilihan
            } else {
                showNotification("Terjadi kesalahan: " + response.pesan);
            }
        },
        error: function(xhr, status, error) {
            console.error("Terjadi kesalahan saat mengirim data: " + error);
            showNotification("Terjadi kesalahan saat mengirim data. Silakan coba lagi.");
        }
    });
}

function logout() {
    console.log("Memeriksa status pemilihan sebelum logout: " + hasVoted); // Debugging
    // Jika pengguna sudah melakukan pemilihan, langsung lanjutkan proses logout
    if (hasVoted) {
        // Jika sudah melakukan pemilihan, lanjutkan dengan proses logout
        $.ajax({
            type: "POST",
            url: "logout.php",
            success: function() {
                // Redirect ke halaman login setelah logout berhasil
                window.location.href = 'login_pemilih.php';
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan saat logout: " + error);
                showNotification("Terjadi kesalahan saat logout. Silakan coba lagi.");
            }
        });
    } else {
        console.log("Pengguna belum melakukan pemilihan, tidak perlu logout.");
    }
}

function showNotification(message) {
    const notification = document.getElementById('notifikasi');
    notification.textContent = message; // Set pesan notifikasi
    notification.classList.add('show'); // Tampilkan notifikasi
    setTimeout(() => {
        notification.classList.remove('show'); // Sembunyikan notifikasi setelah beberapa detik
    }, 3000);
}

</script>

</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>