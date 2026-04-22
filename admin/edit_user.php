<?php
include __DIR__."/../config/koneksi.php";

$id=$_GET['id'];
$data=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM user WHERE id=$id"));

if(isset($_POST['update'])){
    $username=$_POST['username'];
    $email=$_POST['email'];
    $role=$_POST['role'];

    mysqli_query($conn,"UPDATE user SET 
    username='$username',
    email='$email',
    role='$role'
    WHERE id=$id");

    header("Location:user.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>

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
    background:linear-gradient(135deg,#22c55e,#4ade80);
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

<h2>✏️ Edit User</h2>

<form method="POST">
<input name="username" value="<?= $data['username'] ?>" required>
<input name="email" value="<?= $data['email'] ?>" required>

<select name="role">
<option value="user" <?= $data['role']=='user'?'selected':'' ?>>User</option>
<option value="admin" <?= $data['role']=='admin'?'selected':'' ?>>Admin</option>
</select>

<button name="update">Update</button>
</form>

<a class="back" href="user.php">← Kembali</a>

</div>

</body>
</html>