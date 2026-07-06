<?php
session_start();

require_once __DIR__ . '/../class/Transaksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Metode request tidak valid.'
    ]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Silakan login terlebih dahulu.'
    ]);
    exit;
}

if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    http_response_code(403);

    echo json_encode([
        'success' => false,
        'message' => 'Anda tidak memiliki akses.'
    ]);
    exit;
}

if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);

    echo json_encode([
        'success' => false,
        'message' => 'Token keamanan tidak valid.'
    ]);
    exit;
}

$id_transaksi = isset($_POST['id_transaksi']) ? (int) $_POST['id_transaksi'] : 0;

if ($id_transaksi <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'ID transaksi tidak valid.'
    ]);
    exit;
}

$transaksi = new Transaksi();
$result = $transaksi->validasiLunasKurangiStok($id_transaksi);

echo json_encode($result);
exit;