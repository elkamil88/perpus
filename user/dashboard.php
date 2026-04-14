<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['id'];

/* SEARCH */
$search = isset($_GET['search']) ? $_GET['search'] : '';

$buku = mysqli_query($koneksi,"
    SELECT * FROM buku 
    WHERE judul LIKE '%$search%'
");

$pinjam = mysqli_query($koneksi,"
SELECT peminjaman.*, buku.judul 
FROM peminjaman 
JOIN buku ON buku.id=peminjaman.buku_id
WHERE peminjaman.user_id='$user_id'
AND peminjaman.status='dipinjam'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Inter;
}

body{
    background: linear-gradient(135deg,#0f172a,#1e1b4b);
    color:white;
}

/* TOPBAR */
.topbar{
    padding:15px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;

    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    border-bottom:1px solid rgba(255,255,255,0.1);
}

.logout{
    background:#ef4444;
    padding:8px 12px;
    border-radius:10px;
    color:white;
    text-decoration:none;
}

/* HERO */
.hero{
    padding:25px;
    text-align:center;
}

.hero h1{
    font-size:22px;
}

.hero p{
    font-size:13px;
    color:#94a3b8;
    margin-top:5px;
}

/* SEARCH */
.search-box{
    display:flex;
    justify-content:center;
    margin-top:15px;
}

.search-box input{
    width:300px;
    padding:10px;
    border-radius:10px;
    border:none;
    outline:none;
    background:#1e293b;
    color:white;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:15px;
    padding:20px;
}

/* CARD */
.card{
    position:relative;
    border-radius:15px;
    overflow:hidden;
    height:260px;

    background:#1e293b;
    transition:0.3s;
    border:1px solid rgba(255,255,255,0.1);
}

.card:hover{
    transform:translateY(-8px);
}

.card img{
    width:100%;
    height:100%;
    object-fit:cover;
    filter:brightness(0.8);
}

.overlay{
    position:absolute;
    bottom:0;
    width:100%;
    padding:10px;

    background:linear-gradient(to top,rgba(0,0,0,0.9),transparent);
}

/* BADGE */
.badge{
    position:absolute;
    top:10px;
    right:10px;
    background:#22c55e;
    padding:5px 8px;
    border-radius:8px;
    font-size:11px;
}

/* BUTTON */
.btn{
    display:inline-block;
    margin-top:5px;
    padding:6px 10px;
    border-radius:8px;
    background:linear-gradient(135deg,#6366f1,#22c55e);
    color:white;
    text-decoration:none;
    font-size:12px;
}
</style>

</head>
<body>

<!-- TOP -->
<div class="topbar">
    <h3>📚 Library User</h3>
    <a class="logout" href="../logout.php">Logout</a>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Selamat Datang 👋</h1>
    <p>Temukan dan pinjam buku favoritmu</p>

    <form class="search-box" method="GET">
        <input type="text" name="search" placeholder="Cari buku..." value="<?= $search; ?>">
    </form>
</div>

<!-- BUKU -->
<div class="grid">

<?php while($b=mysqli_fetch_assoc($buku)){ ?>

<div class="card">

    <img src="<?= $b['gambar']; ?>">

    <div class="badge">
        Stok: <?= $b['stok']; ?>
    </div>

    <div class="overlay">
        <h3><?= $b['judul']; ?></h3>
        <p style="font-size:12px; color:#cbd5e1;">
            ✍ <?= $b['penulis']; ?>
        </p>

        <?php if($b['stok'] > 0){ ?>
            <a class="btn" href="pinjam.php?id=<?= $b['id']; ?>">
                📥 Pinjam
            </a>
        <?php } else { ?>
            <span style="font-size:11px;color:#f87171;">Habis</span>
        <?php } ?>

    </div>

</div>

<?php } ?>

</div>

<!-- PINJAMAN -->
<div style="padding:20px;">
<h3>📦 Buku Dipinjam</h3>
</div>

<div class="grid">

<?php while($p=mysqli_fetch_assoc($pinjam)){ ?>

<div class="card">

    <div class="overlay">
        <h3><?= $p['judul']; ?></h3>

        <a class="btn" href="kembali.php?id=<?= $p['id']; ?>">
            🔄 Kembalikan
        </a>
    </div>

</div>

<?php } ?>

</div>

</body>
</html>