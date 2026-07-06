<?php
require_once __DIR__ . '/../config/Database.php';

class Kategori extends Database {

private $table = "tabel_kategori";

public function create($nama_kategori) {
    $query = "INSERT INTO $this->table (nama_kategori) VALUES (?)";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("s", $nama_kategori);
    return $stmt->execute();
}

public function readAll() {
    $query = "SELECT * FROM $this->table ORDER BY id_kategori ASC";
    return $this->conn->query($query);
}

public function readById($id){
    $query = "SELECT * FROM $this->table WHERE id_kategori = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

public function update($nama_kategori, $id){
    $query = "UPDATE $this->table SET nama_kategori = ? WHERE id_kategori = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("si", $nama_kategori, $id);
    return $stmt->execute();
}

public function delete($id){
    $query = "DELETE FROM $this->table WHERE id_kategori = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// COUNT Kategori
    public function countKategori() {
        $query = "SELECT COUNT(*) AS total_kategori 
                FROM $this->table";

        $result = $this->conn->query($query);
        $data = $result->fetch_assoc();

        return $data['total_kategori'];
    }
}
?>