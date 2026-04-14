<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['id'];
$buku_id = $_GET['id'];

/* 🔥 CEK ULANG STOK REAL-TIME */
$cek = mysqli_query($koneksi,"
    SELECT * FROM buku WHERE id='$buku_id' FOR UPDATE
");

$buku = mysqli_fetch_assoc($cek);

if(!$buku){
    echo "<script>alert('Buku tidak ditemukan');window.location='dashboard.php';</script>";
    exit;
}

/* ❌ VALIDASI STOK */
if($buku['stok'] <= 0){
    echo "<script>alert('Stok habis! Tidak bisa dipinjam');window.location='dashboard.php';</script>";
    exit;
}

/* 🔥 CEK USER SUDAH PINJAM YANG SAMA */
$cekPinjam = mysqli_query($koneksi,"
    SELECT * FROM peminjaman 
    WHERE user_id='$user_id' 
    AND buku_id='$buku_id' 
    AND status='dipinjam'
");

if(mysqli_num_rows($cekPinjam) > 0){
    echo "<script>alert('Kamu sudah meminjam buku ini');window.location='dashboard.php';</script>";
    exit;
}

/* 🔥 INSERT PEMINJAMAN (STATUS MENUNGGU / DIPINJAM) */
mysqli_query($koneksi,"
    INSERT INTO peminjaman (user_id,buku_id,status)
    VALUES ('$user_id','$buku_id','menunggu')
");

echo "<script>alert('Permintaan pinjam dikirim ke admin');window.location='dashboard.php';</script>";
?>