<?php
session_start(); // Memulai session

// Simulasi pengaturan pesan (Anda bisa mengganti ini dengan logika Anda sendiri)
if (!isset($_SESSION['message'])) {
    $_SESSION['message'] = "Pesan ini hanya muncul sekali.";
}

// Menampilkan pesan jika ada
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Menghapus pesan dari session setelah ditampilkan
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>E-Voting</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <style>
      .message-container {
          display: flex;
          flex-direction: column; /* Mengatur agar pesan dan tombol berada dalam kolom */
          justify-content: center;
          align-items: center;
          height: 100vh; /* Mengatur tinggi untuk memusatkan secara vertikal */
      }
  </style>
</head>
<body class="index-page">
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <img src="assets/img/images.png" alt="">
        <img src="assets/img/logosis.jpg" alt="">
        <h1 class="sitename">E-VOTING SMPI AL-FAQIH MALANG</h1>
      </a>
      <nav id="navmenu" class="navmenu">
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
</header>

<main class="main">
    <div class="message-container">
        <?php if ($message): ?>
            <div class="alert alert-success">
                <?php echo $message; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                Tidak ada pesan untuk ditampilkan.
            </div>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-danger">Keluar</a> <!-- Tombol Keluar -->
    </div>
</main>

    <footer id="footer" class="footer position-relative light-background">
    <div class="container footer-top">
        <div class="footer-about">
        <div class="footer-contact">
            <p>Jalan Utara Makam No.45 Sukoanyar, Kab. Malang</p> 
            <p>Email : smpialfaqihmalang@gmail.com</p>
            <p>(0341) 785 695</p>
        </div>
    </div>
    </footer>

     <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

<!-- Main JS File -->
<script src="assets/js/main.js"></script>

</body>
</html>

<style>
      .message-container {
          display: flex;
          flex-direction: column; /* Mengatur agar pesan dan tombol berada dalam kolom */
          justify-content: center;
          align-items: center;
          height: 100vh; /* Mengatur tinggi untuk memusatkan secara vertikal */
      }
      .btn-danger {
          margin-top: 20px; /* Tambahkan jarak atas untuk tombol */
      }
  </style>
