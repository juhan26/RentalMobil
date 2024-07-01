<?php 
    include('modules/koneksi.php');
    
    session_start();


    $id = '';
    $nama = '';
    $jabatan = '';
    $telp = '';


    if(isset($_GET['id']))  
    {
        $id_petugas = mysqli_real_escape_string($con, $_GET['id']);
        $query = "SELECT * FROM petugas WHERE id = ?";
        if ($stmt = mysqli_prepare($con, $query)) {
            mysqli_stmt_bind_param($stmt, "s", $id_petugas);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $id = $row['id'];
                $nama = $row['nama'];
                $jabatan = $row['jabatan'];
                $telp = $row['telp'];
            } else {
                $_SESSION['error_message'] = "Data petugas tidak ditemukan.";
                header('Location: petugas.php');
                exit();
            }
        }
    }


    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = mysqli_real_escape_string($con, $_POST['id']);
        $nama = mysqli_real_escape_string($con, $_POST['nama']);
        $jabatan = mysqli_real_escape_string($con, $_POST['jabatan']);
        $telp = mysqli_real_escape_string($con, $_POST['nomor_telepon']);


        if(empty($nama) || empty($jabatan) || empty($telp)) {
            $_SESSION['error_message'] = "Semua field harus diisi.";
            header('Location: editpetugas.php?id=' . $id);
            exit();
        } else {
            $query_check = "SELECT * FROM petugas WHERE telp = ? AND id != ?";
            if ($stmt = mysqli_prepare($con, $query_check)) {
                mysqli_stmt_bind_param($stmt, "ss", $telp, $id);
                mysqli_stmt_execute($stmt);
                $result_check = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result_check) > 0) {

                    $_SESSION['error_message'] = "Nomor telepon '$telp' telah digunakan oleh petugas lain.";
                    header('Location: editpetugas.php?id=' . $id);
                    exit();
                } else {
                    // Update data petugas
                    $query_update = "UPDATE petugas SET nama = ?, jabatan = ?, telp = ? WHERE id = ?";
                    if ($stmt = mysqli_prepare($con, $query_update)) {
                        mysqli_stmt_bind_param($stmt, "ssss", $nama, $jabatan, $telp, $id);
                        $result = mysqli_stmt_execute($stmt);

                        if ($result) {
                            $_SESSION['success_message'] = "Data petugas berhasil diperbarui.";
                            header('Location: petugas.php');
                            exit();
                        } else {
                            $_SESSION['error_message'] = "Gagal memperbarui data petugas.";
                            header('Location: editpetugas.php?id=' . $id);
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
    <title>Rental Mobil Juodge</title>
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
                        <h4 class="card-title">Form Petugas</h4>
                        <p class="text-muted">Edit data Petugas</p>
                        <form action="editpetugas.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama" value="<?php echo htmlspecialchars($nama); ?>">
                            </div>
                            <div class="form-group">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Jabatan" value="<?php echo htmlspecialchars($jabatan); ?>" >
                            </div>
                            <div class="form-group">
                                <label for="nomor_telepon">Nomor Telepon</label>
                                <input type="number" name="nomor_telepon" id="nomor_telepon" class="form-control" placeholder="Nomor Telepon" value="<?php echo htmlspecialchars($telp); ?>" >
                            </div>
                            <div class="">
                                <input type="submit" value="Submit" class="btn btn-primary">
                                <a href="petugas.php" type="button" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                        <?php if(isset($_SESSION['error_message'])): ?>
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
