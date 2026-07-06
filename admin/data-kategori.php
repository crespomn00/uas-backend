<?php
require_once __DIR__ . '/../protected.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Poppins,sans-serif;
}

body{
    background:#f4f4f4;
    color:#111;
}

.container{
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */

.sidebar{
    width:250px;
    background:#111;
    color:#fff;
    padding:30px 0;
    display:flex;
    flex-direction:column;
}

.logo{
    text-align:center;
    margin-bottom:40px;
}

.logo h2{
    letter-spacing:4px;
}

.sidebar ul{
    list-style:none;
}

.sidebar li{
    padding:18px 30px;
    display:flex;
    gap:15px;
    cursor:pointer;
    transition:.3s;
}

.sidebar li:hover,
.sidebar .active{
    background:#fff;
    color:#111;
}

.logout{
    margin-top:30px;
    border-top:1px solid rgba(255,255,255,.15);
}

/* MAIN */

main{
    flex:1;
    padding:40px;
}

/* HEADER */
header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:40px;
}

.profile{
    display:flex;
    align-items:center;
    gap:20px;
}

.avatar{
    width:45px;
    height:45px;
    background:#111;
    color:white;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    font-weight:bold;

}

/* CARD */

.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:25px;
    margin-bottom:35px;
}

.card{
    background:white;
    padding:25px;
    border-radius:15px;
    border:2px solid #111;
    transition:.3s;
}

.card:hover{
    background:#111;
    color:white;
    transform:translateY(-6px);
}

.card i{
    font-size:30px;
    margin-bottom:20px;
}

.card h2{
    margin-bottom:10px;
}

/* TABLE */
.table-card{
    background:white;
    padding:25px;
    border-radius:15px;
    overflow:auto;
}

.table-header{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
}

button{
    background:#111;
    color:white;
    border:none;
    padding:12px 18px;
    border-radius:8px;
    cursor:pointer;
}

button:hover{
    background:#333;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#111;
    color:white;
    padding:15px;
}

td{
    padding:15px;
    border-bottom:1px solid #ddd;
}

.done{
    background:#111;
    color:white;
    padding:5px 12px;
    border-radius:20px;
}

.pending{
    background:#ddd;
    padding:5px 12px;
    border-radius:20px;
}

