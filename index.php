<?php
session_start();
include __DIR__ . "/config/koneksi.php";

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = $_POST['password'];

    $q = mysqli_query($conn,"SELECT * FROM user WHERE username='$username'");

    if(mysqli_num_rows($q) > 0){
        $d = mysqli_fetch_assoc($q);

        if($password == $d['password']){
            $_SESSION['id'] = $d['id'];
            $_SESSION['role'] = $d['role'];

            if($d['role'] == 'admin'){
                header("Location: admin/dashboard.php");
            } else {
                header("Location: user/dashboard.php");
            }
            exit;
        } else {
            $err = "Password salah!";
        }
    } else {
        $err = "User tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Perpustakaan</title>

<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:sans-serif;
    background: linear-gradient(135deg,#0f172a,#1e293b);
}

/* CARD */
.card{
    width:320px;
    padding:25px;
    border-radius:15px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    color:white;
    text-align:center;
}

/* INPUT */
input{
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
    background:linear-gradient(135deg,#6366f1,#22c55e);
    color:white;
    font-weight:bold;
    cursor:pointer;
}

/* ERROR */
.error{
    background:#ef4444;
    padding:8px;
    border-radius:8px;
    margin-bottom:10px;
    font-size:13px;
}

/* LINK */
a{
    color:#94a3b8;
    font-size:12px;
    text-decoration:none;
}
</style>

</head>
<body>

<div class="card">

<h2>📚 Login</h2>

<?php if(isset($err)){ ?>
<div class="error"><?= $err; ?></div>
<?php } ?>

<form method="POST">

<input name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>

<button name="login">Masuk</button>

</form>

<br>
<a href="register.php">Belum punya akun? Daftar</a>

</div>

</body>
</html>