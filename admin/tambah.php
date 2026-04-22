<?php
include "../config/koneksi.php";

/* PROSES SIMPAN */
if(isset($_POST['simpan'])){

    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $stok = $_POST['stok'];

    /* upload gambar */
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    $folder = "../uploads/";
    if(!file_exists($folder)){
        mkdir($folder, 0777, true);
    }

    $path = $folder . time() . "_" . $gambar;
    move_uploaded_file($tmp, $path);

    mysqli_query($koneksi,"
        INSERT INTO buku (judul, penulis, stok, gambar)
        VALUES ('$judul','$penulis','$stok','$path')
    ");

    echo "<script>alert('Buku berhasil ditambahkan');window.location='buku.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Buku</title>

<style>
body{
    font-family:Arial;
    background:#0f172a;
    color:white;
}

/* CONTAINER */
.container{
    width:400px;
    margin:50px auto;
    background:#1e293b;
    padding:20px;
    border-radius:15px;
    border:1px solid #334155;
}

/* INPUT */
input{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border-radius:8px;
    border:none;
}

/* LABEL */
label{
    font-size:12px;
    color:#94a3b8;
}

/* BUTTON */
.btn{
    padding:10px 15px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
}

.btn-save{
    background:#22c55e;
    color:white;
}

.btn-save:hover{
    background:#16a34a;
}

.btn-close{
    background:#ef4444;
    color:white;
    text-decoration:none;
    display:inline-block;
    text-align:center;
}

.btn-close:hover{
    background:#dc2626;
}

.btn-group{
    display:flex;
    gap:10px;
}
</style>

</head>
<body>

<div class="container">

<h2>📚 Tambah Buku</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Judul Buku</label>
    <input type="text" name="judul" required>

    <label>Penulis</label>
    <input type="text" name="penulis" required>

    <label>Stok</label>
    <input type="number" name="stok" required>

    <label>Gambar Buku</label>
    <input type="file" name="gambar" accept="image/*">

    <div class="btn-group">

        <button type="submit" name="simpan" class="btn btn-save">
            💾 Simpan
        </button>

        <a href="buku.php" class="btn btn-close">
            ❌ Tutup
        </a>

    </div>

</form>

</div>

</body>
</html>