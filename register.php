<?php
session_start();
include __DIR__ . "/config/koneksi.php";

if(isset($_POST['register'])){

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    /* VALIDASI */
    if(empty($username) || empty($password)){
        $error = "Semua field wajib diisi!";
    } else {

        /* CEK USER SUDAH ADA */
        $cek = mysqli_query($conn,"SELECT * FROM user WHERE username='$username'");

        if(mysqli_num_rows($cek) > 0){
            $error = "Username sudah digunakan!";
        } else {

            /* SIMPAN */
            $simpan = mysqli_query($conn,"
            INSERT INTO user(username,password,role)
            VALUES('$username','$password','user')
            ");

            if($simpan){
                echo "<script>alert('Registrasi berhasil!');location='index.php';</script>";
                exit;
            } else {
                $error = "Gagal daftar!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

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

.card{
    width:300px;
    padding:25px;
    border-radius:15px;
    background:#1e293b;
    color:white;
    text-align:center;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:none;
    border-radius:8px;
    background:#020617;
    color:white;
}

button{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
    background:#22c55e;
    color:white;
    font-weight:bold;
}

.error{
    background:#ef4444;
    padding:8px;
    border-radius:8px;
    margin-bottom:10px;
}
</style>

</head>
<body>

<div class="card">

<h2>📝 Register</h2>

<?php if(isset($error)){ ?>
<div class="error"><?= $error; ?></div>
<?php } ?>

<form method="POST">
<input name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>

<button name="register">Daftar</button>
</form>

<br>
<a href="index.php" style="color:#94a3b8;">Sudah punya akun? Login</a>

</div>

</body>
</html>