<?php
session_start();

require_once __DIR__ . '/../class/Transaksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'pelanggan') {
    header('Location: ../admin/dashboard.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$transaksi = new Transaksi();
$result = $transaksi->readAllByIdUser((int) $_SESSION['user_id']);

$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka)
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

function metodePembayaran($kode)
{
    $data = [
        '1' => 'Transfer Bank',
        '2' => 'QRIS',
        '3' => 'COD'
    ];

    return $data[(string) $kode] ?? 'Tidak diketahui';
}

function statusClass($status)
{
    $status = strtolower((string) $status);

    if ($status === 'pending') {
        return 'status-pending';
    }

    if ($status === 'diproses') {
        return 'status-process';
    }

    if ($status === 'dikirim') {
        return 'status-send';
    }

    if ($status === 'selesai') {
        return 'status-success';
    }

    if ($status === 'dibatalkan') {
        return 'status-cancel';
    }

    return 'status-default';
}

function statusText($status)
{
    $status = strtolower((string) $status);

    $data = [
        'pending' => 'Menunggu Konfirmasi',
        'diproses' => 'Diproses',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan'
    ];

    return $data[$status] ?? ucfirst($status);
}

$placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 400 400'%3E%3Crect width='400' height='400' fill='%23f3f4f6'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dominant-baseline='middle' font-family='Arial' font-size='18' fill='%236b7280'%3ETidak ada gambar%3C/text%3E%3C/svg%3E";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f5f5f5;
            color: #111;
        }

        .navbar {
            width: 100%;
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .navbar-inner {
            max-width: 1100px;
            margin: auto;
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .logo {
            font-size: 22px;
            font-weight: 800;
            color: #111;
            text-decoration: none;
        }

        .logo span {
            color: #555;
        }

        .nav-actions {
            display: flex;
            gap: 10px;
        }

        .nav-btn {
            text-decoration: none;
            border: 1px solid #111;
            background: #111;
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
        }

        .nav-btn.secondary {
            background: #fff;
            color: #111;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 26px 20px 50px;
        }

        .page-header {
            background: #111;
            color: #fff;
            border-radius: 18px;
            padding: 30px;
            margin-bottom: 22px;
        }

        .page-header h1 {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #d4d4d4;
            line-height: 1.6;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 22px;
        }

        .summary-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 14px;
            padding: 18px;
        }

        .summary-card small {
            color: #666;
            display: block;
            margin-bottom: 8px;
        }

        .summary-card strong {
            font-size: 24px;
        }

        .section-title {
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title h2 {
            font-size: 22px;
        }

        .transaction-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .transaction-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 16px;
            padding: 16px;
        }

        .transaction-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid #eee;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }

        .transaction-code {
            font-weight: 800;
            color: #111;
        }

        .transaction-date {
            color: #666;
            font-size: 13px;
            margin-top: 4px;
        }

        .status {
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-pending {
            background: #f3f3f3;
            color: #111;
        }

        .status-process {
            background: #eeeeee;
            color: #111;
        }

        .status-send {
            background: #e8e8e8;
            color: #111;
        }

        .status-success {
            background: #111;
            color: #fff;
        }

        .status-cancel {
            background: #f1f1f1;
            color: #777;
            text-decoration: line-through;
        }

        .status-default {
            background: #f3f3f3;
            color: #555;
        }

        .transaction-body {
            display: grid;
            grid-template-columns: 90px 1fr auto;
            gap: 16px;
            align-items: center;
        }

        .product-image {
            width: 90px;
            height: 90px;
            border-radius: 12px;
            background: #f3f3f3;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .product-name {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .product-detail {
            color: #555;
            font-size: 14px;
            line-height: 1.7;
        }

        .transaction-total {
            text-align: right;
        }

        .transaction-total small {
            display: block;
            color: #666;
            margin-bottom: 5px;
        }

        .transaction-total strong {
            font-size: 18px;
        }

        .transaction-actions {
            margin-top: 14px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            border: none;
            border-radius: 9px;
            padding: 10px 14px;
            font-weight: 800;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-cancel {
            background: #fff;
            color: #111;
            border: 1px solid #111;
        }

        .btn-cancel:hover {
            background: #111;
            color: #fff;
        }

        .btn-disabled {
            background: #e5e5e5;
            color: #777;
            cursor: not-allowed;
        }

        .empty-state {
            background: #fff;
            border: 1px dashed #cfcfcf;
            border-radius: 16px;
            padding: 45px 20px;
            text-align: center;
            color: #666;
        }

        .empty-state h3 {
            color: #111;
            margin-bottom: 8px;
        }

        @media (max-width: 768px) {
            .summary {
                grid-template-columns: 1fr;
            }

            .transaction-body {
                grid-template-columns: 76px 1fr;
            }

            .product-image {
                width: 76px;
                height: 76px;
            }

            .transaction-total {
                grid-column: 1 / -1;
                text-align: left;
                padding-top: 12px;
                border-top: 1px solid #eee;
            }

            .transaction-top {
                align-items: flex-start;
                flex-direction: column;
            }

            .transaction-actions {
                justify-content: stretch;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <div class="navbar-inner">
        <a href="beranda.php" class="logo">Nada<span>Musik</span></a>

        <div class="nav-actions">
            <a href="beranda.php" class="nav-btn secondary">Katalog</a>
            <a href="../logout.php" class="nav-btn">Logout</a>
        </div>
    </div>
</header>

<main class="container">

    <section class="page-header">
        <h1>Riwayat Transaksi</h1>
        <p>Lihat daftar pesanan Anda dan batalkan pesanan yang masih berstatus menunggu konfirmasi.</p>
    </section>

    <?php
        $totalTransaksi = count($items);
        $totalPending = 0;
        $totalDibatalkan = 0;

        foreach ($items as $item) {
            if (strtolower($item['status_transaksi']) === 'pending') {
                $totalPending++;
            }

            if (strtolower($item['status_transaksi']) === 'dibatalkan') {
                $totalDibatalkan++;
            }
        }
    ?>

    <section class="summary">
        <div class="summary-card">
            <small>Total Transaksi</small>
            <strong><?= $totalTransaksi; ?></strong>
        </div>

        <div class="summary-card">
            <small>Menunggu Konfirmasi</small>
            <strong><?= $totalPending; ?></strong>
        </div>

        <div class="summary-card">
            <small>Dibatalkan</small>
            <strong><?= $totalDibatalkan; ?></strong>
        </div>
    </section>

    <section class="section-title">
        <h2>Daftar Pesanan</h2>
    </section>

    <section class="transaction-list">

        <?php if (count($items) === 0): ?>
            <div class="empty-state">
                <h3>Belum ada transaksi</h3>
                <p>Silakan pilih barang dari halaman katalog terlebih dahulu.</p>
            </div>
        <?php endif; ?>

        <?php foreach ($items as $row): ?>
            <?php
                $idTransaksi = (int) $row['id_transaksi'];
                $namaBarang = $row['nama_barang'] ?? 'Barang tidak ditemukan';
                $gambar = !empty($row['gambar']) ? $row['gambar'] : $placeholderImage;
                $qty = (int) $row['qty'];
                $hargaSatuan = (float) $row['harga_satuan'];
                $totalBayar = (float) $row['total_bayar'];
                $metode = metodePembayaran($row['metode_pembayaran']);
                $status = strtolower((string) $row['status_transaksi']);
                $bisaDibatalkan = $status === 'pending';

                $tanggal = '-';

                if (!empty($row['created_at'])) {
                    $tanggal = date('d M Y H:i', strtotime($row['created_at']));
                }
            ?>

            <article class="transaction-card" id="transaksi-<?= $idTransaksi; ?>">
                <div class="transaction-top">
                    <div>
                        <div class="transaction-code">
                            Transaksi #<?= $idTransaksi; ?>
                        </div>
                        <div class="transaction-date">
                            <?= e($tanggal); ?>
                        </div>
                    </div>

                    <span class="status <?= statusClass($status); ?>">
                        <?= e(statusText($status)); ?>
                    </span>
                </div>

                <div class="transaction-body">
                    <div class="product-image">
                        <img 
                            src="<?= e($gambar); ?>" 
                            alt="<?= e($namaBarang); ?>"
                            onerror="this.src='<?= $placeholderImage; ?>'"
                        >
                    </div>

                    <div>
                        <div class="product-name">
                            <?= e($namaBarang); ?>
                        </div>

                        <div class="product-detail">
                            Jumlah: <?= $qty; ?> barang<br>
                            Harga satuan: <?= rupiah($hargaSatuan); ?><br>
                            Metode pembayaran: <?= e($metode); ?><br>
                            No HP: <?= e($row['no_hp']); ?><br>
                            Alamat: <?= e($row['alamat']); ?>
                        </div>
                    </div>

                    <div class="transaction-total">
                        <small>Total Bayar</small>
                        <strong><?= rupiah($totalBayar); ?></strong>
                    </div>
                </div>

                <div class="transaction-actions">
                    <?php if ($bisaDibatalkan): ?>
                        <form class="form-batal" method="POST">
                            <input type="hidden" name="id_transaksi" value="<?= $idTransaksi; ?>">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']); ?>">

                            <button type="submit" class="btn btn-cancel">
                                Batalkan Pesanan
                            </button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-disabled" disabled>
                            Tidak Bisa Dibatalkan
                        </button>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>

    </section>

</main>

<script>
    const forms = document.querySelectorAll('.form-batal');

    forms.forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const yakin = confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');

            if (!yakin) {
                return;
            }

            const button = form.querySelector('button');
            const formData = new FormData(form);

            button.disabled = true;
            button.textContent = 'Membatalkan...';

            try {
                const response = await fetch('../proses/proses_batal_transaksi.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message || 'Pesanan berhasil dibatalkan.');
                    window.location.reload();
                    return;
                }

                alert(result.message || 'Pesanan gagal dibatalkan.');

                button.disabled = false;
                button.textContent = 'Batalkan Pesanan';
            } catch (error) {
                alert('Terjadi kesalahan sistem. Pastikan proses_batal_transaksi.php mengembalikan JSON yang valid.');
                console.error(error);

                button.disabled = false;
                button.textContent = 'Batalkan Pesanan';
            }
        });
    });
</script>

</body>
</html>