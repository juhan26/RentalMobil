<?php
include('modules/koneksi.php');
session_start();

$id = $_GET['id'];

$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);

// Ambil data transaksi dari database
$query_transaksi = "SELECT * FROM transaksi WHERE id='$id'";
$result_transaksi = mysqli_query($con, $query_transaksi);
$transaksi = mysqli_fetch_assoc($result_transaksi);

// Ambil data mobil dari database
$query_mobil = "SELECT * FROM mobil";
$result_mobil = mysqli_query($con, $query_mobil);

// Ambil data depot dari database
$query_depot = "SELECT * FROM depot";
$result_depot = mysqli_query($con, $query_depot);

// Ambil data supir dari database
$query_supir = "SELECT * FROM supir";
$result_supir = mysqli_query($con, $query_supir);

// Ambil data petugas dari database
$query_petugas = "SELECT * FROM petugas";
$result_petugas = mysqli_query($con, $query_petugas);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mobil_id = mysqli_real_escape_string($con, $_POST['mobil_id']);
    $depot_id = mysqli_real_escape_string($con, $_POST['depot_id']);
    $supir_id = mysqli_real_escape_string($con, $_POST['supir_id']);
    $petugas_id = mysqli_real_escape_string($con, $_POST['petugas_id']);
    $tanggal_pinjam = mysqli_real_escape_string($con, $_POST['tanggal_pinjam']);
    $tanggal_kembali = mysqli_real_escape_string($con, $_POST['tanggal_kembali']);

    try {
        if (empty($mobil_id) || empty($depot_id) || empty($supir_id) || empty($petugas_id) || empty($tanggal_pinjam) || empty($tanggal_kembali)) {
            throw new Exception("Semua field harus diisi.");
        }

        if (strtotime($tanggal_kembali) <= strtotime($tanggal_pinjam)) {
            throw new Exception("Tanggal kembali harus setelah tanggal pinjam.");
        }

        // Lakukan update data transaksi ke database
        $update_query = "UPDATE transaksi SET mobil_id='$mobil_id', depot_id='$depot_id', supir_id='$supir_id', petugas_id='$petugas_id', tanggal_pinjam='$tanggal_pinjam', tanggal_kembali='$tanggal_kembali' WHERE id='$id'";
        
        if (mysqli_query($con, $update_query)) {
            $_SESSION['success_message'] = "Data transaksi berhasil diupdate.";
            header('Location: transaksi.php');
            exit();
        } else {
            throw new Exception("Terjadi kesalahan saat mengupdate data transaksi: " . mysqli_error($con));
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: edittransaksi.php?id=$id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi</title>
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
                        <h4 class="card-title">Edit Transaksi Penyewaan</h4>
                        <?php if (!empty($error_message)): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        <form action="edittransaksi.php?id=<?php echo $id; ?>" method="POST">
                            <div class="form-group">
                                <label for="mobil_id">Pilih Mobil</label>
                                <select name="mobil_id" id="mobil_id" class="form-control" required>
                                    <option value="">Pilih Mobil</option>
                                    <?php while ($mobil = mysqli_fetch_assoc($result_mobil)): ?>
                                        <option value="<?php echo $mobil['id']; ?>" <?php echo ($transaksi['mobil_id'] == $mobil['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($mobil['nama']." - ".$mobil['no_polisi']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="depot_id">Pilih Depot</label>
                                <select name="depot_id" id="depot_id" class="form-control" required>
                                    <?php while ($depot = mysqli_fetch_assoc($result_depot)): ?>
                                        <option value="<?php echo $depot['id']; ?>" <?php echo ($transaksi['depot_id'] == $depot['id']) ? 'selected' : ''; ?>>
                                            <?php echo $depot['nama']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="supir_id">Pilih Supir</label>
                                <select name="supir_id" id="supir_id" class="form-control" required>
                                    <?php while ($supir = mysqli_fetch_assoc($result_supir)): ?>
                                        <option value="<?php echo $supir['id']; ?>" <?php echo ($transaksi['supir_id'] == $supir['id']) ? 'selected' : ''; ?>>
                                            <?php echo $supir['nama']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="petugas_id">Pilih Petugas</label>
                                <select name="petugas_id" id="petugas_id" class="form-control" required>
                                    <?php while ($petugas = mysqli_fetch_assoc($result_petugas)): ?>
                                        <option value="<?php echo $petugas['id']; ?>" <?php echo ($transaksi['petugas_id'] == $petugas['id']) ? 'selected' : ''; ?>>
                                            <?php echo $petugas['nama']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" value="<?php echo $transaksi['tanggal_pinjam']; ?>" >
                            </div>
                            <div class="form-group">
                                <label for="tanggal_kembali">Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" value="<?php echo $transaksi['tanggal_kembali']; ?>" >
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
