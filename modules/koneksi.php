<?php 
    $con = mysqli_connect('localhost', 'root', '', 'rental_mobil', '3306');

    if(mysqli_connect_errno()) {
        echo "Koneksi ke database mengalami kegagalan : " . mysqli_connect_error();
    }
?>  