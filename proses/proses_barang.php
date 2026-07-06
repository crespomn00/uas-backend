<?php
require_once '../class/Barang.php';
require_once '../validator/validator_barang.php';

$barang = new Barang();

// Hapus data jika link hapus diklik.
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    if ($barang->delete($id)) {
        echo "
        <script>
            alert('Data berhasil dihapus!');
            window.location.href='../form_barang.php';
        </script>";
    } else {
        echo "
        <script>
            alert('Data gagal dihapus!');
            window.location.href='../form_barang.php';
        </script>";
    }
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Metode request tidak valid.'
    ]);
    exit;
}

$validator = new FormValidatorBarang($_POST, $_FILES);
$validator->validateAll();

if ($validator->hasErrors()) {
    echo json_encode($validator->getErrors());
    exit;
}

$id_barang   = !empty($_POST['id_barang']) ? (int) $_POST['id_barang'] : 0;
$nama_barang = trim($_POST['nama_barang']);
$id_kategori = (int) $_POST['id_kategori'];
$harga       = (float) $_POST['harga'];
$deskripsi   = trim($_POST['deskripsi']);
$stok        = (int) $_POST['stok'];
$gambar      = null;

// Konversi gambar baru menjadi Base64 jika user memilih file.
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $mimeType  = mime_content_type($_FILES['gambar']['tmp_name']);
    $imageData = file_get_contents($_FILES['gambar']['tmp_name']);
    $gambar    = "data:$mimeType;base64," . base64_encode($imageData);
}

$dataLama = $id_barang > 0 ? $barang->readById($id_barang) : null;

if ($dataLama) {
    // Jika edit tanpa upload gambar baru, gunakan gambar lama.
    if ($gambar === null) {
        $gambar = $dataLama['gambar'];
    }

    if ($barang->update($nama_barang, $id_kategori, $harga, $deskripsi, $gambar, $stok, $id_barang)) {
        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil diedit!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal mengedit data!'
        ]);
    }
} else {
    if ($barang->create($nama_barang, $id_kategori, $harga, $deskripsi, $gambar, $stok)) {
        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil ditambahkan!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menambahkan data!'
        ]);
    }
}
