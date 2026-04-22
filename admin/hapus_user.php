<?php
include __DIR__."/../config/koneksi.php";

$id=$_GET['id'];

mysqli_query($conn,"DELETE FROM user WHERE id=$id");

header("Location:user.php");