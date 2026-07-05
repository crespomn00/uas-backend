  <!DOCTYPE html>
  <!-- <?php
//   require_once "protected.php";
  ?> -->
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Inventaris Produk</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          margin: 20px;
          padding: 0;
          display: flex;
          justify-content: center;
        }
        .wrapper{
          width: 80%;
          display: flex;
          flex-direction: row;
          gap: 20px;
        }
        .card-form {
          background-color: #f9f9f9;
          padding: 20px;
          border-radius: 5px;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
          width: 30%;
          height: 600px;
          margin: auto;
          display: flex;
          flex-direction: column;
          justify-content: start;
          align-items: center;
        }
        .card-table {
          background-color: #f9f9f9;
          padding: 20px;
          border-radius: 5px;
          box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
          width: 70%;
          height: 600px;
          margin: auto;
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

        input,textarea,select {
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

        table,
        th,
        td {
          border: 1px solid #ccc;
        }

        th,
        td {
          padding: 12px;
          text-align: left;
          font-size: 12pt;
          font-weight: 300;
          line-height: 1.5;
        }

        th {
          position: sticky;
          top: 0;
          z-index: 1;
          background: #1e3a8a;
          color: white;
        }
        .danger{
          color: red;
        }
      </style>

  </head>
  <body>
      <div class="wrapper">
          <div class="card-form">
            <a href="index.php">Kembali</a>
            <h2>Form Kategori</h2>
            <?php
            require_once "class/Kategori.php";
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
                            window.location.href = 'form_kategori.php';
                          </script>";
                }
            }
            ?>
            <form action="proses/proses_kategori.php" method="POST" id="form">
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
                  require_once "class/Kategori.php";
                  $kategori = new Kategori();
                  $data = $kategori->readAll();
                  
                  if ($data->num_rows > 0) {
                      while ($row = $data->fetch_assoc()) {
                          echo "
                          <tr>
                              <td>{$row['id_kategori']}</td>
                              <td>{$row['nama_kategori']}</td>
                              <td>
                                //   <a href='?edit={$row['id_kategori']}'>Edit</a>
                                //   <a href='proses/proses_kategori.php?hapus={$row['id_kategori']}' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
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

      <script>
          document.getElementById("form").addEventListener("submit", function (e) {

          e.preventDefault();

          fetch("proses/proses_kategori.php", {
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
                window.location.href = "form_kategori.php";
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