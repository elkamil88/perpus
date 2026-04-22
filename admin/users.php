<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

/* HAPUS USER */
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    mysqli_query($koneksi,"DELETE FROM users WHERE id='$id'");
    header("Location: users.php");
    exit;
}

/* DATA USER */
$data = mysqli_query($koneksi,"
SELECT * FROM users WHERE role='user'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Data User</title>

<style>
*{
    margin:0;
    padding:0;
    font-family:Inter,Segoe UI;
    box-sizing:border-box;
}

body{
    background:#0f172a;
    color:white;
}

/* HEADER */
.header{
    background:#1e293b;
    padding:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #334155;
}

.btn-back{
    padding:8px 12px;
    background:#3b82f6;
    color:white;
    text-decoration:none;
    border-radius:8px;
}

/* WRAPPER */
.wrapper{
    padding:20px;
}

/* GRID USER CARD */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
}

/* CARD USER */
.card{
    background:#1e293b;
    padding:15px;
    border-radius:15px;
    border:1px solid #334155;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 10px 25px rgba(0,0,0,0.4);
}

/* BUTTON */
.btn{
    display:inline-block;
    margin-top:10px;
    padding:8px 12px;
    border-radius:8px;
    background:#ef4444;
    color:white;
    text-decoration:none;
    transition:0.3s;
}

.btn:hover{
    background:#dc2626;
}

/* BADGE */
.badge{
    display:inline-block;
    padding:4px 8px;
    font-size:11px;
    border-radius:6px;
    background:#334155;
    margin-top:5px;
}
</style>

</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>👤 Data User</h2>
    <a class="btn-back" href="dashboard.php">⬅ Kembali</a>
</div>

<div class="wrapper">

<div class="grid">

<?php while($u=mysqli_fetch_assoc($data)){ ?>

<div class="card">

    <h3>👤 <?= $u['username']; ?></h3>

    <p class="badge">Email: <?= $u['email']; ?></p>

    <p class="badge">Role: <?= $u['role']; ?></p>

    <br>

    <a href="?hapus=<?= $u['id']; ?>" 
       class="btn"
       onclick="return confirm('Yakin mau hapus user ini?')">
       🗑 Hapus User
    </a>

</div>

<?php } ?>

</div>

</div>

</body>
</html>