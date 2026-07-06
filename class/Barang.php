<?php
require_once __DIR__ . '/../config/Database.php';

class Barang extends Database {

    private $table = "tabel_barang";

    // CREATE
    public function create($nama_barang, $id_kategori, $harga, $deskripsi, $gambar, $stok) {
        $query = "INSERT INTO $this->table (nama_barang, id_kategori, harga, deskripsi, gambar, stok) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sidssi", $nama_barang, $id_kategori, $harga, $deskripsi, $gambar, $stok);
        return $stmt->execute();
    }

    // READ ALL
    public function readAll() {
        $query = "SELECT * FROM $this->table ORDER BY id_barang ASC";
        return $this->conn->query($query);
    }

    // READ BY ID
    public function readById($id_barang) {
        $query = "SELECT * FROM $this->table WHERE id_barang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_barang);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // UPDATE
    public function update($nama_barang, $id_kategori, $harga, $deskripsi, $gambar, $stok, $id_barang) {
        $query = "UPDATE $this->table SET nama_barang = ?, id_kategori = ?, harga = ?, deskripsi = ?, gambar = ?, stok = ? WHERE id_barang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sidssii", $nama_barang, $id_kategori, $harga, $deskripsi, $gambar, $stok, $id_barang);
        return $stmt->execute();
    }

    // DELETE
    public function delete($id_barang) {
        $query = "DELETE FROM $this->table WHERE id_barang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_barang);
        return $stmt->execute();
    }
}
?>
