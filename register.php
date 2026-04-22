<?php
session_start();
include __DIR__ . "/config/koneksi.php";

if(isset($_POST['register'])){

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    /* VALIDASI */
    if(empty($username) || empty($password)){
        $error = "Semua kolom pendaftaran wajib diisi!";
    } else {

        /* CEK USER SUDAH ADA */
        $cek = mysqli_query($conn,"SELECT * FROM user WHERE username='$username'");

        if(mysqli_num_rows($cek) > 0){
            $error = "Username sudah digunakan! Pilih yang lain.";
        } else {

            /* SIMPAN */
            $simpan = mysqli_query($conn,"
            INSERT INTO user(username,password,role)
            VALUES('$username','$password','user')
            ");

            if($simpan){
                // Pop-up sukses yang langsung diarahkan ke halaman login (index.php)
                echo "<script>alert('Registrasi berhasil! Silakan login dengan akun baru Anda.');location='index.php';</script>";
                exit;
            } else {
                $error = "Gagal mendaftar. Terjadi kesalahan pada sistem.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Perpustakaan Digital Daya</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #070b19;
            --bg-card: rgba(30, 41, 59, 0.7);
            --primary: #0ea5e9;
            --primary-glow: rgba(14, 165, 233, 0.4);
            --success: #22c55e;
            --success-glow: rgba(34, 197, 94, 0.4);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.1);
            --danger: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            height: 100vh; display: flex; justify-content: center; align-items: center;
            background: var(--bg-dark);
            background-image: 
                radial-gradient(circle at top right, rgba(30, 41, 59, 0.6) 0%, transparent 50%),
                radial-gradient(circle at bottom left, rgba(34, 197, 94, 0.15) 0%, transparent 50%); /* Aksen hijau untuk register */
            color: var(--text-main); overflow: hidden;
        }

        /* Ornamen Lingkaran Glow di Background */
        .glow-circle { position: absolute; border-radius: 50%; filter: blur(80px); z-index: 0; }
        .circle-1 { width: 300px; height: 300px; background: rgba(34, 197, 94, 0.15); top: -50px; left: -50px; }
        .circle-2 { width: 400px; height: 400px; background: rgba(14, 165, 233, 0.1); bottom: -100px; right: -50px; }

        /* KARTU REGISTER (GLASSMORPHISM) */
        .register-glass {
            position: relative; z-index: 10; width: 100%; max-width: 380px; padding: 40px 30px;
            background: var(--bg-card); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color); border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5); text-align: center;
        }

        /* HEADER */
        .register-header { margin-bottom: 30px; }
        .register-header i { font-size: 40px; color: var(--success); margin-bottom: 15px; filter: drop-shadow(0 0 10px var(--success-glow)); }
        .register-header h2 { font-size: 26px; font-weight: 700; margin-bottom: 5px; letter-spacing: 1px; }
        .register-header p { font-size: 13px; color: var(--text-muted); }

        /* PESAN ERROR */
        .alert-error {
            background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5; padding: 10px; border-radius: 10px; font-size: 13px;
            margin-bottom: 20px; display: flex; align-items: center; justify-content: center; gap: 8px;
        }

        /* INPUT GRUP */
        .input-group { position: relative; margin-bottom: 20px; text-align: left; }
        .input-group i {
            position: absolute; top: 50%; left: 15px; transform: translateY(-50%);
            color: var(--text-muted); transition: 0.3s;
        }
        .input-group input {
            width: 100%; padding: 14px 15px 14px 45px; background: rgba(15, 23, 42, 0.6);
            border: 1px solid transparent; border-bottom: 2px solid rgba(255,255,255,0.1);
            border-radius: 12px; color: white; font-size: 14px; outline: none; transition: 0.3s;
        }
        
        /* Efek saat input diklik */
        .input-group input:focus {
            background: rgba(15, 23, 42, 0.8); border-bottom-color: var(--success);
            box-shadow: inset 0 -2px 10px rgba(34, 197, 94, 0.1);
        }
        .input-group input:focus + i, .input-group input:valid + i { color: var(--success); }

        /* TOMBOL DAFTAR */
        button.btn-register {
            width: 100%; padding: 14px; margin-top: 10px; border: none; border-radius: 12px;
            background: linear-gradient(135deg, #10b981, #059669); color: white; /* Nuansa hijau */
            font-size: 15px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;
            cursor: pointer; box-shadow: 0 4px 15px var(--success-glow); transition: 0.3s;
            display: flex; justify-content: center; align-items: center; gap: 8px;
        }
        button.btn-register:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(34, 197, 94, 0.6); }

        /* LINK LOGIN */
        .register-footer { margin-top: 25px; font-size: 13px; color: var(--text-muted); }
        .register-footer a { color: var(--primary); text-decoration: none; font-weight: 600; transition: 0.3s; }
        .register-footer a:hover { color: #38bdf8; text-decoration: underline; }

    </style>
</head>
<body>

    <div class="glow-circle circle-1"></div>
    <div class="glow-circle circle-2"></div>

    <div class="register-glass">
        
        <div class="register-header">
            <i class="fa-solid fa-user-plus"></i>
            <h2>Daftar Akun</h2>
            <p>Bergabunglah dengan Perpustakaan Digital Daya</p>
        </div>

        <?php if(isset($error)){ ?>
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i> <?= $error; ?>
            </div>
        <?php } ?>

        <form method="POST" autocomplete="off">
            <div class="input-group">
                <input type="text" name="username" placeholder="Buat Username" required>
                <i class="fa-solid fa-user"></i>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" placeholder="Buat Password" required>
                <i class="fa-solid fa-lock"></i>
            </div>

            <button type="submit" name="register" class="btn-register">
                Daftar Sekarang <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>

        <div class="register-footer">
            Sudah memiliki akun? <br>
            <a href="index.php">Kembali ke Halaman Login</a>
        </div>

    </div>

</body>
</html>