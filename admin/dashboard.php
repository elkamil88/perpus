<?php
include __DIR__."/../config/session.php";
include __DIR__."/../config/koneksi.php";

// HITUNG DATA (Logika tetap sama persis seperti kode asli Anda)
$jml_buku = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM buku"))['total'];
$jml_user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM user WHERE role='user'"))['total'];
$jml_pinjam = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan Digital Daya</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ================= VARIABEL WARNA ================= */
        :root {
            --bg-main: #0b1120;
            --bg-sidebar: rgba(15, 23, 42, 0.8);
            --bg-card: rgba(30, 41, 59, 0.4);
            --accent: #0ea5e9;
            --accent-hover: #0284c7;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --danger: #ef4444;
        }

        /* ================= GLOBAL ================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--bg-main);
            background-image: radial-gradient(circle at top right, #1e293b 0%, transparent 40%),
                              radial-gradient(circle at bottom left, #0f172a 0%, transparent 40%);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            backdrop-filter: blur(15px);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            padding: 25px 0;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
            color: white;
            text-align: center;
            padding-bottom: 25px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            letter-spacing: 1px;
        }

        .brand i {
            color: var(--accent);
            margin-right: 8px;
        }

        .menu {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 0 15px;
        }

        .menu a {
            text-decoration: none;
            color: var(--text-muted);
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .menu a:hover, .menu a.active {
            background: rgba(14, 165, 233, 0.1);
            color: var(--accent);
        }

        .logout-container {
            padding: 0 15px;
        }

        .logout {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            text-decoration: none;
            padding: 12px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            transition: 0.3s;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .logout:hover {
            background: var(--danger);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
        }

        /* ================= MAIN CONTENT ================= */
        .main-content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .header {
            margin-bottom: 40px;
        }

        .header h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header p {
            color: var(--text-muted);
            font-size: 15px;
        }

        /* ================= CARDS ================= */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .card {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 25px;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        /* Aksen garis atas pada card */
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--accent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border-color: rgba(14, 165, 233, 0.3);
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        .card-info h3 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 0;
            color: white;
        }

        .card-info p {
            color: var(--text-muted);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        .card-icon {
            width: 60px;
            height: 60px;
            background: rgba(14, 165, 233, 0.1);
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            color: var(--accent);
        }

        /* Variasi Warna Card untuk membedakan data */
        .card:nth-child(2) .card-icon { color: #10b981; background: rgba(16, 185, 129, 0.1); }
        .card:nth-child(2)::before { background: #10b981; }
        .card:nth-child(2):hover { border-color: rgba(16, 185, 129, 0.3); }

        .card:nth-child(3) .card-icon { color: #f59e0b; background: rgba(245, 158, 11, 0.1); }
        .card:nth-child(3)::before { background: #f59e0b; }
        .card:nth-child(3):hover { border-color: rgba(245, 158, 11, 0.3); }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand">
            <i class="fa-solid fa-book-open-reader"></i> Perpus Daya
        </div>
        
        <div class="menu">
            <a href="dashboard.php" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a href="buku.php"><i class="fa-solid fa-book"></i> Kelola Buku</a>
            <a href="peminjaman.php"><i class="fa-solid fa-hand-holding-hand"></i> Peminjaman</a>
            </div>

        <div class="logout-container">
            <a class="logout" href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Dashboard Admin 👑</h2>
            <p>Ringkasan sistem dan aktivitas Perpustakaan Digital Daya hari ini.</p>
        </div>

        <div class="cards">
            
            <div class="card">
                <div class="card-info">
                    <h3><?= $jml_buku; ?></h3>
                    <p>Total Buku</p>
                </div>
                <div class="card-icon">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
            </div>

            <div class="card">
                <div class="card-info">
                    <h3><?= $jml_user; ?></h3>
                    <p>Total Siswa/User</p>
                </div>
                <div class="card-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

            <div class="card">
                <div class="card-info">
                    <h3><?= $jml_pinjam; ?></h3>
                    <p>Sedang Dipinjam</p>
                </div>
                <div class="card-icon">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>

        </div>
    </div>

</body>
</html>