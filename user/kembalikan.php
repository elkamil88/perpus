<?php
session_start();
include '../config/koneksi.php';

$id = $_GET['id'];

// ambil data
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM peminjaman WHERE id='$id'
"));

// validasi
if($data['status'] != 'dipinjam'){
    echo "<script>alert('Tidak bisa dikembalikan');history.back();</script>";
    exit;
}

// ubah jadi menunggu_kembali
mysqli_query($conn, "
    UPDATE peminjaman 
    SET status='menunggu_kembali' 
    WHERE id='$id'
");

echo "<script>alert('Menunggu konfirmasi admin');window.location='riwayat.php';</script>";