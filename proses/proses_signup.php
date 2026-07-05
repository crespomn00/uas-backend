<?php
require_once '../validator/validator_user.php';
require_once '../class/User.php';
$validator = new FormValidatorUser($_POST);
$validator->validateAll();
$user = new User();

if ($validator->hasErrors()) {
    foreach ($validator->getErrors() as $field => $error) {
        $errors[$field] = $error;
    }
    // Tampilkan sebagai JSON (object)
    header('Content-Type: application/json');
    echo json_encode($errors);
}
else {
    $data = $user->readByUsername($_POST['username']);
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'pelanggan';

    if(!$data){
        if ($user->create($nama, $username, $password, $role)) {
            echo "
            <script>
                alert('Register berhasil!');
                window.location.href = '../login.php';
            </script>
            ";
        } else {
            echo "
            <script>
                alert('Register gagal!');
            </script>
            ";
        }
    }else{
        echo "
        <script>
            alert('Username sudah terdaftar!');
            window.location.href = '../login.php';
        </script>
        ";
    }

}
?>