<?php
session_start();
include "../config/koneksi.php";

$id = $_GET['id'];

mysqli_query($koneksi,"DELETE FROM users WHERE id='$id'");

header("Location: users.php");
?>