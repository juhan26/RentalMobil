<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('components/dependencies.php'); ?>
    <title>Login</title>
    <style>
        body {
            background-color: #f5f5f5;
        }

        h1 {
            color: #505050;
        }
    </style>
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col d-flex align-items-center bg-white">
                <div class="p-5 w-100">
                    <div class="bg-white d-flex flex-column pt-4 pb-0">
                        <h1>Login</h1>
                        <p class="text-muted text-sm">Silahkan login untuk melanjutkan</p>
                    </div>
                    <div class="pt-4">
                        <form action="login_process.php" method="POST">
                            <div class="form-group">
                                <label for="email" class="text-muted">Email</label><br>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email kamu" required>
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-muted">Password</label><br>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password kamu" required>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>
                        <div class="mt-5 d-flex flex-column text-center">
                            <p class="text-muted text-sm">Crafted by Juhan</p>
                            <p class="text-muted text-sm">© <?php echo date('Y'); ?> RentalMobil by Juhan </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col d-none d-md-flex d-lg-flex justify-content-center">
                <img src="assets/images/car.svg" alt="" class="w-75">
            </div>
        </div>
    </div>
</body>

</html>
