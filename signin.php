<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Signup</title>
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
    </style>
  </head>
  <body>
    <div class="card">
      <h1>Form Signup</h1>
      <form action="actions/proses_signin.php" method="post" id="form">
        <div class="textbox">
          <label>Username:</label>
          <input type="text" name="username" />
        </div>
        <div class="textbox">
          <label>Password:</label>
          <input type="password" name="password" />
        </div>
        <div class="textbox">
          <label>Confirm Password:</label>
          <input type="password" name="confirm_password" />
        </div>
        <button type="submit">Signup</button>
      </form>
    </div>
  </body>
</html>
