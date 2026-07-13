<?php
require_once __DIR__ . '/../config/Database.php';

class Laporan extends Database {

    private $table = "tabel_laporan_penjualan";

    // READ ALL
    public function readAll() {
        $query = "SELECT * FROM $this->table ORDER BY created_at DESC";
        return $this->conn->query($query);
    }


    public function countPenjualan() {
        $query = "SELECT SUM(total_harga) AS total_penjualan 
                FROM $this->table";

        $result = $this->conn->query($query);
        $data = $result->fetch_assoc();

        return $data['total_penjualan'];
    }

}
?>
