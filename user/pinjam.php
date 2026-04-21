<?php
session_start();
include __DIR__."/../config/koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['id'];

/* VALIDASI ID */
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    die("ID buku tidak valid!");
}

$buku_id = intval($_GET['id']);

/* CEK DATA BUKU */
$q_buku = mysqli_query($conn,"SELECT * FROM buku WHERE id='$buku_id'");
$buku   = mysqli_fetch_assoc($q_buku);

if(!$buku){
    die("Buku tidak ditemukan!");
}

/* VALIDASI STOK */
if($buku['stok'] <= 0){
    echo "<script>alert('Stok buku habis!');location='dashboard.php';</script>";
    exit;
}

/* VALIDASI DUPLIKAT */
$cek = mysqli_query($conn,"
SELECT * FROM peminjaman 
WHERE user_id='$user_id' 
AND buku_id='$buku_id' 
AND status IN ('menunggu','dipinjam')
");

if(mysqli_num_rows($cek) > 0){
    echo "<script>alert('Kamu sudah meminjam / menunggu buku ini!');location='dashboard.php';</script>";
    exit;
}

/* SIMPAN DATA (MENUNGGU ACC) */
$simpan = mysqli_query($conn,"
INSERT INTO peminjaman(user_id,buku_id,status)
VALUES('$user_id','$buku_id','menunggu')
");

/* CEK INSERT */
if(!$simpan){
    die("Gagal menyimpan: " . mysqli_error($conn));
}

echo "<script>alert('Permintaan berhasil! Menunggu ACC admin');location='dashboard.php';</script>";