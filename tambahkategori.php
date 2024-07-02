<?php
include('modules/koneksi.php');

session_start();


$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['error_message']);
unset($_SESSION['success_message']); 

try {

    if (isset($_POST['submit_tambah'])) {
        $nama_kategori = mysqli_real_escape_string($con, $_POST['nama_kategori']);
    
    
        if (empty($nama_kategori)) {
            $_SESSION['error_message'] = "Nama kategori harus diisi.";
        } else {
    
            $query = "INSERT INTO kategori (nama) VALUES ('$nama_kategori')";
    
            if (mysqli_query($con, $query)) {
                $_SESSION['success_message'] = "Kategori berhasil ditambahkan.";
                header("Location: kategori.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Error: " . mysqli_error($con);
            }
        }
    }
} catch (mysqli_sql_exception $e) {
    $errorMessage = null;
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        if (strpos($e->getMessage(), 'nama') !== false) {
            $errorMessage = "data kategori telah ada sebelumnya";
        }
    }

    if ($errorMessage === null) {
        $errorMessage = "An unexpected error occurred: " . $e->getMessage();
    }
    $_SESSION['error_message'] = $errorMessage;
    header("Location: tambahkategori.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - Rental Mobil Juodge</title>
    <?php include('components/dependencies.php'); ?>
</head>
<body>
    <div class="wrapper">
        <?php include('components/sidebar.php'); ?>
        <div id="content">
            <?php include('components/navbar.php'); ?>
            <div id="content-wrapper">
                <?php include('components/alert.php'); ?>
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <h4 class="card-title">Tambah Kategori</h4>
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" >
                            </div>
                            <button type="submit" name="submit_tambah" class="btn btn-primary">Tambah Kategori</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('components/scripts.php'); ?>
</body>
</html>
