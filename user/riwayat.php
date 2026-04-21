<?php
session_start();
include __DIR__."/../config/koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['id'];

/* QUERY */
$data = mysqli_query($conn,"
SELECT peminjaman.*, buku.judul 
FROM peminjaman
JOIN buku ON buku.id = peminjaman.buku_id
WHERE peminjaman.user_id='$user_id'
ORDER BY peminjaman.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Riwayat Peminjaman</title>

<style>
body{
    margin:0;
    font-family:sans-serif;
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
    color:#94a3b8;
    text-decoration:none;
}

.menu a:hover{color:white;}

.logout{
    background:#ef4444;
    padding:6px 12px;
    border-radius:8px;
    color:white;
    text-decoration:none;
}

/* CONTAINER */
.container{
    width:90%;
    margin:auto;
    margin-top:20px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:#1e293b;
    border-radius:12px;
    overflow:hidden;
}

th,td{
    padding:12px;
    text-align:center;
}

th{
    background:#020617;
    color:#94a3b8;
}

tr:hover{
    background:#334155;
}

/* BADGE */
.badge{
    padding:5px 10px;
    border-radius:8px;
    font-size:12px;
}

.menunggu{background:#facc15;color:black;}
.dipinjam{background:#22c55e;}
.kembali{background:#64748b;}
.ditolak{background:#ef4444;}
</style>

</head>
<body>

<!-- NAVBAR -->
<div class="topbar">
    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="riwayat.php">Riwayat</a>
    </div>
    <a class="logout" href="../logout.php">Logout</a>
</div>

<div class="container">
<h2>📚 Riwayat Peminjaman</h2>

<table>
<tr>
    <th>No</th>
    <th>Buku</th>
    <th>Status</th>
    <th>Tanggal Pinjam</th>
    <th>Tanggal Kembali</th>
</tr>

<?php $no=1; while($d=mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($d['judul']); ?></td>

    <td>
        <span class="badge <?= $d['status']; ?>">
            <?= $d['status']; ?>
        </span>
    </td>

    <td><?= $d['tanggal_pinjam'] ?? '-'; ?></td>
    <td><?= $d['tanggal_kembali'] ?? '-'; ?></td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>