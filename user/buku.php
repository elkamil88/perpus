<?php
session_start();
include '../config/koneksi.php';

// cek login
if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
    exit;
}

// ✅ cek id dari URL
if(!isset($_GET['id'])){
    echo "<script>alert('Buku tidak ditemukan!');window.location='buku.php';</script>";
    exit;
}

$id_user = $_SESSION['id'];
$id_buku = $_GET['id'];

// cek buku
$buku = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM buku WHERE id='$id_buku'
"));

if(!$buku){
    echo "<script>alert('Buku tidak ada!');window.location='buku.php';</script>";
    exit;
}

// cek stok
if($buku['stok'] <= 0){
    echo "<script>alert('Stok habis!');window.location='buku.php';</script>";
    exit;
}

// cek sudah pinjam
$cek = mysqli_query($conn, "
    SELECT * FROM peminjaman 
    WHERE user_id='$id_user' 
    AND buku_id='$id_buku' 
    AND status='dipinjam'
");

if(mysqli_num_rows($cek) > 0){
    echo "<script>alert('Masih meminjam buku ini!');window.location='buku.php';</script>";
    exit;
}

// simpan
mysqli_query($conn, "
    INSERT INTO peminjaman (user_id,buku_id,tanggal_pinjam,status)
    VALUES ('$id_user','$id_buku',NOW(),'dipinjam')
");

// kurangi stok
mysqli_query($conn, "
    UPDATE buku SET stok = stok - 1 WHERE id='$id_buku'
");

echo "<script>alert('Berhasil pinjam!');window.location='riwayat.php';</script>";