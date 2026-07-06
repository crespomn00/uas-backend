<!DOCTYPE html>
<?php
require_once __DIR__ . '/../protected.php';
require_once '../class/Barang.php';
require_once '../class/Kategori.php';

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$barang = new Barang();
$total_barang = $barang->countBarang();
$editData = null;

if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $editData = $barang->readById($idEdit);

    if (!$editData) {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location.href = 'data-barang.php';
              </script>";
        exit;
    }
}
?>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Poppins,sans-serif;
}

body{
    background:#f4f4f4;
    color:#111;
}

.container{
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */

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

/* CARD */

.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:25px;
    margin-bottom:35px;
}

.card{
    background:white;
    padding:25px;
    border-radius:15px;
    border:2px solid #111;
    transition:.3s;
}

.card:hover{
    background:#111;
    color:white;
    transform:translateY(-6px);
}

.card i{
    font-size:30px;
    margin-bottom:20px;
}

.card h2{
    margin-bottom:10px;
}

/* TABLE */
.table-card{
    background:white;
    padding:25px;
    border-radius:15px;
    overflow:auto;
}

.table-header{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
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

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#111;
    color:white;
    padding:15px;
}

td{
    padding:15px;
    border-bottom:1px solid #ddd;
}

.done{
    background:#111;
    color:white;
    padding:5px 12px;
    border-radius:20px;
}

.pending{
    background:#ddd;
    padding:5px 12px;
    border-radius:20px;
}

