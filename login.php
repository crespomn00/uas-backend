
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
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

    .login-container{
        width:100%;
        display:flex;
        justify-content:center;
        padding:20px;
    }

    .login-card{
        background:#fff;
        width:400px;
        padding:40px;
        border-radius:12px;
        border:2px solid #111;
        box-shadow:0 10px 25px rgba(0,0,0,.15);
    }

    .login-card h1{
        text-align:center;
        color:#111;
        margin-bottom:10px;
    }

    .login-card p{
        text-align:center;
        color:#666;
        margin-bottom:30px;
    }

    .input-group{
        margin-bottom:20px;
    }

    .input-group label{
        display:block;
        margin-bottom:8px;
        font-weight:bold;
    }

    .input-group input{
        width:100%;
        padding:12px;
        border:1px solid #999;
        border-radius:8px;
        outline:none;
        transition:.3s;
    }

    .input-group input:focus{
        border-color:#111;
        box-shadow:0 0 5px rgba(0,0,0,.3);
    }

    .button-group{
        display:flex;
        gap:10px;
        margin-top:25px;
    }

    .btn-login,
    .btn-home{
        flex:1;
        text-align:center;
        padding:12px;
        border:none;
        border-radius:8px;
        text-decoration:none;
        cursor:pointer;
        font-size:16px;
        transition:.3s;
    }

    .btn-login{
        background:#111;
        color:#fff;
    }

    .btn-login:hover{
        background:#333;
    }

    .btn-home{
        background:#fff;
        color:#111;
        border:2px solid #111;
    }

    .btn-home:hover{
        background:#111;
        color:#fff;
    }

    @media(max-width:480px){

        .login-card{
            width:100%;
            padding:30px 20px;
        }

        .button-group{
            flex-direction:column;
        }

    }
    </style>
  </head>
  <body>
    <div class="login-container">
    <div class="login-card">
        <h1>FORM LOGIN</h1>
        <p>Silakan masuk untuk melanjutkan</p>
        <form action="proses/proses_login.php" method="POST">
            <div class="input-group">
                <label>Username</label>
                <input
                    type="text"
                    name="username"
                    placeholder="Masukkan username"
                    required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-login">
                    Login
                </button>
                <a href="halaman_login.html" class="btn-home">
                    Beranda
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
