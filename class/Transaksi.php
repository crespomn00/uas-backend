<?php
require_once __DIR__ . '/../config/Database.php';

class Transaksi extends Database {

    private $table = "tabel_transaksi";

    // CREATE
    public function create($id_user, $id_barang, $qty, $harga_satuan, $alamat, $no_hp, $total_bayar, $metode_pembayaran, $status_transaksi, $bukti_pembayaran) {
        $query = "INSERT INTO $this->table (id_user, id_barang, qty, harga_satuan, alamat, no_hp, total_bayar, metode_pembayaran, status_transaksi, bukti_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiidssdsss", $id_user, $id_barang, $qty, $harga_satuan, $alamat, $no_hp, $total_bayar, $metode_pembayaran, $status_transaksi, $bukti_pembayaran);
        return $stmt->execute();
    }

    // READ ALL
    public function readAll() {
        $query = "SELECT * FROM $this->table ORDER BY id_transaksi ASC";
        return $this->conn->query($query);
    }

    // READ BY ID
    public function readByIdUser($id_user) {
        $query = "SELECT * FROM $this->table WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // UPDATE STATUS
    public function updateStatus($status_transaksi, $id_transaksi) {
        $query = "UPDATE $this->table SET status_transaksi = ? WHERE id_transaksi = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status_transaksi, $id_transaksi);
        return $stmt->execute();
    }

    // INPUT PEMBAYARAN
    public function inputPembayaran($bukti_pembayaran, $id_transaksi) {
        $query = "UPDATE $this->table SET bukti_pembayaran = ? WHERE id_transaksi = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $bukti_pembayaran, $id_transaksi);
        return $stmt->execute();
    }

    // READ ALL BY USER ID
    public function readAllByIdUser($id_user) {
        $query = "
            SELECT 
                t.*,
                b.nama_barang,
                b.gambar,
                b.deskripsi
            FROM $this->table t
            LEFT JOIN tabel_barang b ON t.id_barang = b.id_barang
            WHERE t.id_user = ?
            ORDER BY t.id_transaksi DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();

        return $stmt->get_result();
    }

    // READ TRANSAKSI BY ID DAN USER
    public function readByIdAndUser($id_transaksi, $id_user) {
        $query = "
            SELECT * FROM $this->table 
            WHERE id_transaksi = ? AND id_user = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_transaksi, $id_user);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // BATALKAN PESANAN OLEH USER
    public function cancelByUser($id_transaksi, $id_user) {
        $status_transaksi = 'dibatalkan';

        $query = "
            UPDATE $this->table 
            SET status_transaksi = ?
            WHERE id_transaksi = ? 
            AND id_user = ?
            AND status_transaksi = 'pending'
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sii", $status_transaksi, $id_transaksi, $id_user);

        return $stmt->execute();
    }

    // READ ALL TRANSAKSI UNTUK ADMIN
    public function readAllForAdmin() {
        $query = "
            SELECT 
                t.*,
                b.nama_barang,
                b.gambar,
                b.stok
            FROM $this->table t
            LEFT JOIN tabel_barang b ON t.id_barang = b.id_barang
            ORDER BY t.id_transaksi DESC
        ";

        return $this->conn->query($query);
    }

    // READ BY ID TRANSAKSI
    public function readById($id_transaksi) {
        $query = "
            SELECT 
                t.*,
                b.nama_barang,
                b.stok
            FROM $this->table t
            LEFT JOIN tabel_barang b ON t.id_barang = b.id_barang
            WHERE t.id_transaksi = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_transaksi);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // VALIDASI PEMBAYARAN DAN KURANGI STOK
    public function validasiLunasKurangiStok($id_transaksi) {
        $this->conn->begin_transaction();

        try {
            $query = "
                SELECT 
                    t.id_transaksi,
                    t.id_barang,
                    t.qty,
                    t.status_transaksi,
                    t.bukti_pembayaran,
                    b.stok
                FROM $this->table t
                INNER JOIN tabel_barang b ON t.id_barang = b.id_barang
                WHERE t.id_transaksi = ?
                FOR UPDATE
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id_transaksi);
            $stmt->execute();

            $data = $stmt->get_result()->fetch_assoc();

            if (!$data) {
                throw new Exception("Transaksi tidak ditemukan.");
            }

            if (strtolower($data['status_transaksi']) !== 'pending') {
                throw new Exception("Transaksi ini sudah divalidasi atau tidak bisa diproses.");
            }

            if (empty($data['bukti_pembayaran'])) {
                throw new Exception("Bukti pembayaran belum tersedia.");
            }

            $id_barang = (int) $data['id_barang'];
            $qty = (int) $data['qty'];
            $stok = (int) $data['stok'];

            if ($stok < $qty) {
                throw new Exception("Stok barang tidak mencukupi.");
            }

            $queryUpdateStok = "
                UPDATE tabel_barang 
                SET stok = stok - ? 
                WHERE id_barang = ? 
                AND stok >= ?
            ";

            $stmtUpdateStok = $this->conn->prepare($queryUpdateStok);
            $stmtUpdateStok->bind_param("iii", $qty, $id_barang, $qty);
            $stmtUpdateStok->execute();

            if ($stmtUpdateStok->affected_rows < 1) {
                throw new Exception("Gagal mengurangi stok barang.");
            }

            $status_lunas = 'Lunas';

            $queryUpdateStatus = "
                UPDATE $this->table 
                SET status_transaksi = ? 
                WHERE id_transaksi = ? 
                AND status_transaksi = 'pending'
            ";

            $stmtUpdateStatus = $this->conn->prepare($queryUpdateStatus);
            $stmtUpdateStatus->bind_param("si", $status_lunas, $id_transaksi);
            $stmtUpdateStatus->execute();

            if ($stmtUpdateStatus->affected_rows < 1) {
                throw new Exception("Gagal mengubah status transaksi.");
            }

            $this->conn->commit();

            return [
                'success' => true,
                'message' => 'Pesanan berhasil divalidasi menjadi Lunas dan stok barang sudah dikurangi.'
            ];

        } catch (Exception $e) {
            $this->conn->rollback();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function countTransaksi() {
        $query = "SELECT COUNT(*) AS total_transaksi 
                FROM $this->table";

        $result = $this->conn->query($query);
        $data = $result->fetch_assoc();

        return $data['total_transaksi'];
    }

}
?>
