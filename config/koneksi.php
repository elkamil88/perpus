<?php
$conn = mysqli_connect("localhost","root","","perpus_vino");

if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>