<?php
include __DIR__."/../config/koneksi.php";

if(isset($_POST['simpan'])){

    $judul   = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $stok    = $_POST['stok'];

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    if($gambar){
        move_uploaded_file($tmp,"../assets/img/".$gambar);
    }

    mysqli_query($conn,"INSERT INTO buku(judul,penulis,stok,gambar)
    VALUES('$judul','$penulis','$stok','$gambar')");

    header("Location: buku.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Buku</title>

<style>
body{
    margin:0;
    font-family:sans-serif;
    background: linear-gradient(135deg,#0f172a,#1e293b);
    color:white;
}

/* CONTAINER */
.container{
    max-width:400px;
    margin:50px auto;
    background:#1e293b;
    padding:25px;
    border-radius:15px;
}

/* INPUT */
input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:none;
    border-radius:8px;
    background:#0f172a;
    color:white;
}

/* BUTTON */
button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:linear-gradient(135deg,#22c55e,#4ade80);
    color:white;
    font-weight:bold;
    cursor:pointer;
}

/* PREVIEW */
.preview{
    margin-top:10px;
    text-align:center;
}

.preview img{
    width:100%;
    max-height:200px;
    object-fit:cover;
    border-radius:10px;
}
</style>

</head>
<body>

<div class="container">
<h2>➕ Tambah Buku</h2>

<form method="POST" enctype="multipart/form-data">

<input name="judul" placeholder="Judul Buku" required>
<input name="penulis" placeholder="Penulis" required>
<input type="number" name="stok" placeholder="Stok" required>

<input type="file" name="gambar" accept="image/*" onchange="previewImg(event)">

<div class="preview">
    <img id="imgPreview" src="../assets/img/noimage.png">
</div>

<button name="simpan">Simpan Buku</button>

</form>
</div>

<script>
function previewImg(event){
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('imgPreview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>