<?php 
include('modules/koneksi.php');
session_start();

// Inisialisasi variabel untuk menyimpan nilai input
$nama = '';
$alamat = '';
$tanggal_lahir = '';
$telp = '';
$supir_id = null;

$is_edit = isset($_GET['id']);
if ($is_edit) {
    $supir_id = $_GET['id'];

    $query_select = "SELECT * FROM supir WHERE id = ?";
    $stmt = mysqli_prepare($con, $query_select);
    mysqli_stmt_bind_param($stmt, "i", $supir_id);
    mysqli_stmt_execute($stmt);
    $result_select = mysqli_stmt_get_result($stmt);
    
    if ($result_select && mysqli_num_rows($result_select) > 0) {
        $supir_data = mysqli_fetch_assoc($result_select);
        $nama = $supir_data['nama'];
        $alamat = $supir_data['alamat'];
        $tanggal_lahir = $supir_data['tanggal_lahir'];
        $telp = $supir_data['telp'];
    } else {
        $_SESSION['error_message'] = "Gagal mengambil data supir.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $telp = $_POST['telp'];

    $today = date("Y-m-d");

    if (empty($nama) || empty($alamat) || empty($tanggal_lahir) || empty($telp)) {
        $_SESSION['error_message'] = "Silakan lengkapi semua field yang diperlukan.";
    } else if ($tanggal_lahir > $today) {
        $_SESSION['error_message'] = "Tanggal lahir tidak boleh lebih dari hari ini.";
    } else {
        if (!preg_match('/^\d{10,12}$/', $telp)) {
            $_SESSION['error_message'] = "Nomor Telepon harus terdiri dari 10 sampai 12 angka.";
        } else {
            $query_check_telp = "SELECT * FROM supir WHERE telp = ? AND id != ?";
            $stmt_check_telp = mysqli_prepare($con, $query_check_telp);
            mysqli_stmt_bind_param($stmt_check_telp, "si", $telp, $supir_id);
            mysqli_stmt_execute($stmt_check_telp);
            $result_check_telp = mysqli_stmt_get_result($stmt_check_telp);

            if ($result_check_telp && mysqli_num_rows($result_check_telp) > 0) {
                $_SESSION['error_message'] = "Nomor Telepon '$telp' telah digunakan oleh supir lain.";
            } else {
                if ($is_edit) {
                    $query_update = "UPDATE supir SET nama = ?, alamat = ?, tanggal_lahir = ?, telp = ? WHERE id = ?";
                    $stmt_update = mysqli_prepare($con, $query_update);
                    mysqli_stmt_bind_param($stmt_update, "ssssi", $nama, $alamat, $tanggal_lahir, $telp, $supir_id);
                    $result_update = mysqli_stmt_execute($stmt_update);

                    if ($result_update) {
                        $_SESSION['success_message'] = "Data supir berhasil diperbarui!";
                        header('Location: supir.php');
                        exit();
                    } else {
                        $_SESSION['error_message'] = "Gagal memperbarui data supir.";
                        error_log(mysqli_error($con));
                    }
                } else {
                    try {
                        $query_insert = "INSERT INTO supir (nama, alamat, tanggal_lahir, telp) VALUES (?, ?, ?, ?)";
                        $stmt_insert = mysqli_prepare($con, $query_insert);
                        mysqli_stmt_bind_param($stmt_insert, "ssss", $nama, $alamat, $tanggal_lahir, $telp);
                        $result_insert = mysqli_stmt_execute($stmt_insert);
    
                        if ($result_insert) {
                            $_SESSION['success_message'] = "Data supir berhasil ditambahkan!";
                            header('Location: supir.php');
                            exit();
                        } else {
                            $_SESSION['error_message'] = "Gagal menambahkan data supir.";
                            error_log(mysqli_error($con));
                        }
                    } catch (mysqli_sql_exception $e) {
                        $errorMessage = null;
                        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                            if (strpos($e->getMessage(), 'telp') !== false) {
                                $errorMessage = "Nomor telepon telah terdaftar";
                            } 
                        }
                    
                        if ($errorMessage === null) {
                            $errorMessage = "An unexpected error occurred: " . $e->getMessage();
                        }
                    
                        $_SESSION['error_message'] = $errorMessage;
                        header("Location: tambahsupir.php");
                        exit();
                    }
                }
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
    <title>Rental Mobil Juodge - <?php echo $is_edit ? 'Edit Supir' : 'Tambah Supir'; ?></title>
    <?php include('components/dependencies.php'); ?>
    <link rel="stylesheet" href="vendor/css/bootstrap-datepicker.min.css">
</head>
<body>
    <div class="wrapper">
        <?php include('components/sidebar.php') ?>
        <div id="content">
            <?php include('components/navbar.php') ?>
            <div id="content-wrapper">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <h4 class="card-title">Form Supir</h4>
                        <p class="text-muted"><?php echo $is_edit ? 'Edit data supir' : 'Pendaftaran supir baru'; ?></p>
                        
                        <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <a href="" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </a>
                            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>

                        <form id="supirForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?><?php echo $is_edit ? '?id=' . $supir_id : ''; ?>" method="POST">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama lengkap" value="<?php echo htmlspecialchars($nama); ?>">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" cols="30" rows="5" class="form-control"><?php echo htmlspecialchars($alamat); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control datepicker" placeholder="Tanggal Lahir" autocomplete="off" value="<?php echo htmlspecialchars($tanggal_lahir); ?>">
                            </div>
                            <div class="form-group">
                                <label for="telp">No. Handphone</label>
                                <input type="text" name="telp" id="telp" class="form-control" placeholder="Nomor handphone" value="<?php echo htmlspecialchars($telp); ?>">
                            </div>
                            <div class="">
                                <input type="submit" value="<?php echo $is_edit ? 'Update' : 'Submit'; ?>" class="btn btn-primary">
                                <a href="supir.php" type="button" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                endDate: new Date()  // Prevent future dates
            });

            $('#supirForm').on('submit', function(e) {
                var tanggalLahir = new Date($('#tanggal_lahir').val());
                var today = new Date();
                if (tanggalLahir > today) {
                    e.preventDefault();
                    alert('Tanggal lahir tidak boleh lebih dari hari ini.');
                }
            });
        });
    </script>
