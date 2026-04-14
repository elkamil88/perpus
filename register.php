<?php
session_start();
include "config/koneksi.php";

$error = "";
$success = "";

if(isset($_POST['register'])){

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // cek user sudah ada
    $cek = mysqli_query($koneksi,"
        SELECT * FROM users 
        WHERE username='$username' OR email='$email'
    ");

    if(mysqli_num_rows($cek) > 0){
        $error = "Username atau email sudah digunakan!";
    } else {

        mysqli_query($koneksi,"
            INSERT INTO users (username,email,password,role)
            VALUES ('$username','$email','$password','user')
        ");

        $success = "Akun berhasil dibuat, silakan login!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<style>
*{
    margin:0;
    padding:0;
    font-family:Inter,Segoe UI;
    box-sizing:border-box;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg, #0f172a, #1e1b4b);
}

/* CARD (SAMA DENGAN LOGIN) */
.card{
    width:400px;
    padding:30px;
    border-radius:18px;

    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(12px);

    border:1px solid rgba(255,255,255,0.1);
    box-shadow:0 20px 40px rgba(0,0,0,0.5);

    animation:fadeIn 0.8s ease;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}

h2{
    color:white;
    margin-bottom:5px;
}

p{
    color:#94a3b8;
    font-size:13px;
    margin-bottom:15px;
}

/* INPUT */
input{
    width:100%;
    padding:12px;
    margin-bottom:12px;

    border-radius:10px;
    border:1px solid rgba(255,255,255,0.1);

    background: rgba(255,255,255,0.05);
    color:white;

    outline:none;
}

input:focus{
    border-color:#6366f1;
}

/* BUTTON */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:12px;

    background: linear-gradient(135deg, #6366f1, #22c55e);
    color:white;
    font-weight:bold;

    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 25px rgba(99,102,241,0.4);
}

/* ERROR / SUCCESS */
.error{
    background: rgba(239,68,68,0.2);
    color:#fca5a5;
    padding:10px;
    border-radius:10px;
    margin-bottom:10px;
    font-size:12px;
}

.success{
    background: rgba(34,197,94,0.2);
    color:#86efac;
    padding:10px;
    border-radius:10px;
    margin-bottom:10px;
    font-size:12px;
}

.small{
    margin-top:15px;
    font-size:12px;
    color:#94a3b8;
    text-align:center;
}

a{
    color:#6366f1;
    text-decoration:none;
}
</style>

</head>
<body>

<div class="card">

    <h2>📝 Register</h2>
    <p>Buat akun baru untuk masuk sistem</p>

    <?php if($error){ ?>
        <div class="error"><?= $error; ?></div>
    <?php } ?>

    <?php if($success){ ?>
        <div class="success"><?= $success; ?></div>
    <?php } ?>

    <form method="POST">

        <input type="text" name="username" placeholder="Username" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="register">REGISTER</button>

    </form>

    <div class="small">
        Sudah punya akun? <a href="index.php">Login di sini</a>
    </div>

</div>

</body>
</html>