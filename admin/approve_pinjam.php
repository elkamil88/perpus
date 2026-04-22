<?php
include '../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM peminjaman WHERE id='$id'"));

if($data['status'] != 'menunggu'){
    die("Data tidak valid");
}

// cek stok
$buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM buku WHERE id='".$data['buku_id']."'"));

if($buku['stok'] <= 0){
    die("Stok habis");
}

// update
mysqli_query($conn, "UPDATE peminjaman SET status='dipinjam' WHERE id='$id'");

// kurangi stok
mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id='".$data['buku_id']."'");

header("Location: peminjaman.php");