<?php
session_start();

require_once '../class/Transaksi.php';
require_once '../validator/validator_transaksi.php';

$transaksi = new Transaksi();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Metode request tidak valid.'
    ]);
    exit;
}

$validator = new FormValidatorTransaksi($_POST, $_FILES);
$validator->validateAll();

if ($validator->hasErrors()) {
    echo json_encode($validator->getErrors());
    exit;
}

// $id_user, $id_barang, $qty, $harga_satuan, $alamat, $no_hp, $total_bayar, $metode_pembayaran, $status_transaksi, $bukti_pembayaran
$id_user = $_SESSION['user_id'];
$id_barang = (int) $_POST['id_barang'];
$qty = (int) $_POST['qty'];
$harga_satuan = (float) $_POST['harga_satuan'];
$alamat = trim($_POST['alamat']);
$no_hp = trim($_POST['no_hp']);
$total_bayar = $qty * $harga_satuan;
$metode_pembayaran = trim($_POST['metode_pembayaran']);
$status_transaksi = 'pending';
$bukti_pembayaran = null;

// Konversi gambar baru menjadi Base64 jika user memilih file.
if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] === UPLOAD_ERR_OK) {
    $mimeType  = mime_content_type($_FILES['bukti_pembayaran']['tmp_name']);
    $imageData = file_get_contents($_FILES['bukti_pembayaran']['tmp_name']);
    $bukti_pembayaran    = "data:$mimeType;base64," . base64_encode($imageData);
}

if ($transaksi->create($id_user, $id_barang, $qty, $harga_satuan, $alamat, $no_hp, $total_bayar, $metode_pembayaran, $status_transaksi, $bukti_pembayaran)) {
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
