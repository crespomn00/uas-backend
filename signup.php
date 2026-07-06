<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Signup</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}
body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#000,#2b2b2b,#ffffff);
    padding:20px;
}

.container{
    width:100%;
    max-width:450px;
    background:rgba(255,255,255,.95);
    padding:40px;
    border-radius:20px;
    box-shadow:0 20px 40px rgba(0,0,0,.25);
}

.container h1{
    text-align:center;
    margin-bottom:10px;
    font-size:38px;
    color:#111;
}

.container p{
    text-align:center;
    color:#666;
    margin-bottom:35px;
}

.input-group{
    margin-bottom:20px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#222;
}

.input-box input{
    width:100%;
    padding:15px 15px 15px 15px;
    border:2px solid #ddd;
    border-radius:12px;
    outline:none;
    font-size:16px;
    transition:.3s;
}

.input-box input:focus{
    border-color:#000;
    box-shadow:0 0 15px rgba(0,0,0,.15);
}

button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:12px;
    background:#000;
    color:white;
    font-size:17px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#333;
    transform:translateY(-2px);
}

.footer{
    text-align:center;
    margin-top:20px;
    color:#555;
}

.footer a{
    text-decoration:none;
    color:#000;
    font-weight:bold;
}

.footer a:hover{
    text-decoration:underline;
}

@media(max-width:500px){

.container{
    padding:30px 20px;
}

.container h1{
    font-size:30px;
}

}

</style>

</head>
<body>

<div class="container">

  <h1>Sign Up</h1>
  <p>Buat akun baru untuk melanjutkan</p>

  <form action="proses/proses_signup.php" method="POST">

    <div class="input-group">
      <label>Email</label>
        <div class="input-box">
          <input type="email" name="email" placeholder="Masukkan email" required>
        </div>
    </div>

    <div class="input-group">
      <label>Nama Lengkap</label>
        <div class="input-box">
          <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
        </div>
    </div>

    <div class="input-group">
      <label>Username</label>
        <div class="input-box">
          <input type="text" name="username" placeholder="Masukkan username" required>
        </div>
    </div>

    <div class="input-group">
      <label>Password</label>
        <div class="input-box">
          <input type="password" name="password" placeholder="Masukkan password" required>
        </div>
    </div>

    <div class="input-group">
      <label>Konfirmasi Password</label>
        <div class="input-box">
          <input type="password" name="confirm_password" placeholder="Ulangi password" required>
        </div>
    </div>

    <button type="submit">Daftar Sekarang</button>
  </form>

  <div class="footer">Sudah punya akun?<a href="login.php">Login</a></div>
</div>

</body>
</html>