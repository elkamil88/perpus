<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id']) || $_SESSION['role']!='admin'){
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'];

/* 🔥 AMBIL DATA PINJAM */
$pinjam = mysqli_fetch_assoc(mysqli_query($koneksi,"
    SELECT * FROM peminjaman WHERE id='$id'
"));

$buku_id = $pinjam['buku_id'];

/* 🔥 CEK STOK ULANG (REAL-TIME SAFETY) */
$buku = mysqli_fetch_assoc(mysqli_query($koneksi,"
    SELECT * FROM buku WHERE id='$buku_id'
"));

if($buku['stok'] <= 0){
    echo "<script>alert('Stok sudah habis, tidak bisa approve');window.location='peminjaman.php';</script>";
    exit;
}

/* 🔥 UPDATE STATUS */
mysqli_query($koneksi,"
    UPDATE peminjaman SET status='dipinjam'
    WHERE id='$id'
");

/* 🔥 KURANGI STOK */
mysqli_query($koneksi,"
    UPDATE buku SET stok = stok - 1
    WHERE id='$buku_id'
");

echo "<script>alert('Pinjaman disetujui');window.location='peminjaman.php';</script>";
?>