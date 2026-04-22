<?php
include '../config/koneksi.php';

$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM peminjaman WHERE id='$id'"));

if($data['status'] != 'menunggu_kembali'){
    die("Data tidak valid");
}

// update
mysqli_query($conn, "
    UPDATE peminjaman 
    SET status='kembali', tanggal_kembali=NOW() 
    WHERE id='$id'
");

// tambah stok
mysqli_query($conn, "
    UPDATE buku SET stok = stok + 1 
    WHERE id='".$data['buku_id']."'
");

header("Location: peminjaman.php");