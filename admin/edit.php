<?php
include "../config/koneksi.php";

$id = $_GET['id'];

$data = mysqli_query($koneksi,"SELECT * FROM buku WHERE id='$id'");
$b = mysqli_fetch_assoc($data);

if(isset($_POST['update'])){
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $stok = $_POST['stok'];

    mysqli_query($koneksi,"UPDATE buku SET 
    judul='$judul',
    penulis='$penulis',
    stok='$stok'
    WHERE id='$id'");

    header("Location: buku.php");
}
?>

<h2>EDIT BUKU</h2>

<form method="POST">
    <input type="text" name="judul" value="<?= $b['judul']; ?>"><br><br>
    <input type="text" name="penulis" value="<?= $b['penulis']; ?>"><br><br>
    <input type="number" name="stok" value="<?= $b['stok']; ?>"><br><br>

    <button type="submit" name="update">UPDATE</button>
</form>