<?php
    // Mendapatkan nama file dari halaman yang sedang diakses
    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav id="sidebar" class="shadow">
    <div class="sidebar-header">
        <h4>Pendataan Rental</h4>
    </div>
    <ul class="list-unstyled components">
        <li class="<?= ($currentPage == 'index.php') ? 'active' : '' ?>">
            <a href="index.php">
                <i class="fas fa-home mr-1"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <p class="mb-0">Master</p>
        <li class="<?= ($currentPage == 'depot.php') ? 'active' : '' ?>">
            <a href="depot.php">
                <i class="fas fa-warehouse mr-2"></i>
                <span>Data Depot</span>
            </a>
        </li>
        <li class="<?= ($currentPage == 'mobil.php') ? 'active' : '' ?>">
            <a href="mobil.php">
                <i class="fas fa-car mr-2"></i>
                <span>Data Mobil</span>
            </a>
        </li>
        <li class="<?= ($currentPage == 'supir.php') ? 'active' : '' ?>">
            <a href="supir.php">
                <i class="far fa-address-card mr-2"></i>
                <span>Data Supir</span>
            </a>
        </li>
        <li class="<?= ($currentPage == 'petugas.php') ? 'active' : '' ?>">
            <a href="petugas.php">
                <i class="far fa-address-card mr-2"></i>
                <span>Data Petugas</span>
            </a>
        </li>
        <li class="<?= ($currentPage == 'kategori.php') ? 'active' : '' ?>">
            <a href="kategori.php">
                <i class="far fa-address-card mr-2"></i>
                <span>Data Kategori</span>
            </a>
        </li>
        <p class="mb-0">Lainnya</p>
        <li class="<?= ($currentPage == 'transaksi.php') ? 'active' : '' ?>">
            <a href="transaksi.php">
                <i class="fas fa-shopping-cart mr-2"></i>
                <span>Transaksi</span>
            </a>
        </li>
    </ul>
</nav>
