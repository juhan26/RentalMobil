<?php 
    include('modules/koneksi.php');
    session_start();

    if(isset($_POST['nama'], $_POST['jabatan'], $_POST['telp'])) {
        $nama = mysqli_real_escape_string($con, $_POST['nama']);
        $jabatan = mysqli_real_escape_string($con, $_POST['jabatan']);
        $telp = mysqli_real_escape_string($con, $_POST['telp']);
        
        // Validasi jika ada field yang kosong
        if(empty($nama) || empty($jabatan) || empty($telp)) {
            $_SESSION['error_message'] = "Silakan lengkapi semua field yang diperlukan.";
        } else {
            // Cek apakah nomor telepon sudah digunakan oleh petugas lain
            $query_check = "SELECT * FROM petugas WHERE telp = '$telp'";
            $result_check = mysqli_query($con, $query_check);

            if (mysqli_num_rows($result_check) > 0) {
                // Jika nomor telepon sudah digunakan, set session error_message
                $_SESSION['error_message'] = "Nomor Telepon '$telp' telah digunakan oleh Petugas lain.";
            } else {
                // Tambahkan data petugas baru
                $result = mysqli_query($con, "INSERT INTO petugas (nama, jabatan, telp) VALUES ('$nama', '$jabatan', '$telp')");

                if ($result) {
                    // Jika berhasil, set pesan sukses
                    $_SESSION['success_message'] = "Data petugas berhasil disimpan!";
                    header("Location: petugas.php"); // Redirect ke halaman petugas setelah sukses
                    exit();
                } else {
                    $_SESSION['error_message'] = "Gagal menambahkan data petugas.";
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
    <title>Rental Mobil Juodge</title>
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
                        <h4 class="card-title">Form Petugas</h4>
                        <p class="text-muted">Input data Petugas baru</p>

                        <!-- Tampilkan pesan error jika ada -->
                        <?php if(isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $_SESSION['error_message']; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php unset($_SESSION['error_message']); // Hapus pesan error setelah ditampilkan ?>
                        <?php endif; ?>

                        <form action="tambahpetugas.php" method="POST">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Lengkap" value="<?php echo isset($_POST['nama']) ? $_POST['nama'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Jabatan" value="<?php echo isset($_POST['jabatan']) ? $_POST['jabatan'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="telp">Nomor Telepon</label>
                                <input type="number" name="telp" id="telp" class="form-control" placeholder="Nomor Telepon" value="<?php echo isset($_POST['telp']) ? $_POST['telp'] : ''; ?>">
                            </div>
                            <div class="">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <a href="petugas.php" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
