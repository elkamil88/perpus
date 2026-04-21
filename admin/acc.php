<?php
include __DIR__."/../config/koneksi.php";

$id = intval($_GET['id']);

/* AMBIL DATA */
$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM peminjaman WHERE id='$id'
"));

if(!$data){
    die("Data tidak ditemukan!");
}

/* CEK STATUS */
if($data['status'] != 'menunggu'){
    die("Data sudah diproses!");
}

/* CEK STOK */
$buku = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM buku WHERE id='".$data['buku_id']."'
"));

if($buku['stok'] <= 0){
    echo "<script>alert('Stok habis!');location='peminjaman.php';</script>";
    exit;
}

/* UPDATE */
mysqli_query($conn,"
UPDATE peminjaman SET status='dipinjam' WHERE id='$id'
");

/* KURANGI STOK */
mysqli_query($conn,"
UPDATE buku SET stok = stok - 1 WHERE id='".$data['buku_id']."'
");

echo "<script>alert('Disetujui!');location='peminjaman.php';</script>";