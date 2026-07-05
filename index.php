<!doctype html>
<?php
require_once "protected.php";
?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Epicenter Music Store</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 20px;
      }
      .card {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: auto;
        display: flex;
        flex-direction: column;
      }
      .wrapper{
        display:flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        width: 100%;
        gap: 10px;
      }
      .box-1{
        display: flex;
        flex-direction: row;
        align-items: center;
        flex: 1;
        gap: 5px;
        border-radius: 5px;
      }
      .box-2{
        display: flex;
        flex-direction: row;
        align-items: center;
        border-radius: 5px;
        gap: 5px;
      }
      .logout{
        background-color: #14213d;
        margin-left: 20px;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
      }
      .outlined-box{
        background-color: #fff;
        color: #14213d;
        border: 1px solid #14213d;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
      }
      .outlined-box:hover{
        background-color: #14213d;
        color: #fff;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
      }
      .textbox {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
      }
      a{
        text-decoration: none;
      }
    </style>
  </head>
  <body>
    <div class="card">
      <div class="wrapper">
        <div class="box-1">
          <?php
            echo "<h2>Selamat datang, " . $_SESSION['user'] . "!</h2>";
          ?>
          
        </div>
        <div class="box-2">
          <form action="proses/proses_logout.php" method="post">
            <button type="submit" class="logout">Logout</button>
          </form>         
        </div>
      </div>
      <div class="wrapper">
        <div class="box-1">
          <?php 
          $user_role = $_SESSION['role'] ?? '';

          if ($user_role === 'admin') {
            echo "
            <a href=''>
              <div class='outlined-box'>
                Admin Dashboard
              </div>
            </a>
            ";
          }
          if ($user_role === 'pelanggan') {
            echo "
            <a href=''>
              <div class='outlined-box'>
                Pelanggan
              </div>
            </a>
            ";
          }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>
