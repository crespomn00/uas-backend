<?php
session_start();
require_once "../validator/validator_login.php";
require_once "../class/User.php";
$validator = new FormValidatorLogin($_POST);
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

    echo "<script>console.log('Data user: " . json_encode($data) . "');</script>";
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($data){
        if (password_verify($password, $data['password'])) {
            session_regenerate_id(true);

            $_SESSION['login'] = true;
            $_SESSION['user'] = $username;
            $_SESSION['role'] = $data['role'];
            $_SESSION['user_id'] = $data['id_user'];

            if (!isset($_SESSION['expire'])) {
                $_SESSION['expire'] = time() + 3600;
            }

            header("Location: ../index.php");
            exit();
        } else {
            echo "
            <script>
                alert('Username atau password salah!');
                window.location.href = '../login.php';
            </script>
            ";
        }
    }else{
        echo "
        <script>
            alert('User belum terdaftar!');
            window.location.href = '../signup.php';
        </script>
        ";
    }

}
?>