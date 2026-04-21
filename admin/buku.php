<?php
include __DIR__."/../config/session.php";
include __DIR__."/../config/koneksi.php";

$data = mysqli_query($conn,"SELECT * FROM buku");
?>

<!DOCTYPE html>
<html>
<head>
<title>Kelola Buku</title>

<style>
body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg,#0f172a,#1e293b);
    color:white;
}

/* TOPBAR */
.topbar{
    display:flex;
    justify-content:space-between;
    padding:15px 20px;
    background:#020617;
}

.menu a{
    margin-right:15px;
    text-decoration:none;
    color:#94a3b8;
}

.menu a:hover{color:white;}

.logout{
    background:#ef4444;
    padding:6px 12px;
    border-radius:8px;
    color:white;
    text-decoration:none;
}

/* HEADER */
.header{
    padding:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.btn-add{
    background:linear-gradient(135deg,#22c55e,#4ade80);
    padding:8px 14px;
    border-radius:8px;
    color:white;
    text-decoration:none;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:20px;
    padding:20px;
}

/* CARD */
.card{
    background:#1e293b;
    border-radius:15px;
    overflow:hidden;
    transition:0.3s;
    position:relative;
}

.card:hover{
    transform:translateY(-8px) scale(1.02);
}

/* IMAGE */
.card img{
    width:100%;
    height:220px;
    object-fit:cover;
}

/* BADGE */
.badge{
    position:absolute;
    top:10px;
    right:10px;
    padding:5px 10px;
    border-radius:10px;
    font-size:12px;
}

.stok-aman{background:#22c55e;}
.stok-habis{background:#ef4444;}

/* BODY */
.card-body{
    padding:12px;
}

.card-body h3{
    margin:0;
    font-size:16px;
}

.card-body p{
    font-size:12px;
    color:#94a3b8;
}

/* BUTTON */
.actions{
    margin-top:10px;
    display:flex;
    justify-content:space-between;
}

.btn{
    padding:5px 10px;
    border-radius:6px;
    font-size:12px;
    text-decoration:none;
}

.edit{background:#3b82f6;color:white;}
.hapus{background:#ef4444;color:white;}
</style>

</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="buku.php">Buku</a>
        <a href="peminjaman.php">Peminjaman</a>
    </div>
    <a class="logout" href="../logout.php">Logout</a>
</div>

<!-- HEADER -->
<div class="header">
    <h2>📚 Kelola Buku</h2>
    <a class="btn-add" href="tambah_buku.php">+ Tambah Buku</a>
</div>

<!-- GRID -->
<div class="grid">

<?php while($b=mysqli_fetch_assoc($data)){ ?>

<div class="card">

    <img src="../assets/img/<?= $b['gambar'] ?: 'noimage.png'; ?>">

    <div class="badge <?= $b['stok'] > 0 ? 'stok-aman' : 'stok-habis'; ?>">
        <?= $b['stok'] > 0 ? 'Stok: '.$b['stok'] : 'Habis'; ?>
    </div>

    <div class="card-body">
        <h3><?= $b['judul']; ?></h3>
        <p>✍ <?= $b['penulis']; ?></p>

        <div class="actions">
            <a class="btn edit" href="edit_buku.php?id=<?= $b['id']; ?>">Edit</a>
            <a class="btn hapus" href="hapus_buku.php?id=<?= $b['id']; ?>" onclick="return confirm('Hapus buku ini?')">Hapus</a>
        </div>
    </div>

</div>

<?php } ?>

</div>

</body>
</html>