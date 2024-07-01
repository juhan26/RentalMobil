<?php
session_start();

include('modules/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $email = mysqli_real_escape_string($con, $email);

    $query = "SELECT * FROM login WHERE email='$email'";
    $result = mysqli_query($con, $query);
    

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        if ($user && $password) {
            $_SESSION['email'] = $email;
            header("Location: index.php");
            exit();
        } else {
            echo "Email atau password salah";
        }
    } else {
        echo "Gagal melakukan query ke database";
    }
}
?>
