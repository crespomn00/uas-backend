<?php
require_once __DIR__ . '/../config/Database.php';

class User extends Database {

    private $table = "tabel_user";

    // CREATE
    public function create($nama, $username, $password, $role) {
        $query = "INSERT INTO $this->table (nama_user, username, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $nama, $username, $password, $role);
        return $stmt->execute();
    }

    // READ ALL
    public function readAll($id_user) {
        $query = "SELECT * FROM $this->table WHERE id_user != ? ORDER BY id_user ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function readAllPelanggan() {
        $query = "SELECT * FROM $this->table WHERE role = 'pelanggan' ORDER BY id_user ASC";
        return $this->conn->query($query);
    }

    // READ BY USERNAME
    public function readByUsername($username) {
        $query = "SELECT * FROM $this->table WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    // READ BY ID
    public function readById($id_user) {
        $query = "SELECT * FROM $this->table WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // UPDATE
    public function update($username, $role, $id_user) {
        $query = "UPDATE $this->table SET username = ?, role = ? WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $username, $role, $id_user);
        return $stmt->execute();
    }

    // DELETE
    public function delete($id_user) {
        $query = "DELETE FROM $this->table WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        return $stmt->execute();
    }
}
?>
