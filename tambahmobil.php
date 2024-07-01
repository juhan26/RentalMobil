<?php 
    include('modules/koneksi.php');
    session_start();

    // Inisialisasi variabel untuk menyimpan nilai input
    $no_polisi = isset($_POST['no_polisi']) ? $_POST['no_polisi'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $warna = isset($_POST['warna']) ? $_POST['warna'] : '';
    $tahun = isset($_POST['tahun']) ? $_POST['tahun'] : '';
    $kategori_id = isset($_POST['kategori_id']) ? $_POST['kategori_id'] : '';

    // Validasi untuk setiap aksi
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $no_polisi = mysqli_real_escape_string($con, $no_polisi);
        $nama = mysqli_real_escape_string($con, $nama);
        $warna = mysqli_real_escape_string($con, $warna);
        $tahun = mysqli_real_escape_string($con, $tahun);
        $kategori_id = mysqli_real_escape_string($con, $kategori_id);

        // Validasi untuk field kosong
        if(empty($no_polisi) || empty($nama) || empty($warna) || empty($tahun) || empty($kategori_id)) {
            $_SESSION['error_message'] = "Semua field harus diisi.";
        } else {
            $query_check = "SELECT * FROM mobil WHERE no_polisi = '$no_polisi'";
            $result_check = mysqli_query($con, $query_check);

            if (mysqli_num_rows($result_check) > 0) {
                $_SESSION['error_message'] = "Nomor Polisi '$no_polisi' telah digunakan oleh mobil lain.";
            } else {
                $result = mysqli_query($con, "INSERT INTO mobil (no_polisi, nama, warna, tahun, kategori_id) VALUES ('$no_polisi', '$nama', '$warna', '$tahun', '$kategori_id')");

                if ($result) {
                    $_SESSION['success_message'] = "Data mobil berhasil ditambahkan.";

                    // Reset nilai input setelah berhasil disimpan
                    $no_polisi = '';
                    $nama = '';
                    $warna = '';
                    $tahun = '';
                    $kategori_id = '';
                    
                    header('location: mobil.php');                  
                    exit();
                } else {
                    $_SESSION['error_message'] = "Gagal menambahkan data mobil.";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Mobil Juodge - Tambah Mobil</title>
    <?php include('components/dependencies.php'); ?>
</head>

<body>
    <div class="wrapper">
        <?php include('components/sidebar.php') ?>
        <div id="content">
            <?php include('components/navbar.php') ?>
            <div id="content-wrapper">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <h4 class="card-title">Form Mobil</h4>
                        <p class="text-muted">Input data mobil baru</p>
                        <!-- Alert jika ada pesan sukses atau error -->
                        <?php if(isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['error_message']; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>

                        <form action="tambahmobil.php" method="POST">
                            <div class="form-group">
                                <label for="no_polisi">Nomor Polisi</label>
                                <input type="text" name="no_polisi" id="no_polisi" class="form-control" placeholder="Nomor Polisi" value="<?php echo htmlspecialchars($no_polisi); ?>">
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama Mobil</label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama mobil" value="<?php echo htmlspecialchars($nama); ?>">
                            </div>
                            <div class="form-group">
                                <label for="kategori_id">Kategori Mobil</label>
                                <select name="kategori_id" id="kategori_id" class="form-control">
                                    <option value="">Pilih Kategori</option>
                                    <?php
                                        $query_kategori = "SELECT * FROM kategori";
                                        $result_kategori = mysqli_query($con, $query_kategori);
                                        while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
                                            $selected = ($kategori_id == $row_kategori['id']) ? 'selected' : '';
                                            echo '<option value="'.$row_kategori['id'].'" '.$selected.'>'.$row_kategori['nama'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="warna">Warna</label>
                                <input type="text" name="warna" id="warna" class="form-control" placeholder="Warna" value="<?php echo htmlspecialchars($warna); ?>">
                            </div>
                            <div class="form-group">
                                <label for="tahun">Tahun</label>
                                <input type="number" name="tahun" id="tahun" class="form-control" placeholder="Tahun" value="<?php echo htmlspecialchars($tahun); ?>">
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="mobil.php" type="button" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
