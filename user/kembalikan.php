<?php
session_start();
include "../config/koneksi.php";

$id = $_GET['id'];

/* ambil data */
$data = mysqli_fetch_assoc(mysqli_query($koneksi,"
    SELECT * FROM peminjaman WHERE id='$id'
"));

$buku_id = $data['buku_id'];

/* update status */
mysqli_query($koneksi,"
    UPDATE peminjaman SET status='dikembalikan'
    WHERE id='$id'
");

/* tambah stok */
mysqli_query($koneksi,"
    UPDATE buku SET stok = stok + 1
    WHERE id='$buku_id'
");

echo "<script>alert('Buku dikembalikan');window.location='dashboard.php';</script>";
?>