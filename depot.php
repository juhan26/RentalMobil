    <?php
    
    include('modules/koneksi.php');
    session_start();


try {
    //code...
    if(isset($_GET['action']) && isset($_GET['id']))
            {
                $id = $_GET['id'];

                $result = mysqli_query($con, "DELETE FROM depot WHERE id='$id'");
            }
        
        if(isset($_GET['search'])) {
            $search = mysqli_real_escape_string($con, $_GET['search']);
            $query = "SELECT * FROM depot WHERE nama LIKE '%$search%' OR alamat LIKE '%$search%'";
        } else {
            $query = "SELECT * FROM depot";
        }

        $result = mysqli_query($con, $query);

        if (!$result) {
            die("Query Error: " . mysqli_error($con));
        }
} catch (mysqli_sql_exception $e) { 
    echo "<script>alert('gagal menghapus data karena data masih terkait di tabel yang lain')
    window.location.href= 'depot.php'
    </script>";
}
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rental Mobil Juodge - Data Depot</title>
        <?php include('components/dependencies.php'); ?>
    </head>

    <body>
        <style>
        .truncate-text {
            max-width: 200px; 
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        </style>
        <div class="wrapper">
            <?php include('components/sidebar.php'); ?>
            <div id="content">
                <?php include('components/navbar.php'); ?>
                <div id="content-wrapper">
                    <div class="card shadow-sm rounded">
                        <div class="card-body">
                            <h4 class="card-title">Data Depot</h4>

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
                                    <form action="depot.php" method="GET" class="form-inline">
                                        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="form-control form-control-sm" placeholder="Cari..">
                                        <button type="submit" class="btn btn-secondary btn-sm ml-2"><i class="fas fa-search"></i></button>
                                    </form>
                                </div>
                                <a href="tambahdepot.php" class="btn btn-primary btn-sm">Tambah</a>
                            </div>
                            <div class="table-responsive mt-4">
                                <table class="table table-flush">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Alamat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                                <td class="truncate-text" ><?php echo substr($row['alamat'], 0, 100); ?></td>
                                                <td>
                                                    <a href="editdepot.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                     <a href="depot.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if(mysqli_num_rows($result) == 0) { ?>
                                            <tr>
                                                <td colspan="3" class="text-center">Belum ada data.</td>
                                            </tr>
                                        <?php } ?>
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
