<!DOCTYPE html>
<?php
require_once 'protected.php';
require_once 'class/Barang.php';
require_once 'class/Kategori.php';

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$barang = new Barang();
$editData = null;

if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $editData = $barang->readById($idEdit);

    if (!$editData) {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location.href = 'form_barang.php';
              </script>";
        exit;
    }
}
?>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        .wrapper {
            width: 100%;
            display: flex;
            flex-direction: row;
            gap: 20px;
        }
        .card-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 30%;
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
            width: 70%;
            height: 600px;
            margin: auto;
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
            background: #1e3a8a;
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
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card-form">
            <a href="index.php">Kembali</a>
            <h2><?= $editData ? 'Edit Barang' : 'Form Barang'; ?></h2>

            <form action="proses/proses_barang.php" method="POST" id="form" enctype="multipart/form-data">
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
                    <a href="form_barang.php">Batal Edit</a>
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
                                        <a href='?edit=" . e($row['id_barang']) . "'>Edit</a>
                                        <a href='proses/proses_barang.php?hapus=" . e($row['id_barang']) . "' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
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

            fetch('proses/proses_barang.php', {
                method: 'POST',
                body: new FormData(this),
            })
            .then((res) => res.json())
            .then((dataObj) => {
                if (dataObj.success === true) {
                    alert(dataObj.message);
                    window.location.href = 'form_barang.php';
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
