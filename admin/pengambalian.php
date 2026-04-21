<?php
include __DIR__ . "/../config/koneksi.php";
include __DIR__ . "/../config/session.php";

if(!isset($conn)){
    die("Koneksi tidak tersedia!");
}

$data = mysqli_query($conn,"
SELECT peminjaman.*, user.username, buku.judul 
FROM peminjaman
JOIN user ON user.id=peminjaman.user_id
JOIN buku ON buku.id=peminjaman.buku_id
ORDER BY peminjaman.id DESC
");
?>