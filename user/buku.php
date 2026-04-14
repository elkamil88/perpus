<?php
include "../config/koneksi.php";
session_start();

$data = mysqli_query($koneksi,"SELECT * FROM buku");
?>

<h2>📚 DAFTAR BUKU</h2>

<table border="1" cellpadding="10">
<tr>
<th>Judul</th>
<th>Penulis</th>
<th>Stok</th>
<th>Aksi</th>
</tr>

<?php while($b=mysqli_fetch_assoc($data)){ ?>
<tr>
<td><?= $b['judul'] ?></td>
<td><?= $b['penulis'] ?></td>
<td><?= $b['stok'] ?></td>
<td>

<a href="pinjam.php?id=<?= $b['id'] ?>">
    📥 Pinjam
</a>

</td>
</tr>
<?php } ?>

</table>