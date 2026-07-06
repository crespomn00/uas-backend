<?php
class FormValidatorTransaksi
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
        return !empty($this->data['id_transaksi']);
    }

    public function validateQtyTransaksi()
    {
        $qty = trim($this->data['qty'] ?? '');

        if ($qty === '') {
            $this->errors['qty'] = 'Jumlah barang wajib diisi.';
        } elseif (!ctype_digit($qty)) {
            $this->errors['qty'] = 'Jumlah barang harus berupa angka bulat.';
        } elseif ((int) $qty < 0) {
            $this->errors['qty'] = 'Jumlah barang tidak boleh bernilai negatif.';
        }
    }

    public function validateAlamatTransaksi()
    {
        $alamat = trim($this->data['alamat'] ?? '');

        if ($alamat === '') {
            $this->errors['alamat'] = 'Alamat wajib diisi.';
        } elseif (strlen($alamat) < 6) {
            $this->errors['alamat'] = 'Alamat minimal 6 karakter.';
        }
    }

    public function validateNoHP()
    {
        $no_hp = trim($this->data['no_hp'] ?? '');

        if ($no_hp === '') {
            $this->errors['no_hp'] = 'Nomor HP wajib diisi.';
        } elseif (strlen($no_hp) < 10) {
            $this->errors['no_hp'] = 'Nomor HP minimal 10 karakter.';
        }
    }

    public function validateMetodePembayaran()
    {
        $metode_pembayaran = trim($this->data['metode_pembayaran'] ?? '');

        if ($metode_pembayaran === '') {
            $this->errors['metode_pembayaran'] = 'Metode pembayaran wajib dipilih.';
        } 
    }

    public function validateBuktiBayar()
    {
        $bukti_pembayaran = $this->files['bukti_pembayaran'] ?? null;

        // Saat edit, gambar boleh dikosongkan agar memakai gambar lama.
        if (!$bukti_pembayaran || !isset($bukti_pembayaran['error']) || $bukti_pembayaran['error'] === UPLOAD_ERR_NO_FILE) {
            if (!$this->isEditMode()) {
                $this->errors['bukti_pembayaran'] = 'Bukti pembayaran wajib diunggah.';
            }
            return;
        }

        if ($bukti_pembayaran['error'] !== UPLOAD_ERR_OK) {
            $this->errors['bukti_pembayaran'] = 'Terjadi kesalahan saat mengunggah bukti pembayaran.';
            return;
        }

        if (empty($bukti_pembayaran['tmp_name']) || !file_exists($bukti_pembayaran['tmp_name'])) {
            $this->errors['bukti_pembayaran'] = 'File bukti pembayaran tidak ditemukan.';
            return;
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png'];
        $mimeType = mime_content_type($bukti_pembayaran['tmp_name']);

        if (!in_array($mimeType, $allowedMimeTypes, true)) {
            $this->errors['bukti_pembayaran'] = 'Format bukti pembayaran harus berupa JPG, JPEG, atau PNG.';
        } elseif ($bukti_pembayaran['size'] > (10 * 1024 * 1024)) {
            $this->errors['bukti_pembayaran'] = 'Ukuran bukti pembayaran maksimal 10 MB.';
        }
    }

    

    public function validateAll()
    {
        $this->validateQtyTransaksi();
        $this->validateAlamatTransaksi();
        $this->validateNoHP();
        $this->validateMetodePembayaran();
        $this->validateBuktiBayar();
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
