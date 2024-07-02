<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Mobil Juodge - Beranda</title>
    <?php include('components/dependencies.php'); ?>
    <?php include('modules/koneksi.php'); ?>
    <style>
        .btn-delete {
            margin-right: 5px; 
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include('components/sidebar.php'); ?>
        <div id="content">
            <?php include('components/navbar.php'); ?>
            <div id="content-wrapper">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <h4 class="card-title">Beranda Penyewaan Mobil</h4>
                        <p class="mb-5">Selamat datang di Beranda Penyewaan Mobil! Halaman ini memberikan gambaran umum tentang aktivitas dan status operasional rental mobil. Di sini Anda dapat dengan cepat melihat informasi penting, memantau transaksi terbaru, dan mengakses berbagai fitur manajemen rental mobil.</p>
                        <h4 class="mb-3">Statistik data</h4>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Mobil</h5>
                                        <?php
                                        $query_mobil_count = "SELECT COUNT(*) as total_mobil FROM mobil";
                                        $result_mobil_count = mysqli_query($con, $query_mobil_count);
                                        if (!$result_mobil_count) {
                                            die('Query Error: ' . mysqli_error($con));
                                        }
                                        $row_mobil_count = mysqli_fetch_assoc($result_mobil_count);
                                        ?>
                                        <p class="card-text"><?php echo $row_mobil_count['total_mobil']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Kategori</h5>
                                        <?php
                                        $query_kategori_count = "SELECT COUNT(*) as total_kategori FROM kategori";
                                        $result_kategori_count = mysqli_query($con, $query_kategori_count);
                                        if (!$result_kategori_count) {
                                            die('Query Error: ' . mysqli_error($con));
                                        }
                                        $row_kategori_count = mysqli_fetch_assoc($result_kategori_count);
                                        ?>
                                        <p class="card-text"><?php echo $row_kategori_count['total_kategori']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Petugas</h5>
                                        <?php
                                        $query_petugas_count = "SELECT COUNT(*) as total_petugas FROM petugas";
                                        $result_petugas_count = mysqli_query($con, $query_petugas_count);
                                        if (!$result_petugas_count) {
                                            die('Query Error: ' . mysqli_error($con));
                                        }
                                        $row_petugas_count = mysqli_fetch_assoc($result_petugas_count);
                                        ?>
                                        <p class="card-text"><?php echo $row_petugas_count['total_petugas']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Supir</h5>
                                        <?php
                                        $query_supir_count = "SELECT COUNT(*) as total_supir FROM supir";
                                        $result_supir_count = mysqli_query($con, $query_supir_count);
                                        if (!$result_supir_count) {
                                            die('Query Error: ' . mysqli_error($con));
                                        }
                                        $row_supir_count = mysqli_fetch_assoc($result_supir_count);
                                        ?>
                                        <p class="card-text"><?php echo $row_supir_count['total_supir']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Transaksi</h5>
                                        <?php
                                        $query_transaksi_count = "SELECT COUNT(*) as total_transaksi FROM transaksi";
                                        $result_transaksi_count = mysqli_query($con, $query_transaksi_count);
                                        if (!$result_transaksi_count) {
                                            die('Query Error: ' . mysqli_error($con));
                                        }
                                        $row_transaksi_count = mysqli_fetch_assoc($result_transaksi_count);
                                        ?>
                                        <p class="card-text"><?php echo $row_transaksi_count['total_transaksi']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Add more cards for other counts as needed -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
