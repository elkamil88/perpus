<?php
session_start();
include '../config/koneksi.php';

// cek login admin
if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? 0;

// ambil data peminjaman
$data = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM peminjaman WHERE id='$id'
"));

// validasi
if(!$data){
    echo "<script>alert('Data tidak ditemukan');history.back();</script>";
    exit;
}

// update status jadi ditolak
$update = mysqli_query($conn,"
    UPDATE peminjaman 
    SET status='ditolak' 
    WHERE id='$id'
");

if($update){
    echo "<script>alert('Peminjaman ditolak');location='peminjaman.php';</script>";
} else {
    echo "<script>alert('Gagal menolak');history.back();</script>";
}