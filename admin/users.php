<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id']) || $_SESSION['role']!='admin'){
    header("Location: ../index.php");
    exit;
}

$data = mysqli_query($koneksi,"SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
<title>Kelola User</title>

<style>
body{
    font-family:Inter;
    background:linear-gradient(135deg,#0f172a,#1e1b4b);
    color:white;
    padding:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th,td{
    padding:12px;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

a{
    padding:6px 10px;
    border-radius:8px;
    text-decoration:none;
    color:white;
}

.tambah{background:#22c55e;}
.edit{background:#3b82f6;}
.hapus{background:#ef4444;}
</style>
</head>

<body>

<h2>👤 Kelola User</h2>

<a class="tambah" href="tambah_user.php">+ Tambah User</a>

<table>
<tr>
    <th>No</th>
    <th>Username</th>
    <th>Email</th>
    <th>Role</th>
    <th>Aksi</th>
</tr>

<?php $no=1; while($u=mysqli_fetch_assoc($data)){ ?>

<tr>
<td><?= $no++; ?></td>
<td><?= $u['username']; ?></td>
<td><?= $u['email']; ?></td>
<td><?= $u['role']; ?></td>
<td>
    <a class="edit" href="edit_user.php?id=<?= $u['id']; ?>">Edit</a>
    <a class="hapus" href="hapus_user.php?id=<?= $u['id']; ?>" onclick="return confirm('Hapus user?')">Hapus</a>
</td>
</tr>

<?php } ?>

</table>

</body>
</html>