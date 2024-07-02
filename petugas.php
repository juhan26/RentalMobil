<?php 
    include('modules/koneksi.php');
    session_start();

    try {
        if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $id = mysqli_real_escape_string($con, $_GET['id']);
            $result = mysqli_query($con, "DELETE FROM petugas WHERE id='$id'");
            if($result) {
                $_SESSION['success_message'] = "Data petugas berhasil dihapus.";
            } else {
                $_SESSION['error_message'] = "Gagal menghapus data petugas.";
            }
            header('Location: petugas.php');
            exit();
        }
    } catch (mysqli_sql_exception $e) { 
        $_SESSION['error_message'] = "Gagal menghapus data petugas karena data masih terkait di tabel yang lain.";
        header("Location: petugas.php");
        exit();
    }

    // Query data petugas
    $query = "SELECT * FROM petugas";
    if(isset($_GET['search'])) {
        $search = mysqli_real_escape_string($con, $_GET['search']);
        $query = "SELECT * FROM petugas WHERE nama LIKE '%$search%' OR telp LIKE '%$search%' OR jabatan LIKE '%$search%'";
    }
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query Error: " . mysqli_error($con));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Mobil Juodge - Data Petugas</title>
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
                        <h4 class="card-title">Data Petugas</h4>

                        <?php if(isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success">
                                <a href="" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                                <?php echo $_SESSION['success_message']; ?>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>

                        <?php if(isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger">
                                <a href="" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                                <?php echo $_SESSION['error_message']; ?>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between">
                            <div class="col-sm-2 col-md-4 pl-0">
                                <form action="petugas.php" method="GET" class="form-inline">
                                    <input type="text" name="search" id="search" value="<?php echo $_GET['search'] ?? null; ?>" class="form-control form-control-sm" placeholder="Cari..">
                                    <button type="submit" class="btn btn-secondary btn-sm ml-2"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                            <a href="tambahpetugas.php" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table table-flush">
                                <thead>
                                    <tr>
                                        <th>Nama Lengkap</th>
                                        <th>Jabatan</th>
                                        <th>Telp</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($result) > 0): ?>
                                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?php echo $row['nama']; ?></td>
                                                <td><?php echo $row['jabatan']; ?></td>
                                                <td><?php echo $row['telp']; ?></td>
                                                <td>
                                                    <a href="editpetugas.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="petugas.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">Belum ada data</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</body>
</html>
