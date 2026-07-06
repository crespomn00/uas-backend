<?php
class FormValidatorBarang
{
    private $data;
    private $files;
    private $errors = [];

    public function __construct(array $data, array $files = [])
    {
        $this->data = $data;
        $this->files = $files;
    }

    private function isEditMode()
    {
        return !empty($this->data['id_barang']);
    }

    public function validateNamaBarang()
    {
        $nama_barang = trim($this->data['nama_barang'] ?? '');

        if ($nama_barang === '') {
            $this->errors['nama_barang'] = 'Nama barang wajib diisi.';
        } elseif (strlen($nama_barang) < 4) {
            $this->errors['nama_barang'] = 'Nama barang minimal 4 karakter.';
        }
    }

    public function validateKategoriBarang()
    {
        $id_kategori = trim($this->data['id_kategori'] ?? '');

        if ($id_kategori === '') {
            $this->errors['id_kategori'] = 'Kategori barang wajib dipilih.';
        } elseif (!ctype_digit($id_kategori)) {
            $this->errors['id_kategori'] = 'Kategori barang tidak valid.';
        }
    }

    public function validateHargaBarang()
    {
        $harga = trim($this->data['harga'] ?? '');

        if ($harga === '') {
            $this->errors['harga'] = 'Harga barang wajib diisi.';
        } elseif (!is_numeric($harga)) {
            $this->errors['harga'] = 'Harga barang harus berupa angka.';
        } elseif ((float) $harga < 0) {
            $this->errors['harga'] = 'Harga barang tidak boleh bernilai negatif.';
        }
    }

    public function validateDeskripsiBarang()
    {
        $deskripsi = trim($this->data['deskripsi'] ?? '');

        if ($deskripsi === '') {
            $this->errors['deskripsi'] = 'Deskripsi barang wajib diisi.';
        } elseif (strlen($deskripsi) < 6) {
            $this->errors['deskripsi'] = 'Deskripsi barang minimal 6 karakter.';
        }
    }

    public function validateGambarBarang()
    {
        $gambar = $this->files['gambar'] ?? null;

        // Saat edit, gambar boleh dikosongkan agar memakai gambar lama.
        if (!$gambar || !isset($gambar['error']) || $gambar['error'] === UPLOAD_ERR_NO_FILE) {
            if (!$this->isEditMode()) {
                $this->errors['gambar'] = 'Gambar barang wajib diunggah.';
            }
            return;
        }

        if ($gambar['error'] !== UPLOAD_ERR_OK) {
            $this->errors['gambar'] = 'Terjadi kesalahan saat mengunggah gambar.';
            return;
        }

        if (empty($gambar['tmp_name']) || !file_exists($gambar['tmp_name'])) {
            $this->errors['gambar'] = 'File gambar tidak ditemukan.';
            return;
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png'];
        $mimeType = mime_content_type($gambar['tmp_name']);

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            $this->errors['gambar'] = 'Format gambar harus berupa JPG, JPEG, atau PNG.';
        } elseif ($gambar['size'] > (10 * 1024 * 1024)) {
            $this->errors['gambar'] = 'Ukuran gambar maksimal 10 MB.';
        }
    }

    public function validateStokBarang()
    {
        $stok = trim($this->data['stok'] ?? '');

        if ($stok === '') {
            $this->errors['stok'] = 'Stok barang wajib diisi.';
        } elseif (!ctype_digit($stok)) {
            $this->errors['stok'] = 'Stok barang harus berupa angka bulat.';
        } elseif ((int) $stok < 0) {
            $this->errors['stok'] = 'Stok barang tidak boleh bernilai negatif.';
        }
    }

    public function validateAll()
    {
        $this->validateNamaBarang();
        $this->validateKategoriBarang();
        $this->validateHargaBarang();
        $this->validateDeskripsiBarang();
        $this->validateGambarBarang();
        $this->validateStokBarang();
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
