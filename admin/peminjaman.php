<?php
include __DIR__ . "/../config/koneksi.php";

/* QUERY */
$data = mysqli_query($conn,"
SELECT peminjaman.*, user.username, buku.judul 
FROM peminjaman
JOIN user ON user.id = peminjaman.user_id
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

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

/* BACK BUTTON */
.back{
    background:#6366f1;
    padding:8px 12px;
    border-radius:8px;
    text-decoration:none;
    color:white;
}

/* TABLE */
table{
    width:100%;
    margin-top:20px;
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

/* BUTTON */
.btn{
    padding:5px 10px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:12px;
}

.acc{background:#22c55e;}
.tolak{background:#ef4444;}
</style>

</head>
<body>

<!-- NAVBAR -->
<div class="topbar">
    <div class="menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="buku.php">Buku</a>
        <a href="peminjaman.php">Peminjaman</a>
    </div>
    <a class="logout" href="../logout.php">Logout</a>
</div>

<div class="container">

<div class="header">
    <h2>📦 Data Peminjaman</h2>
    <a href="dashboard.php" class="back">← Kembali</a>
</div>

<table>
<tr>
    <th>No</th>
    <th>User</th>
    <th>Buku</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php $no=1; while($d=mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($d['username']); ?></td>
    <td><?= htmlspecialchars($d['judul']); ?></td>

    <td>
        <span class="badge <?= $d['status']; ?>">
            <?= $d['status']; ?>
        </span>
    </td>

    <td>
    <?php if($d['status']=='menunggu'){ ?>
        <a href="acc.php?id=<?= $d['id']; ?>" class="btn acc">ACC</a>
        <a href="tolak.php?id=<?= $d['id']; ?>" class="btn tolak">Tolak</a>
    <?php } else { ?>
        -
    <?php } ?>
    </td>

</tr>
<?php } ?>

</table>

</div>

</body>
</html>