.wrapper {
            width: 100%;
            gap: 20px;
        }
        .card-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            min-height: 600px;
            margin: auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }
        .card-table {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            height: 600px;
            margin: auto;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        form {
            width: 100%;
        }
        .textbox {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }
        input, textarea, select {
            padding: 12px 12px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            outline: none;
        }
        .table-container {
            width: 100%;
            height: 500px;
            overflow-y: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: left;
            font-size: 12pt;
            font-weight: 300;
            line-height: 1.5;
            vertical-align: top;
        }
        th {
            position: sticky;
            top: 0;
            z-index: 1;
            background: #000000;
            color: white;
        }
        .danger {
            color: red;
            font-weight: bold;
        }
        .error {
            color: red;
            font-size: 12px;
            margin-top: 4px;
        }
        .preview {
            max-width: 120px;
            height: auto;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

/* RESPONSIVE */

@media(max-width:1000px){

.cards{
    grid-template-columns:repeat(2,1fr);
}

}
@media(max-width:768px){

.container{
    flex-direction:column;
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

main{
    padding:20px;
}

.cards{
    grid-template-columns:1fr;
}

header{
    flex-direction:column;
    align-items:flex-start;
    gap:20px;
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
    <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>ADMIN</h2>
            </div>
            <ul>
                <li>
                    <i class="fa-solid fa-house"></i>
                    <span><a href="dashboard.php" style="text-decoration:none; color: white;">Dashboard</></span>
                </li>
                <li>
                    <i class="fa-solid fa-box"></i>
                    <span><a href="data-barang.php" style="text-decoration: none; color: white;">Data Barang</a></span>
                </li>
                <li>
                    <i class="fa-solid fa-layer-group"></i>
                    <span><a href="data-barang.php" style="text-decoration: none; color: white;">Data Kategori</a></span>
                </li>
                <li>
                    <i class="fa-solid fa-users"></i>
                    <span><a href="data-user.php" style="text-decoration: none; color: white;">Data User</a></span>
                </li>

                <li>
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    <span><a href="data-barang.php" style="text-decoration: none; color: white;">Data Transaksi</a></span>
                </li>
                <form action="../proses/proses_logout.php" method="post">
                    <button type="submit" class="logout" style="margin-left: 30px; background: red;">Logout</button>
                </form>
            </ul>
        </aside>

    <!-- Main -->
    <main>
    <header>
        <div>
            <h1>LIST BARANG</h1>
        </div>
        <div class="profile">
            <i class="fa-solid fa-bell"></i>
            <div class="avatar">A</div>
        </div>
    </header>
    <!-- Cards -->

    <section class="cards">
        <div class="card">
            <i class="fa-solid fa-box"></i>
            <h1><?= $total_barang ?></h1>
            <p>Total Barang</p>
        </div>
    </section>

    <!-- Table -->

    <section class="table-card">
        <div class="wrapper">
        <div class="card-form">
            <h2><?= $editData ? 'Edit Barang' : 'Form Barang'; ?></h2>

            <form action="../proses/proses_barang.php" method="POST" id="form" enctype="multipart/form-data">
                <input type="hidden" name="id_barang" value="<?= e($editData['id_barang'] ?? ''); ?>">

                <div class="textbox">
                    <label>Nama Barang</label>
                    <input type="text" name="nama_barang" value="<?= e($editData['nama_barang'] ?? ''); ?>" required>
                    <div id="error-nama_barang" class="error"></div>
                </div>

                <div class="textbox">
                    <label>Kategori</label>
                    <select name="id_kategori" id="id_kategori" required>
                        <option value="">Pilih Kategori</option>
                        <?php
                        $kategori = new Kategori();
                        $dataKategori = $kategori->readAll();

                        if ($dataKategori && $dataKategori->num_rows > 0) {
                            while ($row = $dataKategori->fetch_assoc()) {
                                $selected = isset($editData['id_kategori']) && (int) $editData['id_kategori'] === (int) $row['id_kategori'] ? 'selected' : '';
                                echo "<option value='" . e($row['id_kategori']) . "' $selected>" . e($row['nama_kategori']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <div id="error-id_kategori" class="error"></div>
                </div>

                <div class="textbox">
                    <label>Harga</label>
                    <input type="number" name="harga" value="<?= e($editData['harga'] ?? ''); ?>" required>
                    <div id="error-harga" class="error"></div>
                </div>

                <div class="textbox">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" required><?= e($editData['deskripsi'] ?? ''); ?></textarea>
                    <div id="error-deskripsi" class="error"></div>
                </div>

                <div class="textbox">
                    <label>Gambar</label>
                    <input type="file" name="gambar" accept="image/jpeg,image/png">
                    <?php if (!empty($editData['gambar'])): ?>
                        <img src="<?= e($editData['gambar']); ?>" alt="Gambar saat ini" class="preview">
                        <small>Kosongkan gambar jika tidak ingin mengganti gambar lama.</small>
                    <?php endif; ?>
                    <div id="error-gambar" class="error"></div>
                </div>

                <div class="textbox">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?= e($editData['stok'] ?? ''); ?>">
                    <div id="error-stok" class="error"></div>
                </div>

                <button type="submit">Simpan</button>
                <?php if ($editData): ?>
                <button style="background: red;"><a href="data-barang.php" style="color: white; text-decoration: none;">Batal Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card-table">
            <h2>Data Barang</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Stok</th>
                            <th>Dibuat</th>
                            <th>Diubah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $kategoriMap = [];
                        $kategoriUntukTabel = (new Kategori())->readAll();
                        if ($kategoriUntukTabel && $kategoriUntukTabel->num_rows > 0) {
                            while ($kat = $kategoriUntukTabel->fetch_assoc()) {
                                $kategoriMap[$kat['id_kategori']] = $kat['nama_kategori'];
                            }
                        }

                        $dataBarang = $barang->readAll();

                        if ($dataBarang && $dataBarang->num_rows > 0) {
                            while ($row = $dataBarang->fetch_assoc()) {
                                $class = ((int) $row['stok'] < 5) ? 'danger' : '';
                                $harga = 'Rp ' . number_format((float) $row['harga'], 0, ',', '.');
                                $kategoriBarang = $kategoriMap[$row['id_kategori']] ?? $row['id_kategori'];
                                $gambarHtml = !empty($row['gambar'])
                                    ? "<img src='" . e($row['gambar']) . "' alt='Gambar Barang' style='width: 100px; height: auto;'>"
                                    : '-';

                                echo "
                                <tr>
                                    <td>" . e($row['id_barang']) . "</td>
                                    <td>" . e($row['nama_barang']) . "</td>
                                    <td>" . e($kategoriBarang) . "</td>
                                    <td>" . e($harga) . "</td>
                                    <td>" . e($row['deskripsi']) . "</td>
                                    <td>$gambarHtml</td>
                                    <td class='" . e($class) . "'>" . e($row['stok']) . "</td>
                                    <td>" . e($row['created_at'] ?? '-') . "</td>
                                    <td>" . e($row['updated_at'] ?? '-') . "</td>
                                    <td>
                                        <button style='background: blue;'>
                                            <a href='?edit=" . e($row['id_barang']) . "' style='color: white; text-decoration: none;'>Edit</a>
                                        </button>
                                        <button style='background: red;'>
                                            <a href='../proses/proses_barang.php?hapus=" . e($row['id_barang']) . "' onclick=\"return confirm('Yakin ingin menghapus data ini?')\" style='color: white; text-decoration: none;'>Hapus</a>
                                        </button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>Belum ada data</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        </section>
    </main>
</div>
<script>
        const form = document.getElementById('form');
        const errorFields = ['nama_barang', 'id_kategori', 'harga', 'deskripsi', 'gambar', 'stok'];

        function resetErrors() {
            errorFields.forEach((field) => {
                const el = document.getElementById('error-' + field);
                if (el) el.innerHTML = '';
            });
        }

        function showErrors(dataObj) {
            errorFields.forEach((field) => {
                if (dataObj[field]) {
                    const el = document.getElementById('error-' + field);
                    if (el) el.innerHTML = dataObj[field];
                }
            });
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            resetErrors();

            fetch('../proses/proses_barang.php', {
                method: 'POST',
                body: new FormData(this),
            })
            .then((res) => res.json())
            .then((dataObj) => {
                if (dataObj.success === true) {
                    alert(dataObj.message);
                    window.location.href = 'data-barang.php';
                    return;
                }

                showErrors(dataObj);

                if (dataObj.success === false && dataObj.message) {
                    alert(dataObj.message);
                }
            })
            .catch((err) => {
                console.error(err);
                alert('Terjadi kesalahan sistem! Pastikan proses_barang.php mengembalikan JSON yang valid.');
            });
        });
</script>

</body>
</html>