<?php
include "../config/koneksi.php";

/* TAMBAH */
if(isset($_POST['simpan']) && empty($_POST['id'])){
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $stok = $_POST['stok'];

    mysqli_query($koneksi,"
        INSERT INTO buku (judul, penulis, stok)
        VALUES ('$judul','$penulis','$stok')
    ");

    header("Location: buku.php");
    exit;
}

/* EDIT / UPDATE */
if(isset($_POST['simpan']) && !empty($_POST['id'])){
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $stok = $_POST['stok'];

    mysqli_query($koneksi,"
        UPDATE buku 
        SET judul='$judul', penulis='$penulis', stok='$stok'
        WHERE id='$id'
    ");

    header("Location: buku.php");
    exit;
}

$data = mysqli_query($koneksi,"SELECT * FROM buku");
?>
<?php
include "../config/koneksi.php";

if(isset($_POST['simpan'])){
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $stok = $_POST['stok'];

    if($judul != "" && $penulis != "" && $stok != ""){
        mysqli_query($koneksi,"
            INSERT INTO buku (judul, penulis, stok)
            VALUES ('$judul','$penulis','$stok')
        ");

        header("Location: buku.php");
        exit;
    } else {
        echo "<script>alert('Data tidak boleh kosong!');</script>";
    }
}
?>
<?php
include "../config/koneksi.php";

$data = mysqli_query($koneksi,"SELECT * FROM buku");
?>

<!DOCTYPE html>
<html>
<head>
<title>CRUD Modern</title>

<style>
*{
    margin:0;
    padding:0;
    font-family:Inter,Segoe UI;
    box-sizing:border-box;
}

body{
    background:#0f172a;
    color:white;
}

/* HEADER */
.header{
    background:#1e293b;
    padding:20px;
    border-bottom:1px solid #334155;
}

/* CONTAINER */
.container{
    padding:20px;
}

/* TOP BUTTON */
.topbar{
    display:flex;
    gap:10px;
    margin-bottom:15px;
}

.btn{
    padding:10px 15px;
    border:none;
    border-radius:12px;
    cursor:pointer;
    transition:0.3s;
}

.btn-add{
    background:#3b82f6;
    color:white;
}

.btn-add:hover{
    transform:translateY(-3px);
    background:#2563eb;
}

.btn-back{
    background:#64748b;
    color:white;
}

.btn-back:hover{
    transform:translateY(-3px);
    background:#475569;
}

/* SEARCH */
input{
    width:100%;
    padding:12px;
    border-radius:12px;
    border:1px solid #334155;
    background:#1e293b;
    color:white;
    margin-bottom:15px;
    transition:0.3s;
}

input:focus{
    border-color:#3b82f6;
    transform:scale(1.01);
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:15px;
}

/* CARD */
.card{
    background:#1e293b;
    padding:15px;
    border-radius:15px;
    border:1px solid #334155;
    box-shadow:0 10px 20px rgba(0,0,0,0.3);
    transition:0.4s;
    opacity:0;
    transform:translateY(15px);
    animation:fadeIn 0.4s forwards;
}

.card:hover{
    transform:translateY(-8px);
}

/* ANIMATION */
@keyframes fadeIn{
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* ACTION BUTTON */
.btn-edit{
    background:#22c55e;
    color:white;
    margin-right:5px;
}

.btn-edit:hover{
    transform:scale(1.05);
}

.btn-del{
    background:#ef4444;
    color:white;
}

.btn-del:hover{
    transform:scale(1.05);
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,0.5);
    backdrop-filter:blur(5px);
    justify-content:center;
    align-items:center;
}

.modal-box{
    background:#1e293b;
    padding:20px;
    border-radius:15px;
    width:320px;
    border:1px solid #334155;
    transform:scale(0.8);
    opacity:0;
    animation:pop 0.3s forwards;
}

@keyframes pop{
    to{
        transform:scale(1);
        opacity:1;
    }
}

.modal-box input{
    margin-bottom:10px;
}

.save{
    background:#3b82f6;
    color:white;
}

.save:hover{
    transform:scale(1.05);
}
</style>

</head>
<body>
    <img src="<?= $b['gambar']; ?>" width="60" style="border-radius:8px;">

<div class="header">
    <h2>📚 CRUD BUKU MODERN</h2>
</div>

<div class="container">

<div class="topbar">

<button class="btn btn-add" onclick="openModal()">+ Tambah Buku</button>

<a href="dashboard.php">
<button class="btn btn-back">⬅ Back</button>
</a>

</div>

<input type="text" id="search" placeholder="🔍 Cari buku...">

<div class="grid">

<?php while($b=mysqli_fetch_assoc($data)){ ?>

<div class="card">
    <h3><?= $b['judul']; ?></h3>
    <p>✍ <?= $b['penulis']; ?></p>
    <p>📦 Stok: <?= $b['stok']; ?></p>

    <button class="btn btn-edit" onclick="editData(<?= $b['id'] ?>,'<?= $b['judul'] ?>','<?= $b['penulis'] ?>',<?= $b['stok'] ?>)">Edit</button>

    <a href="hapus.php?id=<?= $b['id'] ?>">
        <button class="btn btn-del">Hapus</button>
    </a>
</div>

<?php } ?>

</div>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
<div class="modal-box">

<h3 id="title">Tambah Buku</h3>

<form method="POST">

<input type="hidden" name="id" id="id">

<input type="text" name="judul" id="judul" placeholder="Judul">
<input type="text" name="penulis" id="penulis" placeholder="Penulis">
<input type="number" name="stok" id="stok" placeholder="Stok">

<button name="simpan" class="save">Simpan</button>
<button type="button" onclick="closeModal()">Tutup</button>

</form>

</div>
</div>

<script>

function openModal(){
    document.getElementById('modal').style.display='flex';
}

function closeModal(){
    document.getElementById('modal').style.display='none';
}

function editData(id,judul,penulis,stok){
    openModal();
    document.getElementById('id').value=id;
    document.getElementById('judul').value=judul;
    document.getElementById('penulis').value=penulis;
    document.getElementById('stok').value=stok;
}

document.getElementById("search").addEventListener("input",function(){
    let val=this.value.toLowerCase();
    let cards=document.querySelectorAll(".card");

    cards.forEach(c=>{
        c.style.display=c.innerText.toLowerCase().includes(val)?"block":"none";
    });
});

</script>

</body>
</html>