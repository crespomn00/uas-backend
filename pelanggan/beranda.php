<?php
session_start();

require_once __DIR__ . '/../class/Barang.php';
require_once __DIR__ . '/../class/Kategori.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'pelanggan') {
    header('Location: ../admin/dashboard.php');
    exit;
}

$barang = new Barang();
$kategoriObj = new Kategori();

$result = $barang->readAll();
$resultKategori = $kategoriObj->readAll();

$items = [];
$kategoriMap = [];
$kategoriTampil = [];

// Ambil semua kategori, lalu simpan dalam bentuk id => nama
while ($kat = $resultKategori->fetch_assoc()) {
    $kategoriMap[$kat['id_kategori']] = $kat['nama_kategori'];
}

// Ambil semua barang
while ($row = $result->fetch_assoc()) {
    $items[] = $row;

    $idKategori = $row['id_kategori'];
    $kategoriTampil[$idKategori] = $kategoriMap[$idKategori] ?? 'Kategori Tidak Diketahui';
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka)
{
    return 'Rp ' . number_format((float) $angka, 0, ',', '.');
}

$placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 400 400'%3E%3Crect width='400' height='400' fill='%23f3f4f6'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dominant-baseline='middle' font-family='Arial' font-size='18' fill='%236b7280'%3ETidak ada gambar%3C/text%3E%3C/svg%3E";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Pelanggan - Katalog Alat Musik</title>

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
            max-width: 1200px;
            margin: auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .logo {
            font-size: 22px;
            font-weight: 800;
            color: #111;
            white-space: nowrap;
        }

        .logo span {
            color: #555;
        }

        .search-box {
            flex: 1;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d4d4d4;
            border-radius: 10px;
            outline: none;
            font-size: 14px;
            background: #fff;
        }

        .search-box input:focus {
            border-color: #111;
        }

        .nav-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .nav-btn {
            border: 1px solid #111;
            background: #111;
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .nav-btn.secondary {
            background: #fff;
            color: #111;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 24px 20px 50px;
        }

        .hero {
            background: #111;
            color: #fff;
            border-radius: 18px;
            padding: 36px 32px;
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            overflow: hidden;
        }

        .hero h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .hero p {
            color: #d4d4d4;
            max-width: 600px;
            line-height: 1.6;
        }

        .hero-badge {
            background: #fff;
            color: #111;
            padding: 12px 18px;
            border-radius: 999px;
            font-weight: 700;
            white-space: nowrap;
        }

        .category-wrapper {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 22px;
            display: flex;
            gap: 10px;
            overflow-x: auto;
        }

        .chip {
            border: 1px solid #d4d4d4;
            background: #fff;
            color: #111;
            padding: 10px 14px;
            border-radius: 999px;
            cursor: pointer;
            font-size: 14px;
            white-space: nowrap;
        }

        .chip.active,
        .chip:hover {
            background: #111;
            color: #fff;
            border-color: #111;
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .section-title h2 {
            font-size: 22px;
        }

        .section-title span {
            color: #555;
            font-size: 14px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
        }

        .product-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 14px;
            overflow: hidden;
            transition: 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .product-image {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: #f3f3f3;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .product-info {
            padding: 12px;
        }

        .product-name {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
            min-height: 40px;
            color: #111;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .product-desc {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
            height: 34px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 12px;
            color: #555;
        }

        .stock {
            background: #f1f1f1;
            padding: 5px 8px;
            border-radius: 999px;
        }

        .btn-buy {
            width: 100%;
            border: none;
            background: #111;
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-buy:hover {
            background: #333;
        }

        .btn-buy:disabled {
            background: #cfcfcf;
            color: #777;
            cursor: not-allowed;
        }

        .empty-state {
            background: #fff;
            border: 1px dashed #cfcfcf;
            border-radius: 14px;
            padding: 40px;
            text-align: center;
            color: #666;
            grid-column: 1 / -1;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 50;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: start;
            margin-bottom: 18px;
        }

        .modal-header h3 {
            font-size: 20px;
            margin-bottom: 6px;
        }

        .modal-header p {
            color: #555;
            font-size: 14px;
        }

        .close-modal {
            border: none;
            background: #f1f1f1;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            border: 1px solid #d4d4d4;
            border-radius: 9px;
            padding: 11px 12px;
            outline: none;
            font-size: 14px;
        }

        .form-group textarea {
            min-height: 84px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #111;
        }

        .total-box {
            background: #f5f5f5;
            border-radius: 10px;
            padding: 13px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            font-weight: 800;
        }

        .submit-order {
            width: 100%;
            background: #111;
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 13px;
            cursor: pointer;
            font-weight: 800;
        }

        .submit-order:hover {
            background: #333;
        }

        @media (max-width: 1100px) {
            .product-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 850px) {
            .navbar-inner {
                flex-wrap: wrap;
            }

            .search-box {
                order: 3;
                flex-basis: 100%;
            }

            .hero {
                align-items: flex-start;
                flex-direction: column;
            }

            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 18px 12px 40px;
            }

            .hero {
                padding: 26px 22px;
            }

            .hero h1 {
                font-size: 24px;
            }

            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .nav-actions {
                margin-left: auto;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <div class="navbar-inner">
        <div class="logo">Epicenter <span>Music Store</span></div>

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Cari gitar, bass, drum, keyboard...">
        </div>

        <div class="nav-actions">
            <a href="riwayat_transaksi.php" class="nav-btn secondary">Riwayat</a>
            <a href="../logout.php" class="nav-btn">Logout</a>
        </div>
    </div>
</header>

<main class="container">

    <section class="hero">
        <div>
            <h1>Katalog Alat Musik</h1>
            <p>
                Temukan berbagai alat musik pilihan dengan tampilan katalog yang bersih,
                modern, dan mudah digunakan oleh pelanggan.
            </p>
        </div>
        <div class="hero-badge">Music Store</div>
    </section>

    <section class="category-wrapper">
        <button class="chip active" data-category="all">Semua Produk</button>

        <?php foreach ($kategoriTampil as $idKategori => $namaKategori): ?>
            <button class="chip" data-category="<?= e($idKategori); ?>">
                <?= e($namaKategori); ?>
            </button>
        <?php endforeach; ?>
    </section>

    <section class="section-title">
        <h2>Produk Tersedia</h2>
        <span><?= count($items); ?> barang ditemukan</span>
    </section>

    <section class="product-grid" id="productGrid">

        <?php if (count($items) === 0): ?>
            <div class="empty-state">
                Belum ada barang yang tersedia.
            </div>
        <?php endif; ?>

        <?php foreach ($items as $row): ?>
            <?php
                $idBarang = (int) $row['id_barang'];
                $namaBarang = $row['nama_barang'];
                $idKategori = $row['id_kategori'];
                $harga = (float) $row['harga'];
                $deskripsi = $row['deskripsi'];
                $gambar = !empty($row['gambar']) ? $row['gambar'] : $placeholderImage;
                $stok = (int) $row['stok'];
            ?>

            <article
                class="product-card"
                data-name="<?= e(strtolower($namaBarang)); ?>"
                data-category="<?= e($idKategori); ?>"
            >
                <div class="product-image">
                    <img
                        src="<?= e($gambar); ?>"
                        alt="<?= e($namaBarang); ?>"
                        onerror="this.src='<?= $placeholderImage; ?>'"
                    >
                </div>

                <div class="product-info">
                    <div class="product-name">
                        <?= e($namaBarang); ?>
                    </div>

                    <div class="product-price">
                        <?= rupiah($harga); ?>
                    </div>

                    <div class="product-desc">
                        <?= e($deskripsi); ?>
                    </div>

                    <div class="product-meta">
                        
                        <span class="stock">Stok <?= $stok; ?></span>
                    </div>

                    <button
                        class="btn-buy"
                        data-id="<?= $idBarang; ?>"
                        data-name="<?= e($namaBarang); ?>"
                        data-price="<?= $harga; ?>"
                        data-stock="<?= $stok; ?>"
                        <?= $stok <= 0 ? 'disabled' : ''; ?>
                    >
                        <?= $stok <= 0 ? 'Stok Habis' : 'Beli Sekarang'; ?>
                    </button>
                </div>
            </article>
        <?php endforeach; ?>

    </section>
</main>

<div class="modal-overlay" id="checkoutModal">
    <div class="modal">
        <div class="modal-header">
            <div>
                <h3>Checkout Barang</h3>
                <p id="modalProductName">Nama barang</p>
            </div>
            <button class="close-modal" id="closeModal">&times;</button>
        </div>

        <form id="checkoutForm" action="../proses/proses_transaksi.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_barang" id="id_barang">
            <input type="hidden" name="harga_satuan" id="harga_satuan">

            <div class="form-group">
                <label>Jumlah Beli</label>
                <input type="number" name="qty" id="qty" min="1" value="1" required>
            </div>

            <div class="form-group">
                <label>Alamat Pengiriman</label>
                <textarea name="alamat" placeholder="Masukkan alamat lengkap" required></textarea>
            </div>

            <div class="form-group">
                <label>Nomor HP</label>
                <input type="text" name="no_hp" placeholder="Contoh: 081234567890" required>
            </div>

            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="metode_pembayaran" required>
                    <option value="">Pilih metode pembayaran</option>
                    <option value="transfer">Transfer Bank</option>
                </select>
            </div>

            <div class="form-group">
                <label>Bukti Pembayaran</label>
                <input type="file" name="bukti_pembayaran" accept="image/jpeg,image/png">
            </div>

            <div class="total-box">
                <span>Total Bayar</span>
                <span id="totalBayar">Rp 0</span>
            </div>

            <button type="submit" class="submit-order">Kirim Pesanan</button>
        </form>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const cards = document.querySelectorAll('.product-card');
    const chips = document.querySelectorAll('.chip');

    let activeCategory = 'all';

    function filterProducts() {
        const keyword = searchInput.value.toLowerCase();

        cards.forEach(card => {
            const name = card.dataset.name;
            const category = card.dataset.category;

            const matchSearch = name.includes(keyword);
            const matchCategory = activeCategory === 'all' || category === activeCategory;

            card.style.display = matchSearch && matchCategory ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', filterProducts);

    chips.forEach(chip => {
        chip.addEventListener('click', function () {
            chips.forEach(item => item.classList.remove('active'));
            this.classList.add('active');

            activeCategory = this.dataset.category;
            filterProducts();
        });
    });

    const modal = document.getElementById('checkoutModal');
    const closeModal = document.getElementById('closeModal');
    const buyButtons = document.querySelectorAll('.btn-buy');

    const idBarangInput = document.getElementById('id_barang');
    const hargaSatuanInput = document.getElementById('harga_satuan');
    const qtyInput = document.getElementById('qty');
    const totalBayarText = document.getElementById('totalBayar');
    const modalProductName = document.getElementById('modalProductName');

    let selectedPrice = 0;
    let selectedStock = 1;

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    function updateTotal() {
        let qty = parseInt(qtyInput.value || 1);

        if (qty < 1) qty = 1;
        if (qty > selectedStock) qty = selectedStock;

        qtyInput.value = qty;
        totalBayarText.textContent = formatRupiah(qty * selectedPrice);
    }

    buyButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = parseFloat(this.dataset.price);
            const stock = parseInt(this.dataset.stock);

            selectedPrice = price;
            selectedStock = stock;

            idBarangInput.value = id;
            hargaSatuanInput.value = price;
            qtyInput.value = 1;
            qtyInput.max = stock;

            modalProductName.textContent = name + ' - ' + formatRupiah(price);
            updateTotal();

            modal.classList.add('show');
        });
    });

    qtyInput.addEventListener('input', updateTotal);

    closeModal.addEventListener('click', function () {
        modal.classList.remove('show');
    });

    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });

    document.getElementById('checkoutForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert(result.message || 'Pesanan berhasil dibuat.');
                window.location.reload();
                return;
            }

            if (result.message) {
                alert(result.message);
                return;
            }

            alert(Object.values(result).join('\n'));
        } catch (error) {
            alert('Terjadi kesalahan sistem. Pastikan proses_transaksi.php mengembalikan JSON yang valid.');
            console.error(error);
        }
    });
</script>

</body>
</html>