<?php
session_start();
include __DIR__."/../config/koneksi.php";

if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['id'];
$buku_id = intval($_GET['id']);

/* CEK BUKU */
$buku = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM buku WHERE id='$buku_id'"));

if(!$buku){
    die("Buku tidak ditemukan!");
}

/* VALIDASI STOK */
if($buku['stok'] <= 0){
    echo "<script>alert('Stok habis!');location='dashboard.php';</script>";
    exit;
}

/* CEK DUPLIKAT */
$cek = mysqli_query($conn,"
SELECT * FROM peminjaman 
WHERE user_id='$user_id' 
AND buku_id='$buku_id' 
AND status IN ('menunggu','dipinjam')
");

if(mysqli_num_rows($cek) > 0){
    echo "<script>alert('Sudah request / dipinjam!');location='dashboard.php';</script>";
    exit;
}

/* INSERT */
mysqli_query($conn,"
INSERT INTO peminjaman(user_id,buku_id,status)
VALUES('$user_id','$buku_id','menunggu')
");

echo "<script>alert('Menunggu ACC admin!');location='dashboard.php';</script>";