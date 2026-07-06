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
    $allowed_editor_pages = ['index.php','form_barang.php', 'form_kategori.php'];
    if (!in_array($current_page, $allowed_editor_pages)) {
        // Jika ketahuan mengakses halaman lain, arahkan ke halaman transaksi atau beri pesan error
        header("Location: Transaksi_form.php"); 
        exit();
    }
}
elseif ($user_role === 'pelanggan') {
    // Viewer HANYA boleh mengakses index.php dan User.php
    $allowed_viewer_pages = ['index.php', 'Transaksi_user.php'];
    
    if (!in_array($current_page, $allowed_viewer_pages)) {
        header("Location: Transaksi_user.php");
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