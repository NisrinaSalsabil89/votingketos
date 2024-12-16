<?php
include 'koneksi.php'; // Pastikan koneksi.php menginisialisasi $pdo

try {
    $pdo = new PDO("mysql:host=localhost;dbname=evoting", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    exit();
}

// Ambil total suara
$totalVotesQuery = $pdo->query("SELECT COUNT(*) as total FROM hasil_pemilihan");
$totalVotes = $totalVotesQuery->fetch(PDO::FETCH_ASSOC)['total'];

// Ambil suara per kandidat
$candidatesQuery = $pdo->query("SELECT c.id_kandidatsmk, c.nama_kandidat AS nama_kandidat, COUNT(v.id_kandidatsmk) as votes FROM kandidat_smk c LEFT JOIN hasil_pemilihan v ON c.id_kandidatsmk = v.id_kandidatsmk GROUP BY c.id_kandidatsmk, c.nama_kandidat");
$candidates = $candidatesQuery->fetchAll(PDO::FETCH_ASSOC);
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
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
      /* CSS untuk memastikan chart responsif */
      #votesChart {
          width: 300px;  /* Atur lebar sesuai kebutuhan */
          height: 300px; /* Atur tinggi sesuai kebutuhan */
      }

      @media (max-width: 768px) {
          #votesChart {
              width: 200px; /* Ukuran lebih kecil untuk layar kecil */
              height: 200px; /* Ukuran lebih kecil untuk layar kecil */
          }
      }

      /* Mengatur margin dan padding untuk menghindari scroll */
      body, html {
          margin: 0;
          padding: 0;
          overflow-x: hidden; /* Menghindari scroll horizontal */
      }

      .container {
          padding: 20px; /* Menambahkan padding untuk konten */
      }
  </style>

</head>

<body class="index-page">
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">
            <a href="index.php" class="logo d-flex align-items-center me-auto">
                <img src="assets/img/images.png" alt="">
                <img src="assets/img/logosis.jpg" alt="">
                <h1 class="sitename">E-VOTING SMKI AL-FAQIH MALANG</h1>
            </a>
            <a href="logout.php" class="btn btn-danger float-end">Keluar</a>
            <nav id="navmenu" class="navmenu">
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <main class="main">
        <section id="hero" class="hero section">
            <div class="hero-bg">
                <img src="assets/img/hero-bg-light.webp" alt="">
            </div>
            <div class="container mt-5">
                <h1>Dashboard Admin E-Voting</h1>
                <h2>Persentase Suara</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kandidat</th>
                            <th>Jumlah Suara</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($candidates as $candidate): ?>
                            <?php
                            $votes = $candidate['votes']; // Menggunakan 'votes' sesuai dengan alias di query
                            $percentage = $totalVotes > 0 ? ($votes / $totalVotes) * 100 : 0;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($candidate['nama_kandidat']); ?></td>
                                <td><?php echo $votes; ?></td>
                                <td><?php echo number_format($percentage, 2) . '%'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                <br>
                <!-- Canvas untuk Pie Chart -->
                <h2>Pie Chart Suara Kandidat</h2>
                <canvas id="votesChart"></canvas>
            </div>
        </section>
    </main>

    <script>
        // Data untuk Pie Chart
        const candidates = <?php echo json_encode($candidates); ?>;
        const labels = candidates.map(candidate => candidate.nama_kandidat);
        const data = candidates.map(candidate => candidate.votes);

        // Konfigurasi Pie Chart
        const ctx = document.getElementById('votesChart').getContext('2d');
        const votesData = {
            labels: <?php echo json_encode(array_column($candidates, 'nama_kandidat')); ?>,
            datasets: [{
                label: 'Jumlah Suara',
                data: <?php echo json_encode(array_column($candidates, 'votes')); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        const votesChart = new Chart(ctx, {
            type: 'pie',
            data: votesData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>