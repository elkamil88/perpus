<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

/* STATISTIK */
$buku = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as total FROM buku"))['total'];
$user = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as total FROM users WHERE role='user'"))['total'];
$pinjam = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as total FROM peminjaman"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Inter;}

body{
    background: linear-gradient(135deg,#0f172a,#1e1b4b);
    color:white;
    display:flex;
}

/* SIDEBAR */
.sidebar{
    width:250px;
    height:100vh;
    position:fixed;
    padding:20px;

    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(15px);
    border-right:1px solid rgba(255,255,255,0.1);
}

.sidebar h2{margin-bottom:20px;}

.sidebar a{
    display:block;
    padding:12px;
    margin-bottom:8px;
    color:white;
    text-decoration:none;
    border-radius:10px;
    transition:0.3s;
}

.sidebar a:hover{
    background:rgba(255,255,255,0.1);
    transform:translateX(5px);
}

/* CONTENT */
.content{
    margin-left:250px;
    padding:20px;
    width:100%;
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:15px;
}

/* CARD */
.card{
    padding:20px;
    border-radius:15px;

    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);

    border:1px solid rgba(255,255,255,0.1);

    transition:0.3s;
}

.card:hover{
    transform:translateY(-8px);
}

/* TEXT */
h3{color:#94a3b8;font-size:14px;}
h1{margin-top:10px;font-size:28px;}
</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>📚 Admin</h2>

    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="buku.php">📖 Buku</a>
    <a href="users.php">👤 User</a>
    <a href="peminjaman.php">📦 Peminjaman</a>
    <a href="../logout.php">🚪 Logout</a>
</div>

<!-- CONTENT -->
<div class="content">

<h2>📊 Admin Dashboard</h2>

<div class="grid">

<div class="card">
    <h3>Total Buku</h3>
    <h1><?= $buku; ?></h1>
</div>

<div class="card">
    <h3>Total User</h3>
    <h1><?= $user; ?></h1>
</div>

<div class="card">
    <h3>Total Peminjaman</h3>
    <h1><?= $pinjam; ?></h1>
</div>

</div>

</div>

</body>
</html>