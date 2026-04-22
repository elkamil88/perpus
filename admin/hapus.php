<?php
include "../config/koneksi.php";

$id = $_GET['id'];

mysqli_query($koneksi,"DELETE FROM buku WHERE id='$id'");

header("Location: buku.php");
?>