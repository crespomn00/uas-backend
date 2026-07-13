<?php
require_once __DIR__ . '/../protected.php';
require_once __DIR__ . '/../class/Transaksi.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$transaksi = new Transaksi();
$result = $transaksi->readAllForAdmin();

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

    if ($status === 'lunas') {
        return 'status-lunas';
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
        'pending' => 'Menunggu Validasi',
        'lunas' => 'Lunas',
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
    <title>Kelola Transaksi Admin</title>

    <style>
         @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        * {
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Poppins,sans-serif;
        }

        body {
            background: #f5f5f5;
            color: #111;
        }
        .container {
            display:flex;
            min-height:100vh;
        }

        .sidebar{
            width:250px;
            background:#111;
            color:#fff;
            padding:30px 0;
            display:flex;
            flex-direction:column;
        }

        .logo{
            text-align:center;
            margin-bottom:40px;
        }

        .logo h2{
            letter-spacing:4px;
        }

        .sidebar ul{
            list-style:none;
        }

        .sidebar li{
            padding:18px 30px;
            display:flex;
            gap:15px;
            cursor:pointer;
            transition:.3s;
        }

        .sidebar li:hover,
        .sidebar .active{
            background:#fff;
            color:#111;
        }

        .logout{
            margin-top:30px;
            border-top:1px solid rgba(255,255,255,.15);
        }


        /* MAIN */
        main{
            flex:1;
            padding:40px;
        }

        /* HEADER */
        header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:40px;
        }

        .profile{
            display:flex;
            align-items:center;
            gap:20px;
        }

        .avatar{
            width:45px;
            height:45px;
            background:#111;
            color:white;
            border-radius:50%;
            display:flex;
            justify-content:center;
            align-items:center;
            font-weight:bold;

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
            display: block;
            color: #666;
            margin-bottom: 8px;
        }

        .summary-card strong {
            font-size: 24px;
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
            align-items: flex-start;
            gap: 14px;
            padding-bottom: 12px;
            margin-bottom: 14px;
            border-bottom: 1px solid #eee;
        }

        .transaction-code {
            font-weight: 800;
            margin-bottom: 5px;
        }

        .transaction-date {
            color: #666;
            font-size: 13px;
        }

        .status {
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-pending {
            background: #f2f2f2;
            color: #111;
        }

        .status-lunas {
            background: #111;
            color: #fff;
        }

        .status-cancel {
            background: #eeeeee;
            color: #777;
            text-decoration: line-through;
        }

        .status-default {
            background: #f3f3f3;
            color: #555;
        }

        .transaction-body {
            display: grid;
            grid-template-columns: 90px 1fr 230px;
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

        .transaction-payment {
            text-align: right;
        }

        .transaction-payment small {
            color: #666;
            display: block;
            margin-bottom: 5px;
        }

        .transaction-payment strong {
            display: block;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .action-area {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        button{
            background:#111;
            color:white;
            border:none;
            padding:12px 18px;
            border-radius:8px;
            cursor:pointer;
        }

        button:hover{
            background:#333;
        }

        .btn {
            border: none;
            border-radius: 9px;
            padding: 10px 13px;
            font-weight: 800;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-proof {
            background: #fff;
            color: #111;
            border: 1px solid #111;
        }

        .btn-validate {
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

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 99;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: 16px;
            width: 100%;
            max-width: 620px;
            padding: 18px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .modal-header h3 {
            font-size: 20px;
        }

        .close-modal {
            border: none;
            background: #f1f1f1;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
        }

        .proof-image {
            width: 100%;
            max-height: 520px;
            object-fit: contain;
            border-radius: 12px;
            background: #f5f5f5;
            border: 1px solid #eee;
        }

        @media (max-width: 850px) {
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

            .transaction-payment {
                grid-column: 1 / -1;
                text-align: left;
                padding-top: 12px;
                border-top: 1px solid #eee;
            }

            .action-area {
                justify-content: flex-start;
            }

            .transaction-top {
                flex-direction: column;
            }

            .sidebar{
                width:100%;
                padding:20px;
            }

            .sidebar ul{
                display:flex;
                flex-wrap:wrp;
                justify-content:center;
                gap:10px;
            }

            .sidebar li{
                padding:10px 15px;
                border-radius:8px;
            }
        }
    </style>
    <link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
/>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h2>ADMIN</h2>
            </div>
            <ul>
                <li>
                    <i class="fa-solid fa-house"></i>
                    <span><a href="dashboard.php" style="text-decoration:none; color: white;">Dashboard</a></span>
                </li>
                <li>
                    <i class="fa-solid fa-box"></i>
                    <span><a href="data-barang.php" style="text-decoration: none; color: white;">Data Barang</a></span>
                </li>
                <li>
                    <i class="fa-solid fa-layer-group"></i>
                    <span><a href="data-kategori.php" style="text-decoration: none; color: white;">Data Kategori</a></span>
                </li>
                <li>
                    <i class="fa-solid fa-users"></i>
                    <span><a href="data-user.php" style="text-decoration: none; color: white;">Data User</a></span>
                </li>

                <li class="active">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    <span>Data Transaksi</span>
                </li>

                <li>
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    <span><a href="laporan_penjualan.php" style="text-decoration: none; color: white;">Laporan Penjualan</a></span>
                </li>

                <form action="../proses/proses_logout.php" method="post">
                    <button type="submit" class="logout" style="margin-left: 30px; background: red;">Logout</button>
                </form>
            </ul>
        </aside>

<main>
    <header>
    <div>
        <h1>Data Transaksi</h1>
            <p>Halaman lihat data transaksi</p>
        </div>
    <div class="profile">
            <i class="fa-solid fa-bell"></i>
            <div class="avatar">A</div>
        </div>
    </header>

    <?php
        $totalTransaksi = count($items);
        $totalPending = 0;
        $totalLunas = 0;

        foreach ($items as $item) {
            $status = strtolower($item['status_transaksi']);

            if ($status === 'pending') {
                $totalPending++;
            }

            if ($status === 'lunas') {
                $totalLunas++;
            }
        }
    ?>

    <section class="summary">
        <div class="summary-card">
            <small>Total Transaksi</small>
            <strong><?= $totalTransaksi; ?></strong>
        </div>

        <div class="summary-card">
            <small>Menunggu Validasi</small>
            <strong><?= $totalPending; ?></strong>
        </div>

        <div class="summary-card">
            <small>Lunas</small>
            <strong><?= $totalLunas; ?></strong>
        </div>
    </section>

    <section class="transaction-list">

        <?php if (count($items) === 0): ?>
            <div class="empty-state">
                <h3>Belum ada transaksi</h3>
                <p>Transaksi pelanggan akan tampil di halaman ini.</p>
            </div>
        <?php endif; ?>

        <?php foreach ($items as $row): ?>
            <?php
                $idTransaksi = (int) $row['id_transaksi'];
                $idUser = (int) $row['id_user'];
                $namaBarang = $row['nama_barang'] ?? 'Barang tidak ditemukan';
                $gambar = !empty($row['gambar']) ? $row['gambar'] : $placeholderImage;
                $qty = (int) $row['qty'];
                $stok = (int) $row['stok'];
                $hargaSatuan = (float) $row['harga_satuan'];
                $totalBayar = (float) $row['total_bayar'];
                $metode = metodePembayaran($row['metode_pembayaran']);
                $status = strtolower((string) $row['status_transaksi']);
                $buktiPembayaran = $row['bukti_pembayaran'] ?? '';
                $adaBukti = !empty($buktiPembayaran);
                $bisaValidasi = $status === 'pending' && $adaBukti;

                $tanggal = '-';

                if (!empty($row['created_at'])) {
                    $tanggal = date('d M Y H:i', strtotime($row['created_at']));
                }
            ?>

            <article class="transaction-card" id="transaksi-<?= $idTransaksi; ?>">
                <div class="transaction-top">
                    <div>
                        <div class="transaction-code">
                            Transaksi #<?= $idTransaksi; ?> — User ID <?= $idUser; ?>
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
                            Jumlah beli: <?= $qty; ?> barang<br>
                            Harga satuan: <?= rupiah($hargaSatuan); ?><br>
                            Stok saat ini: <?= $stok; ?><br>
                            Metode pembayaran: <?= e($metode); ?><br>
                            No HP: <?= e($row['no_hp']); ?><br>
                            Alamat: <?= e($row['alamat']); ?>
                        </div>
                    </div>

                    <div class="transaction-payment">
                        <small>Total Bayar</small>
                        <strong><?= rupiah($totalBayar); ?></strong>

                        <div class="action-area">
                            <?php if ($adaBukti): ?>
                                <button 
                                    type="button" 
                                    class="btn btn-proof"
                                    data-proof="<?= e($buktiPembayaran); ?>"
                                >
                                    Lihat Bukti
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-disabled" disabled>
                                    Belum Ada Bukti
                                </button>
                            <?php endif; ?>

                            <?php if ($bisaValidasi): ?>
                                <form class="form-validasi" method="POST">
                                    <input type="hidden" name="id_transaksi" value="<?= $idTransaksi; ?>">
                                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']); ?>">

                                    <button type="submit" class="btn btn-validate">
                                        Validasi Lunas
                                    </button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="btn btn-disabled" disabled>
                                    Tidak Bisa Validasi
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

    </section>

</main>

<div class="modal-overlay" id="proofModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Bukti Pembayaran</h3>
            <button class="close-modal" id="closeModal">&times;</button>
        </div>

        <img src="" alt="Bukti Pembayaran" class="proof-image" id="proofImage">
    </div>
</div>

<script>
    const forms = document.querySelectorAll('.form-validasi');

    forms.forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const yakin = confirm('Pastikan pembayaran benar-benar sudah diterima. Validasi pesanan menjadi Lunas?');

            if (!yakin) {
                return;
            }

            const button = form.querySelector('button');
            const formData = new FormData(form);

            button.disabled = true;
            button.textContent = 'Memvalidasi...';

            try {
                const response = await fetch('../proses/proses_validasi_transaksi.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message || 'Transaksi berhasil divalidasi.');
                    window.location.reload();
                    return;
                }

                alert(result.message || 'Transaksi gagal divalidasi.');

                button.disabled = false;
                button.textContent = 'Validasi Lunas';
            } catch (error) {
                alert('Terjadi kesalahan sistem. Pastikan proses_validasi_transaksi.php mengembalikan JSON yang valid.');
                console.error(error);

                button.disabled = false;
                button.textContent = 'Validasi Lunas';
            }
        });
    });

    const proofButtons = document.querySelectorAll('.btn-proof');
    const proofModal = document.getElementById('proofModal');
    const proofImage = document.getElementById('proofImage');
    const closeModal = document.getElementById('closeModal');

    proofButtons.forEach(button => {
        button.addEventListener('click', function () {
            proofImage.src = this.dataset.proof;
            proofModal.classList.add('show');
        });
    });

    closeModal.addEventListener('click', function () {
        proofModal.classList.remove('show');
        proofImage.src = '';
    });

    proofModal.addEventListener('click', function (e) {
        if (e.target === proofModal) {
            proofModal.classList.remove('show');
            proofImage.src = '';
        }
    });
</script>

</body>
</html>