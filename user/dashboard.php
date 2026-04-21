<?php
include __DIR__."/../config/session.php";
include __DIR__."/../config/koneksi.php";

$user = $_SESSION['id'];

/* VALIDASI */
$search = '';
$filter = '';
$error  = '';

if(isset($_GET['search'])){
    $search = trim($_GET['search']);

    if(strlen($search) > 50){
        $error = "Pencarian terlalu panjang!";
        $search = '';
    }

    if(!preg_match("/^[a-zA-Z0-9\s]*$/",$search)){
        $error = "Hanya huruf & angka!";
        $search = '';
    }

    $search = mysqli_real_escape_string($conn,$search);
}

if(isset($_GET['filter'])){
    $allowed = ['','tersedia','habis'];

    if(in_array($_GET['filter'],$allowed)){
        $filter = $_GET['filter'];
    }
}

/* QUERY */
$where = "WHERE 1=1";

if($search){
    $where .= " AND judul LIKE '%$search%'";
}

if($filter == "tersedia"){
    $where .= " AND stok > 0";
} elseif($filter == "habis"){
    $where .= " AND stok = 0";
}

$buku = mysqli_query($conn,"SELECT * FROM buku $where");

$pinjam = mysqli_query($conn,"
SELECT peminjaman.*, buku.judul 
FROM peminjaman 
JOIN buku ON buku.id=peminjaman.buku_id
WHERE user_id='$user' AND status='dipinjam'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>

<style>
body{
    margin:0;
    font-family:sans-serif;
    background: linear-gradient(135deg,#0f172a,#1e293b);
    color:white;
}

.topbar{
    display:flex;
    justify-content:space-between;
    padding:15px 20px;
    background:#020617;
}

.logout{
    background:#ef4444;
    padding:6px 12px;
    border-radius:8px;
    color:white;
    text-decoration:none;
}

.hero{padding:20px;}

.search-box{
    padding:0 20px;
    margin-bottom:10px;
}

.search-box form{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.search-box input,
.search-box select{
    padding:10px;
    border-radius:8px;
    border:none;
    background:#020617;
    color:white;
}

.search-box input{flex:1;}

.btn{
    padding:10px 15px;
    border:none;
    border-radius:8px;
    background:linear-gradient(135deg,#6366f1,#22c55e);
    color:white;
    cursor:pointer;
}

.error{
    margin:0 20px 10px;
    padding:10px;
    background:#ef4444;
    border-radius:8px;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
    gap:15px;
    padding:20px;
}

.card{
    background:#1e293b;
    border-radius:12px;
    overflow:hidden;
    position:relative;
}

.card img{
    width:100%;
    height:200px;
    object-fit:cover;
}

.badge{
    position:absolute;
    top:10px;
    right:10px;
    padding:5px 10px;
    border-radius:8px;
    font-size:12px;
}

.ada{background:#22c55e;}
.habis{background:#ef4444;}

.card-body{padding:10px;}

.pinjam{
    display:inline-block;
    margin-top:5px;
    padding:6px 10px;
    border-radius:6px;
    background:linear-gradient(135deg,#6366f1,#22c55e);
    color:white;
    text-decoration:none;
    font-size:12px;
}
</style>

</head>
<body>

<div class="topbar">
    <h3>📚 User Dashboard</h3>
    <a class="logout" href="../logout.php">Logout</a>
    <a href="riwayat.php">Riwayat</a>
</div>

<div class="hero">
    <h2>Selamat Datang 👋</h2>
    <p>Cari dan pinjam buku favoritmu</p>
</div>

<?php if($error){ ?>
<div class="error"><?= $error; ?></div>
<?php } ?>

<div class="search-box">
<form method="GET">

<input type="text" name="search" placeholder="Cari buku..."
value="<?= htmlspecialchars($search); ?>">

<select name="filter">
    <option value="">Semua</option>
    <option value="tersedia" <?= $filter=='tersedia'?'selected':''; ?>>Tersedia</option>
    <option value="habis" <?= $filter=='habis'?'selected':''; ?>>Habis</option>
</select>

<button class="btn">Cari</button>

</form>
</div>

<h3 style="padding-left:20px;">📖 Daftar Buku</h3>

<div class="grid">

<?php while($b=mysqli_fetch_assoc($buku)){ ?>
<div class="card">

<img src="../assets/img/<?= $b['gambar'] ?: 'noimage.png'; ?>">

<div class="badge <?= $b['stok']>0?'ada':'habis'; ?>">
<?= $b['stok']>0 ? 'Stok: '.$b['stok'] : 'Habis'; ?>
</div>

<div class="card-body">
<h3><?= htmlspecialchars($b['judul']); ?></h3>
<p>✍ <?= htmlspecialchars($b['penulis']); ?></p>

<?php if($b['stok']>0){ ?>
<a class="pinjam" href="pinjam.php?id=<?= $b['id']; ?>">📥 Pinjam</a>
<?php } else { ?>
<p style="color:#ef4444;font-size:12px;">Tidak tersedia</p>
<?php } ?>

</div>
</div>
<?php } ?>

</div>

<h3 style="padding-left:20px;">📦 Buku Dipinjam</h3>

<div class="grid">

<?php while($p=mysqli_fetch_assoc($pinjam)){ ?>
<div class="card">
<div class="card-body">
<h3><?= htmlspecialchars($p['judul']); ?></h3>

<a class="pinjam" href="kembali.php?id=<?= $p['id']; ?>">
🔄 Kembalikan
</a>
</div>
</div>
<?php } ?>

</div>

</body>
</html>