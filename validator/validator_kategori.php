<?php
class FormValidatorKategori {
    private $data;
    private $error = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function validateNamaKategori() {
        $nama_kategori = trim($this->data['nama_kategori'] ?? '');
        if (empty($nama_kategori)) {
            $this->errors['nama_kategori'] = 'Nama Kategori Wajib Diisi!';
        }elseif (strlen($nama_kategori) < 4) {
            $this->errors['nama_kategori'] = 'Nama Kategori Minimal 4 Karakter!';
        }
    }

    public function validateAll() {
        $this->validateNamaKategori();
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getErrors() {
        
    }
}
?>