.wrapper {
            width: 100%;
            gap: 20px;
        }
        .card-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            min-height: 100px;
            margin: auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }
        .card-table {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            height: 600px;
            margin: auto;
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        form {
            width: 100%;
        }
        .textbox {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
        }
        input, textarea, select {
            padding: 12px 12px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            outline: none;
        }
        .table-container {
            width: 100%;
            height: 500px;
            overflow-y: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: left;
            font-size: 12pt;
            font-weight: 300;
            line-height: 1.5;
            vertical-align: top;
        }
        th {
            position: sticky;
            top: 0;
            z-index: 1;
            background: #000000;
            color: white;
        }
        .danger {
            color: red;
            font-weight: bold;
        }
        .error {
            color: red;
            font-size: 12px;
            margin-top: 4px;
        }
        .preview {
            max-width: 120px;
            height: auto;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

/* RESPONSIVE */

@media(max-width:1000px){

.cards{
    grid-template-columns:repeat(2,1fr);
}

}
@media(max-width:768px){

.container{
    flex-direction:column;
}
.sidebar{
    width:100%;
    padding:20px;
}

.sidebar ul{
    display:flex;
    flex-wrap:wrp;
    justify-content:center;
    gap:10px;
}

.sidebar li{
    padding:10px 15px;
    border-radius:8px;
}

main{
    padding:20px;
}

.cards{
    grid-template-columns:1fr;
}

header{
    flex-direction:column;
    align-items:flex-start;
    gap:20px;
}
}
</style>

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
/>

</head>

<body>
    <div class="container">
    <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <h2>ADMIN</h2>
            </div>
            <ul>
                <li>
                    <i class="fa-solid fa-house"></i>
                    <span><a href="dashboard.php" style="text-decoration: none; color: white;">Dashboard</a></span>
                </li>
                <li>
                    <i class="fa-solid fa-box"></i>
                    <span><a href="data-barang.php" style="text-decoration: none; color: white;">Data Barang</a></span>
                </li>
                <li class="active">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Data Kategori</span>
                </li>
                <li>
                    <i class="fa-solid fa-users"></i>
                    <span><a href="data-user.php" style="text-decoration: none; color: white;">Data User</a></span>
                </li>

                <li>
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    <span><a href="kelola_transaksi.php" style="text-decoration: none; color: white;">Data Transaksi</a></span>
                </li>

                <form action="../proses/proses_logout.php" method="post">
                    <button type="submit" class="logout" style="margin-left: 30px; background: red;">Logout</button>
                </form>
            </ul>
        </aside>

    <!-- Main -->
    <main>
    <header>
        <div>
            <h1>Edit Data Kategori</h1>
            <p>Halaman edit data kategori</p>
        </div>
        <div class="profile">
            <i class="fa-solid fa-bell"></i>
            <div class="avatar">A</div>
        </div>
    </header>
    <!-- Cards -->

        <div class="wrapper">
          <div class="card-form">
            <h2>Form Kategori</h2>
            <?php
            require_once "../class/Kategori.php";
            $kategori = new Kategori();
            $editData = null;
            if (isset($_GET['edit'])) {
                $idEdit = $_GET['edit'];
                $editData = $kategori->readById($idEdit);
                if($editData) {
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                document.querySelector('input[name=\"id_kategori\"]').value = '{$editData['id_kategori']}';
                                document.querySelector('input[name=\"nama_kategori\"]').value = '{$editData['nama_kategori']}';
                            });
                          </script>";
                } else {
                    echo "<script>
                            alert('Data tidak ditemukan!');
                            window.location.href = 'data-kategori.php';
                          </script>";
                }
            }
            ?>
            <form action="../proses/proses_kategori.php" method="POST" id="form">
              <input type="hidden" name="id_kategori" />
              <div class="textbox">
                <label>Nama Produk</label>
                <input type="text" name="nama_kategori" required/>
                <div id="error-nama" style="color: red"></div>
              </div>
              <button type="submit">Simpan</button>
            </form>
          </div>

          <div class="card-table">
            <h2>Data Produk</h2>
            <div class="table-container">    
              <table>
                  <thead>
                  <tr>
                      <th>ID Kategori</th>
                      <th>Nama Kategori</th>
                      <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  require_once "../class/Kategori.php";
                  $kategori = new Kategori();
                  $data = $kategori->readAll();
                  
                  if ($data->num_rows > 0) {
                      while ($row = $data->fetch_assoc()) {
                          echo "
                          <tr>
                              <td>{$row['id_kategori']}</td>
                              <td>{$row['nama_kategori']}</td>
                              <td>
                                <button style='background: #B1D3B9;'><a href='?edit={$row['id_kategori']}' style='color: white; text-decoration: none;'>Edit</a></button>
                                <button style='background: #F26076;'><a href='../proses/proses_kategori.php?hapus={$row['id_kategori']}' onclick=\"return confirm('Yakin ingin menghapus data ini?')\" style='color: white; text-decoration: none;'>Hapus</a></button>
                              </td>
                          </tr>";
                      }
                  } else {
                      echo "<tr><td colspan='3'>Belum ada data</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
    </main>
    </div>
<script>
    document.getElementById("form").addEventListener("submit", function (e) {

          e.preventDefault();

          fetch("../proses/proses_kategori.php", {
              method: "POST",
              body: new FormData(this),
          })
          .then((res) => res.json())
          .then((dataObj) => {
            // Reset error
            document.getElementById("error-nama").innerHTML = "";

            // Jika sukses
            if (dataObj.success) {
                alert(dataObj.message);
                window.location.href = "data-kategori.php";
                return;
            }

            // Jika validasi error
            if (dataObj.nama_kategori) {
                document.getElementById("error-nama").innerHTML =
                    dataObj.nama_kategori;
            }


            // Jika gagal insert/update
            if (dataObj.message) {
                alert(dataObj.message);
            }
        })
        .catch((err) => {
            console.log(err);
            alert("Terjadi kesalahan sistem!");
        });
      });
</script>

</body>
</html>