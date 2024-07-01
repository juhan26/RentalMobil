<?php
include('modules/koneksi.php');
session_start();

// Inisialisasi variabel untuk nilai default
$id_supir = '';
$nama = '';
$alamat = '';
$tanggal_lahir = '';
$telp = '';

// Ambil data supir berdasarkan ID untuk diedit
if (isset($_GET['id'])) {
    $id_supir = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM supir WHERE id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_supir);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nama = $row['nama'];
        $alamat = $row['alamat'];
        $tanggal_lahir = $row['tanggal_lahir'];
        $telp = $row['telp'];
    } else {
        $_SESSION['error_message'] = "Data supir tidak ditemukan.";
        header('location: supir.php');
        exit();
    }
}

try {

    if (isset($_POST['update'])) {
        $id = mysqli_real_escape_string($con, $_POST['id']);
        $nama = mysqli_real_escape_string($con, $_POST['nama']);
        $alamat = mysqli_real_escape_string($con, $_POST['alamat']);
        $tanggal_lahir = mysqli_real_escape_string($con, $_POST['tanggal_lahir']);
        $telp = mysqli_real_escape_string($con, $_POST['telp']);
    
    
        if (empty($nama) || empty($alamat) || empty($tanggal_lahir) || empty($telp)) {
            $_SESSION['error_message'] = "Silakan lengkapi semua field yang diperlukan.";
        } else {
    
            $query_update = "UPDATE supir SET nama=?, alamat=?, tanggal_lahir=?, telp=? WHERE id=?";
            $stmt_update = mysqli_prepare($con, $query_update);
            mysqli_stmt_bind_param($stmt_update, "sssii", $nama, $alamat, $tanggal_lahir, $telp, $id);
    
            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['success_message'] = "Data supir berhasil diperbarui.";
                header('location: supir.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui data supir.";
            }
        }
    }
} catch (mysqli_sql_exception $e) {
    $errorMessage = null;
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        if (strpos($e->getMessage(), 'telp') !== false) {
            $errorMessage = "Nomor telepon supir telah digunakan sebelumnya.";
        } 
    }

    if ($errorMessage === null) {
        $errorMessage = "Terjadi kesalahan yang tidak terduga: " . $e->getMessage();
    }

    $_SESSION['error_message'] = $errorMessage;
    header("Location: editsupir.php?id=$id");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Mobil Juodge - Edit Supir</title>
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
                        <h4 class="card-title">Form Edit Supir</h4>
                        <p class="text-muted">Edit data Supir</p>

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

                        <form action="editsupir.php?id=<?php echo htmlspecialchars($id_supir, ENT_QUOTES); ?>" method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id_supir, ENT_QUOTES); ?>">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($nama, ENT_QUOTES); ?>" >
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" cols="30" rows="5" class="form-control"><?php echo htmlspecialchars($alamat, ENT_QUOTES); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" placeholder="Tanggal Lahir" value="<?php echo htmlspecialchars($tanggal_lahir, ENT_QUOTES); ?>" >
                            </div>
                            <div class="form-group">
                                <label for="telp">Nomor Telepon</label>
                                <input type="tel" name="telp" id="telp" class="form-control" placeholder="Nomor Telepon" value="<?php echo htmlspecialchars($telp, ENT_QUOTES); ?>" >
                            </div>
                            <div class="">
                                <button type="submit" name="update" class="btn btn-primary">Submit</button>
                                <a href="supir.php" class="btn btn-secondary">Back</a>
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