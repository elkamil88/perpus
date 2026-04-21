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
    die("ID tidak valid!");
}

$id = intval($_GET['id']);

/* AMBIL DATA PEMINJAMAN */
$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM peminjaman WHERE id='$id'
"));

if(!$data){
    die("Data tidak ditemukan!");
}

/* VALIDASI: HARUS MILIK USER */
if($data['user_id'] != $user_id){
    die("Akses ditolak!");
}

/* VALIDASI STATUS */
if($data['status'] != 'dipinjam'){
    echo "<script>alert('Buku tidak dalam status dipinjam!');location='dashboard.php';</script>";
    exit;
}

/* UPDATE STATUS */
/* UPDATE STATUS */
mysqli_query($conn,"
UPDATE peminjaman SET status='kembali' WHERE id='$id'
");

/* CEK UPDATE */
if(!$update){
    die("Gagal update: " . mysqli_error($conn));
}

/* TAMBAH STOK */
$stok = mysqli_query($conn,"
UPDATE buku SET stok = stok + 1 WHERE id='".$data['buku_id']."'
");

if(!$stok){
    die("Gagal update stok: " . mysqli_error($conn));
}

echo "<script>alert('Buku berhasil dikembalikan!');location='dashboard.php';</script>";