<?php
session_start();

if(!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

if (time() > $_SESSION['expire']) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['role'] ?? '';

$current_page = basename($_SERVER['PHP_SELF']);

if ($user_role === 'admin') {
    $allowed_editor_pages = ['dashboard.php','data-barang.php', 'data-kategori.php', 'data-user.php', 'kelola_transaksi.php', 'laporan_penjualan.php'];
    if (!in_array($current_page, $allowed_editor_pages)) {
        // Jika ketahuan mengakses halaman lain, arahkan ke halaman transaksi atau beri pesan error
        header("Location: admin/dashboard.php"); 
        exit();
    }
}
elseif ($user_role === 'pelanggan') {
    // Pelanggan HANYA boleh mengakses halaman beranda dan riwayat transaksi
    $allowed_viewer_pages = ['beranda.php', 'riwayat_transaksi.php'];
    
    if (!in_array($current_page, $allowed_viewer_pages)) {
        header("Location: pelanggan/beranda.php");
        exit();
    }
}
else {
    // Jika role tidak dikenali, paksa logout
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

?>