<?php
include __DIR__."/../config/koneksi.php";

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM buku WHERE id='$id'"));

if(isset($_POST['update'])){

    $judul   = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $stok    = $_POST['stok'];

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    if($gambar){
        move_uploaded_file($tmp,"../assets/img/".$gambar);
        $gbr = $gambar;
    } else {
        $gbr = $data['gambar'];
    }

    mysqli_query($conn,"UPDATE buku SET 
        judul='$judul',
        penulis='$penulis',
        stok='$stok',
        gambar='$gbr'
        WHERE id='$id'");

    header("Location: buku.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Buku</title>

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

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
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
    background:linear-gradient(135deg,#3b82f6,#60a5fa);
    color:white;
    font-weight:bold;
    cursor:pointer;
}

/* BACK */
.btn-back{
    background:#334155;
    border:none;
    padding:6px 12px;
    border-radius:8px;
    color:white;
    cursor:pointer;
    font-size:12px;
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

<div class="header">
    <button onclick="goBack()" class="btn-back">⬅ Kembali</button>
    <h2>✏️ Edit Buku</h2>
</div>

<form method="POST" enctype="multipart/form-data">

<input name="judul" value="<?= $data['judul']; ?>" required>
<input name="penulis" value="<?= $data['penulis']; ?>" required>
<input type="number" name="stok" value="<?= $data['stok']; ?>" required>

<input type="file" name="gambar" accept="image/*" onchange="previewImg(event)">

<div class="preview">
    <img id="imgPreview" src="../assets/img/<?= $data['gambar'] ?: 'noimage.png'; ?>">
</div>

<button name="update">Update Buku</button>

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

function goBack(){
    if(document.referrer !== ""){
        window.history.back();
    } else {
        window.location.href = "buku.php";
    }
}
</script>

</body>
</html>