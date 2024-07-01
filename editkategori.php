<?php
include('modules/koneksi.php');

session_start();

// Ambil pesan error jika ada
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['error_message']); 
unset($_SESSION['success_message']); 


if (isset($_GET['id'])) {
    $kategori_id = $_GET['id'];


    $query_kategori = "SELECT * FROM kategori WHERE id = $kategori_id";
    $result_kategori = mysqli_query($con, $query_kategori);


    if (mysqli_num_rows($result_kategori) == 1) {
        $row = mysqli_fetch_assoc($result_kategori);
    } else {
        $_SESSION['error_message'] = "Kategori tidak ditemukan.";
        header("Location: kategori.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "Parameter ID kategori tidak ditemukan.";
    header("Location: kategori.php");
    exit();
}

try {
    //code...
    if (isset($_POST['submit_edit'])) {
        $nama_kategori = mysqli_real_escape_string($con, $_POST['nama_kategori']);
    
    
        if (empty($nama_kategori)) {
            $_SESSION['error_message'] = "Nama kategori harus diisi.";
        } else {
    
            $query = "UPDATE kategori SET nama = '$nama_kategori' WHERE id = $kategori_id";
    
            if (mysqli_query($con, $query)) {
                $_SESSION['success_message'] = "Kategori berhasil diperbarui.";
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
            $errorMessage = "Data kategori telah digunakan sebelumnya.";
        } 
    }

    if ($errorMessage === null) {
        $errorMessage = "Terjadi kesalahan yang tidak terduga: " . $e->getMessage();
    }

    $_SESSION['error_message'] = $errorMessage;
    header("Location: editkategori.php?id=$kategori_id");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - Rental Mobil Juodge</title>
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
                        <h4 class="card-title">Edit Kategori</h4>
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="<?php echo $row['nama']; ?>" required>
                            </div>
                            <button type="submit" name="submit_edit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('components/scripts.php'); ?>
</body>
</html>
