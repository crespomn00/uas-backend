
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 20px;
        margin-top: 350px;
      }
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
      .tombol-login {
        background-color: green;
        color: white;
        padding: 12px 24px;
        text-decoration: none;
        border-radius: 5px;
        font-family: sans-serif;
        display: inline-block;
        transition: background-color 0.3s;
        align-items: center;
      }
      .tombol-beranda {
        background-color: white;
        color: black;
        padding: 12px 24px;
        text-decoration: none;
        border-radius: 5px;
        font-family: sans-serif;
        display: inline-block;
        transition: background-color 0.3s;
        align-items: center;
      }
      .all-button{
        text-align: center;

      }
    </style>
  </head>
  <body>
    <div class="card">
      <h1>Form Login</h1>
      <form action="proses/proses_login.php" method="post">
        <div class="textbox">
          <label>Username:</label>
          <input type="text" name="username" required/>
        </div>
        <div class="textbox">
          <label>Password:</label>
          <input type="password" name="password" required/>
        </div>
        <div class="all-button">
            <button class="tombol-login" type="submit">Login</button>
            <button class="tombol-beranda"><a href="halaman_login.html">Beranda</a></button>
        </div>
      </form>
    </div>
  </body>
</html>
