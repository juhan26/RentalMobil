<?php
include('modules/koneksi.php');
session_start();

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);

try {
    if (isset($_GET['hapus'])) {
        $kategori_id = mysqli_real_escape_string($con, $_GET['hapus']);

        $query_cek_kategori = "SELECT * FROM kategori WHERE id = $kategori_id";
        $result_cek_kategori = mysqli_query($con, $query_cek_kategori);

        if (mysqli_num_rows($result_cek_kategori) == 1) {
            $query_hapus_kategori = "DELETE FROM kategori WHERE id = $kategori_id";
            if (mysqli_query($con, $query_hapus_kategori)) {
                $_SESSION['success_message'] = "Kategori berhasil dihapus.";
            } else {
                $_SESSION['error_message'] = "Error: Gagal menghapus kategori. " . mysqli_error($con);
            }
        } else {
            $_SESSION['error_message'] = "Kategori tidak ditemukan.";
        }

        header("Location: kategori.php");
        exit();
    }
} catch (mysqli_sql_exception $e) {
    $_SESSION['error_message'] = "Gagal menghapus data kategori karena data masih terkait di tabel yang lain.";
    header("Location: kategori.php");
    exit();
}

$query_kategori = "SELECT * FROM kategori";
$result_kategori = mysqli_query($con, $query_kategori);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kategori - Rental Mobil Juodge</title>
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title m-0">Daftar Kategori</h4>
                            <a href="tambahkategori.php" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <div class="table-responsive mt-4">
                            <table class="table table-flush">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Kategori</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($result_kategori)): ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['nama']; ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="editkategori.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm mr-1">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="kategori.php?hapus=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm btn-delete" onclick="return confirm('Anda yakin ingin menghapus kategori ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('components/scripts.php'); ?>
</body>
</html>
