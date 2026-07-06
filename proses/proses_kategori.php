<?php
require_once"../class/Kategori.php";
require_once"../validator/validator_kategori.php";

$kategori = new Kategori();

if (isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    if ($kategori->delete($id)){
        echo "
        <script>
            alert('Data Berhasil Dihapus!');
            window.location.href='../admin/data-kategori.php';
        </script>";
    } else {
        echo "
        <script>
            alert('Data Gagal Dihapus!');
            window.location.href='../admin/data-kategori.php';
        <script>";
    }
} else{
    $validator = new FormValidatorKategori($_POST);
    $validator->validateAll();

    if($validator->hasErrors()) {
        foreach ($validator->getErrors() as $field => $error) {
            $errors[$field] = $error;
        }

            header('Content-Type: application/json');
            echo json_encode($errors);
    }else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id_kategori'];
            $nama_kategori = $_POST['nama_kategori'];

            $data = $kategori->readById($id);

            if($data){
                if ($kategori->update($nama_kategori, $id)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Data Berhasil Diedit'
                    ]);
                } else {
                    echo "Data Gagal Diedit!";
                }
            } else{
                if ($kategori->create($nama_kategori)){
                    echo json_encode([
                        'success' => true,
                        'message' => 'Data Berhasil Ditambah',
                    ]);
                }else {
                    echo json_decode([
                        'success' => false,
                        'message' => 'Data Gagal Ditambah',
                    ]);
                }
            }
        }
    }
}
?>