</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Mobil Juodge - <?php echo $is_edit ? 'Edit Supir' : 'Tambah Supir'; ?></title>
    <?php include('components/dependencies.php'); ?>
    <link rel="stylesheet" href="vendor/css/bootstrap-datepicker.min.css">
</head>
<body>
    <div class="wrapper">
        <?php include('components/sidebar.php') ?>
        <div id="content">
            <?php include('components/navbar.php') ?>
            <div id="content-wrapper">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <h4 class="card-title">Form Supir</h4>
                        <p class="text-muted"><?php echo $is_edit ? 'Edit data supir' : 'Pendaftaran supir baru'; ?></p>
                        
                        <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <a href="" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </a>
                            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>

                        <form id="supirForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?><?php echo $is_edit ? '?id=' . $supir_id : ''; ?>" method="POST">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama lengkap" value="<?php echo htmlspecialchars($nama); ?>">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" cols="30" rows="5" class="form-control"><?php echo htmlspecialchars($alamat); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control datepicker" placeholder="Tanggal Lahir" autocomplete="off" value="<?php echo htmlspecialchars($tanggal_lahir); ?>">
                            </div>
                            <div class="form-group">
                                <label for="telp">No. Handphone</label>
                                <input type="text" name="telp" id="telp" class="form-control" placeholder="Nomor handphone" value="<?php echo htmlspecialchars($telp); ?>">
                            </div>
                            <div class="">
                                <input type="submit" value="<?php echo $is_edit ? 'Update' : 'Submit'; ?>" class="btn btn-primary">
                                <a href="supir.php" type="button" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                endDate: new Date()  // Prevent future dates
            });

            $('#supirForm').on('submit', function(e) {
                var tanggalLahir = new Date($('#tanggal_lahir').val());
                var today = new Date();
                if (tanggalLahir > today) {
                    e.preventDefault();
                    alert('Tanggal lahir tidak boleh lebih dari hari ini.');
                }
            });
        });
    </script>
</body>
</html>
