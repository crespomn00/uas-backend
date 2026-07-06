<?php
require_once '../validator/validator_user.php';
require_once '../class/User.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$validator = new FormValidatorUser($_POST);
$validator->validateAll();

$mail = new PHPMailer(true);

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
    $email = $_POST['email'];
    $data = $user->readByUsername($_POST['username']);
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'pelanggan';

    if(!$data){
        if ($user->create($email, $nama, $username, $password, $role)) {

            $mail->isSMTP();
            $mail->Host         = 'smtp.gmail.com';
            $mail->SMTPAuth     = true;
            $mail->Username     ='portfolio.crespo@gmail.com';
            $mail->Password     ='divs xdue kkyz fwrx';
            $mail->SMTPSecure   = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port         ='587';

            $mail->setFrom($_POST['email'], $_POST['nama']);
            $mail->addAddress($_POST['email'], $_POST['nama']);
            $mail->addReplyTo($_POST['email'], $_POST['nama']);

            $mail->isHTML(true);               
            $mail->Subject = 'Konfirmasi Registrasi';  
            $mail->Body    = 'Anda telah berhasil mendaftar, Selamat Berbelanja'; 

            $mail->send();

            echo "
            <script>
                alert('Register Berhasil! Cek Notifikasi Email Anda');
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