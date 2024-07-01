<?php
include('modules/koneksi.php');

session_start();

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobil_id = mysqli_real_escape_string($con, $_POST['mobil_id']);
    $depot_id = mysqli_real_escape_string($con, $_POST['depot_id']);
    $supir_id = mysqli_real_escape_string($con, $_POST['supir_id']);
    $petugas_id = mysqli_real_escape_string($con, $_POST['petugas_id']);
    $tanggal_pinjam = mysqli_real_escape_string($con, $_POST['tanggal_pinjam']);
    $tanggal_kembali = mysqli_real_escape_string($con, $_POST['tanggal_kembali']);
    $status = 'disewa';

try{
    if (empty($mobil_id) || empty($depot_id) || empty($supir_id) || empty($petugas_id) || empty($tanggal_pinjam) || empty($tanggal_kembali)) {
        $_SESSION['error_message'] = "Semua field harus diisi.";
    } elseif (strtotime($tanggal_pinjam) > strtotime($tanggal_kembali)) {
        $_SESSION['error_message'] = "Tanggal pinjam tidak boleh lebih besar dari tanggal kembali.";
    } else {
        $query = "INSERT INTO transaksi (mobil_id, depot_id, supir_id, petugas_id, tanggal_pinjam, tanggal_kembali, status) 
                  VALUES ('$mobil_id', '$depot_id', '$supir_id', '$petugas_id', '$tanggal_pinjam', '$tanggal_kembali', '$status')";

        if (mysqli_query($con, $query)) {
            $_SESSION['success_message'] = "Transaksi berhasil ditambahkan.";
            header("Location: transaksi.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($con);
        }
    }

    header("Location: tambahtransaksi.php");
    exit();
}catch (mysqli_sql_exception $e) {
    $errorMessage = null;
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        if (strpos($e->getMessage(), 'mobil_id') !== false) {
            $errorMessage = "data mobil telah ada sebelumnya";
        } elseif (strpos($e->getMessage(), 'supir_id') !== false) {
            $errorMessage = "data supir telah ada sebelumnya";
        }
    }

    if ($errorMessage === null) {
        $errorMessage = "An unexpected error occurred: " . $e->getMessage();
    }

    $_SESSION['error_message'] = $errorMessage;
    header("Location: tambahtransaksi.php");
    exit();
}
}

$query_mobil = "SELECT * FROM mobil";
$result_mobil = mysqli_query($con, $query_mobil);

$query_depot = "SELECT * FROM depot";
$result_depot = mysqli_query($con, $query_depot);

$query_supir = "SELECT * FROM supir";
$result_supir = mysqli_query($con, $query_supir);

$query_petugas = "SELECT * FROM petugas";
$result_petugas = mysqli_query($con, $query_petugas);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Mobil Juodge - Penyewaan Mobil</title>
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
                        <h4 class="card-title">Form Penyewaan Mobil</h4>
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="mobil_id">Pilih Mobil</label>
                                <select class="form-control" id="mobil_id" name="mobil_id">
                                    <option value="">Pilih Mobil</option>
                                    <?php while ($row = mysqli_fetch_assoc($result_mobil)): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']." - ".$row['no_polisi']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="depot_id">Pilih Depot</label>
                                <select class="form-control" id="depot_id" name="depot_id">
                                    <option value="">Pilih Depot</option>
                                    <?php while ($row = mysqli_fetch_assoc($result_depot)): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="supir_id">Pilih Supir</label>
                                <select class="form-control" id="supir_id" name="supir_id">
                                    <option value="">Pilih Supir</option>
                                    <?php while ($row = mysqli_fetch_assoc($result_supir)): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="petugas_id">Pilih Petugas</label>
                                <select class="form-control" id="petugas_id" name="petugas_id">
                                    <option value="">Pilih Petugas</option>
                                    <?php while ($row = mysqli_fetch_assoc($result_petugas)): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nama']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                                <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam">
                            </div>
                            <div class="form-group">
                                <label for="tanggal_kembali">Tanggal Kembali</label>
                                <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
