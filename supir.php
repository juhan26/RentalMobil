<?php 
    include('modules/koneksi.php');
    session_start();
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
    <style>
        .truncate-text {
            max-width: 200px; /* Sesuaikan lebar maksimum yang diinginkan */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <?php

    try {
        if(isset($_GET['action']) && isset($_GET['id']))
        {
            $id = $_GET['id'];
            $result = mysqli_query($con, "DELETE FROM supir WHERE id='$id'");
        }

    } catch (mysqli_sql_exception $e) { 
        echo "<script>alert('gagal menghapus data karena data masih terkait di tabel yang lain')</script>";
    }
        
        $data = null;
        
        if(isset($_GET['search']))
        {
            $search = mysqli_real_escape_string($con, $_GET['search']);
            $data = mysqli_query($con, "SELECT * FROM supir WHERE nama LIKE '%$search%' OR telp LIKE '%$search%'");
        } else {
            $data = mysqli_query($con, "SELECT * FROM supir");
        }
    ?>
    <div class="wrapper">
        <?php include('components/sidebar.php') ?>
        <div id="content">
            <?php include('components/navbar.php') ?>
            <div id="content-wrapper">
                <?php include('components/alert.php') ?>
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <h4 class="card-title">Data Supir</h4>

                        <?php if(isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success">
                                <a href="" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                                <?php echo $_SESSION['success_message']; ?>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between">
                            <div class="col-sm-2 col-md-4 pl-0">
                                <form action="supir.php" method="GET" class="form-inline">
                                    <input type="text" name="search" id="search" value="<?php echo $_GET['search'] ?? null; ?>" class="form-control form-control-sm" placeholder="Cari..">
                                    <button type="submit" class="btn btn-secondary btn-sm ml-2"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                            <a href="tambahsupir.php" class="btn btn-primary btn-sm">Tambah</a>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table table-flush">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Telepon</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($data) > 0): ?>
                                        <?php while($row = mysqli_fetch_array($data)): ?>
                                            <tr>
                                                <td><?php echo $row['nama']; ?></td>
                                                <td class="truncate-text"><?php echo substr($row['alamat'], 0, 100); ?></td>
                                                <td><?php echo $row['tanggal_lahir']; ?></td>
                                                <td><?php echo $row['telp']; ?></td>
                                                <td>
                                                    <a href="editsupir.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    <a href="supir.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada data.</td>
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
