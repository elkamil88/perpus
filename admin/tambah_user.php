<?php
include __DIR__."/../config/koneksi.php";

if(isset($_POST['simpan'])){
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $role=$_POST['role'];

    mysqli_query($conn,"INSERT INTO user(username,email,password,role)
    VALUES('$username','$email','$password','$role')");

    header("Location:user.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah User</title>

<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#0f172a,#1e293b);
    font-family:Inter;
}

/* CARD */
.card{
    width:350px;
    padding:25px;
    border-radius:15px;
    background:rgba(255,255,255,0.05);
    backdrop-filter:blur(10px);
    color:white;
}

/* TITLE */
h2{
    text-align:center;
    margin-bottom:20px;
}

/* INPUT */
input,select{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:none;
    border-radius:8px;
    background:#020617;
    color:white;
}

/* BUTTON */
button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:linear-gradient(135deg,#3b82f6,#06b6d4);
    color:white;
    font-weight:bold;
    cursor:pointer;
}

/* BACK */
.back{
    display:block;
    margin-top:10px;
    text-align:center;
    color:#94a3b8;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="card">

<h2>➕ Tambah User</h2>

<form method="POST">
<input name="username" placeholder="Username" required>
<input name="email" placeholder="Email" required>
<input name="password" placeholder="Password" required>

<select name="role">
<option value="user">User</option>
<option value="admin">Admin</option>
</select>

<button name="simpan">Simpan</button>
</form>

<a class="back" href="user.php">← Kembali</a>

</div>

</body>
</html>