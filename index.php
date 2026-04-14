<?php
session_start();
include "config/koneksi.php";
$error = "";

if(isset($_POST['login'])){

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = mysqli_query($koneksi,"
        SELECT * FROM users 
        WHERE (username='$username' OR email='$username') 
        AND password='$password'
    ");

    $data = mysqli_fetch_assoc($query);

    if($data){
        $_SESSION['id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        if($data['role']=='admin'){
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

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

/* LOGIN CARD (MATCH DASHBOARD STYLE) */
.card{
    width:380px;
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
    margin-bottom:20px;
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

/* BUTTON (MATCH DASHBOARD GRADIENT) */
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

/* ERROR */
.error{
    background: rgba(239,68,68,0.2);
    color:#fca5a5;
    padding:10px;
    border-radius:10px;
    margin-bottom:10px;
    font-size:12px;
}

/* LINK */
a{
    color:#6366f1;
    text-decoration:none;
}

a:hover{
    text-decoration:underline;
}

.small{
    margin-top:15px;
    font-size:12px;
    color:#94a3b8;
    text-align:center;
}
</style>

</head>
<body>

<div class="card">

    <h2>📚 Library Login</h2>
    <p>Masuk ke sistem perpustakaan</p>

    <?php if($error){ ?>
        <div class="error"><?= $error; ?></div>
    <?php } ?>

    <form method="POST">

        <input type="text" name="username" placeholder="Username / Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">LOGIN</button>

    </form>

    <div class="small">
        Belum punya akun? <a href="register.php">Daftar sekarang</a>
    </div>

</div>

</body>
</html>