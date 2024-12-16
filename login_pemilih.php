
<?php
session_start();
include 'koneksi.php'; // Pastikan Anda menghubungkan ke database

function verifyPin($pin, $user_id) {
    // Koneksi ke database
    global $conn; // Menggunakan koneksi global

    // Menghindari SQL Injection
    $user_id = $conn->real_escape_string($user_id);
    $pin = $conn->real_escape_string($pin);
    
    // Query untuk mendapatkan PIN hash dari database
    $sql = "SELECT pin FROM pemilih WHERE id_user = '$user_id'";
    $result = $conn->query($sql);

    // Memeriksa apakah user_id ada di database
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pin_hash = $row['pin_hash'];

        // Memverifikasi PIN
        return password_verify($pin, $pin_hash);
    }
    return false; // User tidak ditemukan
}

function hasVoted($user_id) {
    global $conn; // Menggunakan koneksi global

    // Menghindari SQL Injection
    $user_id = $conn->real_escape_string($user_id);
    
    // Query untuk memeriksa apakah pengguna telah memberikan suara
    $sql = "SELECT has_voted FROM pemilih WHERE id_user = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['has_voted'] == 1; // Mengembalikan true jika sudah memberikan suara
    }
    return false; // User tidak ditemukan
}

function hasVoted2($user_id) {
  global $conn; // Menggunakan koneksi global

  // Menghindari SQL Injection
  $user_id = $conn->real_escape_string($user_id);
  
  // Query untuk memeriksa apakah pengguna telah memberikan suara
  $sql = "SELECT has_voted FROM pemilih_smk WHERE id_user = '$user_id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['has_voted'] == 1; // Mengembalikan true jika sudah memberikan suara
  }
  return false; // User tidak ditemukan
}

// Mendapatkan data dari formulir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = $_POST['pin'];
    $user_id = $_POST['id_user']; // Ambil user_id dari formulir

    // Memeriksa apakah pengguna sudah memberikan suara
    if (hasVoted($user_id)) {
        $error_message = "Anda sudah memberikan suara, tidak dapat login kembali.";
        // Redirect atau tampilkan pesan kesalahan
        header("Location: login_pemilih.php?error=" . urlencode($error_message));
        exit();
    }

    if (hasVoted2($user_id)) {
      $error_message = "Anda sudah memberikan suara, tidak dapat login kembali.";
      // Redirect atau tampilkan pesan kesalahan
      header("Location: login_pemilih.php?error=" . urlencode($error_message));
      exit();
  }

    // Memverifikasi PIN
}
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
    .panel-default {
        width: 50%;
        align-content: center;
    }
  </style>
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <img src="assets/img/images.png" alt="">
        <img src="assets/img/logosis.jpg " alt="">
        <h1 class="sitename">E-VOTING SMPI & SMKI AL-FAQIH MALANG</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <i class="mobile-nav-toggle d-xl-none bi bi-list">Beranda</i>
      </nav>
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">
      <div class="hero-bg">
        <img src="assets/img/hero-bg-light.webp" alt="">
      </div>

      <div class="container mt-5">
        <center>
          <div class="panel panel-default" align="center">
            <div class="panel-heading">
              <center><b>Masukkan PIN Anda</b></center>
            </div>
            <div class="panel-body">
            <form id="form-login" method="post" action="aksiloginpemilih.php">
            <br>
            <div class="form-group">
                <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($user_id); ?>"> <!-- Pastikan user_id diisi -->
                <input type="text" name="pin" placeholder="Masukkan PIN" pattern="\d*" required>
            </div>
            <br>
            <button type="submit" value="login" name="login" class="btn btn-primary">Login</button>
            </form>
              <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
            </div>
          </div>
        </center>
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