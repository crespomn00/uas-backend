<?php
session_start();
include "../config/koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];


$query = "SELECT * FROM tabel_user WHERE username='$username' AND password='$password'";
$hasil = $koneksi->query($query);

$data = $hasil->fetch_assoc();

if($data){
    $_SESSION['login'] = true;
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

    if($data['role'] == 'admin'){
        header("Location: ../index.php");
    } else {
        header("Location: login.php");
    }
} else {
    echo"
    <style>
      .card {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        margin: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }
      form {
        width: 70%;
      }
      .textbox {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
      }
      .tombol-kembali {
        background-color: red;
        color: white;
        padding: 12px 24px;
        text-decoration: none;
        border-radius: 5px;
        font-family: sans-serif;
        display: inline-block;
        transition: background-color 0.3s;
        align-items: center;
        justify-content: center;
        margin-left: 75px;
      }
    </style>
    <div class='card'>
    <h1>Peringatan!</h1>
    <form method='post'>
        <div class='textbox'>
          <label>Username atau Password Anda Salah!</label>
        </div>
        <div class='all-button'>
            <button class='tombol-kembali' type='submit'><a href='../login.php'>Kembali Login</a></button>
        </div>
      </form>
      </div>
    ";
}
?>