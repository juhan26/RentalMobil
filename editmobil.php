<?php
include('modules/koneksi.php');
session_start();


$id_mobil = '';
$no_polisi = '';
$nama = '';
$kategori_id = '';
$warna = '';
$tahun = '';


if (isset($_GET['id'])) {
    $id_mobil = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM mobil WHERE id=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_mobil);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $no_polisi = $row['no_polisi'];
        $nama = $row['nama'];
        $kategori_id = $row['kategori_id'];
        $warna = $row['warna'];
        $tahun = $row['tahun'];
    } else {
        $_SESSION['error_message'] = "Data mobil tidak ditemukan.";
        header('location: mobil.php');
        exit();
    }
}


$old_no_polisi = $no_polisi;


if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $no_polisi = mysqli_real_escape_string($con, $_POST['no_polisi']);
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    $kategori_id = mysqli_real_escape_string($con, $_POST['kategori_id']);
    $warna = mysqli_real_escape_string($con, $_POST['warna']);
    $tahun = mysqli_real_escape_string($con, $_POST['tahun']);


    if (empty($no_polisi) || empty($nama) || empty($kategori_id) || empty($warna) || empty($tahun)) {
        $_SESSION['error_message'] = "Silakan lengkapi semua field yang diperlukan.";
    } else {

        if ($no_polisi != $old_no_polisi) {
            $query_check = "SELECT * FROM mobil WHERE no_polisi = ? AND id != ?";
            $stmt_check = mysqli_prepare($con, $query_check);
            mysqli_stmt_bind_param($stmt_check, "si", $no_polisi, $id);
            mysqli_stmt_execute($stmt_check);
            $result_check = mysqli_stmt_get_result($stmt_check);

            if (mysqli_num_rows($result_check) > 0) {
                $_SESSION['error_message'] = "Nomor Polisi '$no_polisi' telah digunakan oleh mobil lain.";
            } else {

                $query_update = "UPDATE mobil SET no_polisi=?, nama=?, warna=?, tahun=?, kategori_id=? WHERE id=?";
                $stmt_update = mysqli_prepare($con, $query_update);
                mysqli_stmt_bind_param($stmt_update, "ssssii", $no_polisi, $nama, $warna, $tahun, $kategori_id, $id);

                if (mysqli_stmt_execute($stmt_update)) {
                    $_SESSION['success_message'] = "Data mobil berhasil diperbarui.";
                    header('location: mobil.php');
                    exit();
                } else {
                    $_SESSION['error_message'] = "Gagal memperbarui data mobil.";
                }
            }
        } else {
            $query_update = "UPDATE mobil SET nama=?, warna=?, tahun=?, kategori_id=? WHERE id=?";
            $stmt_update = mysqli_prepare($con, $query_update);
            mysqli_stmt_bind_param($stmt_update, "sssii", $nama, $warna, $tahun, $kategori_id, $id);

            if (mysqli_stmt_execute($stmt_update)) {
                $_SESSION['success_message'] = "Data mobil berhasil diperbarui.";
                header('location: mobil.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal memperbarui data mobil.";
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
                    <h4 class="card-title">Form Mobil</h4>
                    <p class="text-muted">Edit data mobil</p>
                    <form action="editmobil.php?id=<?php echo htmlspecialchars($id_mobil, ENT_QUOTES); ?>" method="POST">
                        <input type="hidden" name="id"
                               value="<?php echo htmlspecialchars($id_mobil, ENT_QUOTES); ?>">
                        <div class="form-group">
                            <label for="no_polisi">Nomor Plat</label>
                            <input type="text" name="no_polisi" id="no_polisi" class="form-control"
                                   value="<?php echo htmlspecialchars($no_polisi, ENT_QUOTES); ?>"
                                   placeholder="Nomor Polisi">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Mobil</label>
                            <input type="text" name="nama" id="nama" class="form-control"
                                   value="<?php echo htmlspecialchars($nama, ENT_QUOTES); ?>" placeholder="Nama mobil">
                        </div>
                        <div class="form-group">
                            <label for="kategori_id">Kategori Mobil</label>
                            <select name="kategori_id" id="kategori_id" class="form-control">
                                <?php
                                $query_kategori = "SELECT * FROM kategori";
                                $result_kategori = mysqli_query($con, $query_kategori);
                                while ($row_kategori = mysqli_fetch_assoc($result_kategori)) {
                                    $selected = ($row_kategori['id'] == $kategori_id) ? "selected" : "";
                                    echo '<option value="' . $row_kategori['id'] . '" ' . $selected . '>' . htmlspecialchars($row_kategori['nama'], ENT_QUOTES) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="warna">Warna</label>
                            <input type="text" name="warna" id="warna" class="form-control"
                                   value="<?php echo htmlspecialchars($warna, ENT_QUOTES); ?>" placeholder="Warna">
                        </div>
                        <div class="form-group">
                            <label for="tahun">Tahun</label>
                            <input type="number" name="tahun" id="tahun" class="form-control"
                                   value="<?php echo htmlspecialchars($tahun, ENT_QUOTES); ?>" placeholder="Tahun">
                        </div>
                        <div class="">
                            <input type="submit" name="update" value="Submit" class="btn btn-primary">
                            <a href="mobil.php" type="button" class="btn btn-secondary">Back</a>
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