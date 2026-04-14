<?php
session_start();
include "../config/koneksi.php";

/* CEK LOGIN ADMIN */
if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

/* =====================
   APPROVE PEMINJAMAN
===================== */
if(isset($_GET['approve'])){
    $id = $_GET['approve'];

    $cek = mysqli_query($koneksi,"
        SELECT * FROM peminjaman WHERE id='$id'
    ");
    $data = mysqli_fetch_assoc($cek);

    if($data && $data['status']=='menunggu'){

        // ubah status jadi dipinjam
        mysqli_query($koneksi,"
            UPDATE peminjaman 
            SET status='dipinjam'
            WHERE id='$id'
        ");

        // kurangi stok buku
        mysqli_query($koneksi,"
            UPDATE buku 
            SET stok = stok - 1 
            WHERE id='{$data['buku_id']}'
        ");
    }

    header("Location: peminjaman.php");
    exit;
}

/* =====================
   TOLAK PEMINJAMAN
===================== */
if(isset($_GET['tolak'])){
    $id = $_GET['tolak'];

    mysqli_query($koneksi,"
        UPDATE peminjaman 
        SET status='ditolak'
        WHERE id='$id'
    ");

    header("Location: peminjaman.php");
    exit;
}

/* =====================
   AMBIL DATA PEMINJAMAN
===================== */
$data = mysqli_query($koneksi,"
SELECT peminjaman.*, users.username, buku.judul
FROM peminjaman
JOIN users ON users.id = peminjaman.user_id
JOIN buku ON buku.id = peminjaman.buku_id
ORDER BY peminjaman.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Peminjaman Admin</title>

<style>
body{
    font-family:Arial;
    background:#0f172a;
    color:white;
}

.container{
    padding:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:#1e293b;
}

th,td{
    padding:12px;
    border:1px solid #334155;
    text-align:left;
}

th{
    background:#334155;
}

a{
    text-decoration:none;
    padding:6px 10px;
    border-radius:6px;
    margin-right:5px;
}

.approve{
    background:#22c55e;
    color:white;
}

.tolak{
    background:#ef4444;
    color:white;
}

.badge{
    padding:4px 8px;
    border-radius:6px;
    background:#334155;
}
</style>

</head>
<body>

<div class="container">

<h2>📥 Data Peminjaman Buku</h2>

<table>
<tr>
<th>User</th>
<th>Buku</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php while($p=mysqli_fetch_assoc($data)){ ?>

<tr>
<td><?= $p['username']; ?></td>
<td><?= $p['judul']; ?></td>
<td><span class="badge"><?= $p['status']; ?></span></td>
<td>

<?php if($p['status']=='menunggu'){ ?>

    <a class="approve" href="?approve=<?= $p['id']; ?>">✔ Approve</a>
    <a class="tolak" href="?tolak=<?= $p['id']; ?>">❌ Tolak</a>

<?php } elseif($p['status']=='dipinjam'){ ?>

    <span class="badge">Sedang Dipinjam</span>

<?php } else { ?>

    <span class="badge">Selesai</span>

<?php } ?>

</td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>