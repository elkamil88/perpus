<?php
include "config/koneksi.php";

$data = mysqli_query($koneksi,"SELECT * FROM buku");

if(!$data){
    die(mysqli_error($koneksi));
}

echo "DATABASE OK";
?>