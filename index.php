<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Beranda</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Role: <strong><?php echo $_SESSION['role']; ?></strong></p>
    
    <nav>
            <div class="nav-tombol">
            <a href="index.php" class="tombol">Beranda</a>
            
            <?php if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'editor'): ?>
                <a href="editor.php" class="tombol">Halaman Editor</a>
            <?php endif; ?>
            
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="admin.php" class="tombol">Halaman Admin</a>
            <?php endif; ?>
            
            <a href="logout.php" class="tombol-logout">Logout</a>
            </div>
    </nav>

    <hr>
    <p>*Dapat dilihat semua user</p>
</body>
</html>