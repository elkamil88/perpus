<?php
include "../config/koneksi.php";
session_start();

$user_id = $_SESSION['id'];

$data = mysqli_query($koneksi,"
SELECT peminjaman.*, buku.judul 
FROM peminjaman 
JOIN buku ON peminjaman.buku_id = buku.id
WHERE user_id='$user_id'
");
?>

<h2>📄 RIWAYAT PINJAM</h2>

<table border="1" cellpadding="10">
<tr>
<th>Buku</th>
<th>Tanggal Pinjam</th>
<th>Status</th>
</tr>

<?php while($r=mysqli_fetch_assoc($data)){ ?>
<tr>
<td><?= $r['judul'] ?></td>
<td><?= $r['tanggal_pinjam'] ?></td>
<td><?= $r['status'] ?></td>
</tr>
<?php } ?>

</table>