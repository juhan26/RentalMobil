<?php
include('modules/koneksi.php');
session_start();

if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    $alamat = mysqli_real_escape_string($con, $_POST['alamat']);

    // Validasi jika ada field yang kosong
    if (empty($nama) || empty($alamat)) {
        $_SESSION['error_message'] = "Semua field harus diisi.";
    } else {
        $query = "INSERT INTO depot (nama, alamat) VALUES ('$nama', '$alamat')";
        if (mysqli_query($con, $query)) {
            $_SESSION['success_message'] = "Data depot berhasil ditambahkan.";
            header("Location: depot.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan data depot.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Depot</title>
    <?php include('components/dependencies.php'); ?>
</head>

<body>
    <div class="wrapper">
        <?php include('components/sidebar.php'); ?>
        <div id="content">
            <?php include('components/navbar.php'); ?>
            <div id="content-wrapper">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <h4 class="card-title">Tambah Depot</h4>
                        <form action="tambahdepot.php" method="POST">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control"></textarea>
                            </div>
                            <div>
                                <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                                <a href="depot.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger mt-2">
                                <?php echo $_SESSION['error_message']; ?>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
