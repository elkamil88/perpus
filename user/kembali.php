<?php
include '../config/koneksi.php';

$id = $_GET['id'];

// ambil data peminjaman
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM peminjaman WHERE id='$id'
"));

// cek sudah dikembalikan
if($data['status'] == 'kembali'){
    echo "<script>alert('Buku sudah dikembalikan!');history.back();</script>";
    exit;
}

// ✅ SIMPAN KE VARIABEL $update
$update = mysqli_query($conn, "
    UPDATE peminjaman 
    SET status='kembali', tanggal_kembali=NOW()
    WHERE id='$id'
");

// tambah stok
mysqli_query($conn, "
    UPDATE buku SET stok = stok + 1 WHERE id='".$data['buku_id']."'
");

// cek hasil
if($update){
    echo "<script>alert('Buku berhasil dikembalikan');window.location='riwayat.php';</script>";
}else{
    echo "Gagal update: " . mysqli_error($conn);
}