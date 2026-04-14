<?php
$koneksi = mysqli_connect("localhost","root","","perpus_vino");

if(!$koneksi){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>