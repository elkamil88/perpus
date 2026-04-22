<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Perpustakaan</title>

<style>
body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg,#0f172a,#1e3a8a);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box{
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    padding:40px;
    border-radius:20px;
    width:320px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    color:white;
    text-align:center;
}

.login-box h2{
    margin-bottom:20px;
}

.input-box{
    margin:15px 0;
}

.input-box input{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    outline:none;
}

.btn{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#3b82f6;
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.btn:hover{
    background:#2563eb;
    transform:scale(1.05);
}

.footer{
    margin-top:15px;
    font-size:12px;
    opacity:0.7;
}
</style>

</head>
<body>

<div class="login-box">

    <h2>📚 Perpustakaan Login</h2>

    <form action="auth/proses_login.php" method="POST">

        <div class="input-box">
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button class="btn" type="submit">Login</button>

    </form>

    <div class="footer">
        Sistem Perpustakaan Digital
    </div>

</div>

</body>
</html>