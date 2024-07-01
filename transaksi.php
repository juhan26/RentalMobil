<?php
include('modules/koneksi.php');

if (!isset($_SESSION)) {
    session_start();
}

$query = "SELECT t.id, m.nama AS nama_mobil, d.nama AS nama_depot, s.nama AS nama_supir, p.nama AS nama_petugas,
        t.tanggal_pinjam, t.tanggal_kembali, t.status
    FROM transaksi t
    INNER JOIN mobil m ON t.mobil_id = m.id
    INNER JOIN depot d ON t.depot_id = d.id
    INNER JOIN supir s ON t.supir_id = s.id
    INNER JOIN petugas p ON t.petugas_id = p.id
";

$result = mysqli_query($con, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_status'])) {
        $transaksi_id = $_POST['transaksi_id'];
        $status = $_POST['status'];

        $update_query = "UPDATE transaksi SET status = '$status' WHERE id = '$transaksi_id'";

        if (mysqli_query($con, $update_query)) {
            $_SESSION['success_message'] = "Status transaksi berhasil diupdate.";
            header("Location: tambahtransaksi.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($con);
        }
    } elseif (isset($_POST['delete_transaksi'])) {
        $transaksi_id = $_POST['transaksi_id'];

        $delete_query = "DELETE FROM transaksi WHERE id = '$transaksi_id'";

        if (mysqli_query($con, $delete_query)) {
            $_SESSION['success_message'] = "Transaksi berhasil dihapus.";
            header("Location: transaksi.php");
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Mobil Juodge - Data Transaksi</title>
    <?php include('components/dependencies.php'); ?>
    <style>
        .btn-delete {
            margin-right: 5px; 
        }
    </style>
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
                            <h4 class="card-title m-0">Data Transaksi Penyewaan</h4>
                            <a href="tambahtransaksi.php" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                        <?php if(isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success">
                                <a href="" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                                <?php echo $_SESSION['success_message']; ?>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Mobil</th>
                                        <th>Depot</th>
                                        <th>Supir</th>
                                        <th>Petugas</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['nama_mobil']; ?></td>
                                            <td><?php echo $row['nama_depot']; ?></td>
                                            <td><?php echo $row['nama_supir']; ?></td>
                                            <td><?php echo $row['nama_petugas']; ?></td>
                                            <td><?php echo $row['tanggal_pinjam']; ?></td>
                                            <td><?php echo $row['tanggal_kembali']; ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="edittransaksi.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="display: inline-block;">
                                                        <input type="hidden" name="transaksi_id" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" name="delete_transaksi" class="btn btn-danger btn-sm btn-delete ml-1" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
</body>
</html>
