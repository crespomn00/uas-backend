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
                <li class="active">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </li>
                <li>
                    <i class="fa-solid fa-box"></i>
                    <span><a href="data-barang.php" style="text-decoration: none; color: white;">Data Barang</a></span>
                </li>
                <li>
                    <i class="fa-solid fa-layer-group"></i>
                    <span><a href="data-barang.php" style="text-decoration: none; color: white;">Data Kategori</a></span>
                </li>
                <li>
                    <i class="fa-solid fa-users"></i>
                    <span><a href="data-user.php" style="text-decoration: none; color: white;">Data User</a></span>
                </li>

                <li>
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    <span><a href="data-barang.php" style="text-decoration: none; color: white;">Data Transaksi</a></span>
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
            <h1>Dashboard</h1>
            <p>Selamat datang kembali, Admin.</p>
        </div>
        <div class="profile">
            <i class="fa-solid fa-bell"></i>
            <div class="avatar">A</div>
        </div>
    </header>
    <!-- Cards -->

    <section class="cards">
        <div class="card">
            <i class="fa-solid fa-box"></i>
            <h2>1.254</h2>
            <p>Total Barang</p>
        </div>
        <div class="card">
            <i class="fa-solid fa-layer-group"></i>
            <h2>18</h2>
            <p>Kategori</p>
        </div>
        <div class="card">
            <i class="fa-solid fa-users"></i>
            <h2>52</h2>
            <p>User</p>
        </div>
        <div class="card">
            <i class="fa-solid fa-cart-shopping"></i>
            <h2>3.428</h2>
            <p>Transaksi</p>
        </div>
    </section>
    </main>
    </div>
<script src="js/script.js"></script>

</body>
</html>