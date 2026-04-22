<?php
include __DIR__."/../config/session.php";
include __DIR__."/../config/koneksi.php";

// DATA
$jml_buku = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM buku"))['total'];
$jml_user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM user WHERE role='user'"))['total'];
$jml_pinjam = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM peminjaman WHERE status='dipinjam'"))['total'];

// ambil buku terbaru
$recent = mysqli_query($conn,"SELECT * FROM buku ORDER BY id DESC LIMIT 6");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpus Daya</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #070b19;
            --bg-panel: rgba(15, 23, 42, 0.6);
            --bg-card: rgba(30, 41, 59, 0.7);
            --primary: #0ea5e9;
            --primary-glow: rgba(14, 165, 233, 0.4);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.05);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        body {
            display: flex;
            background: var(--bg-dark);
            background-image: 
                radial-gradient(circle at top right, rgba(30, 41, 59, 0.5) 0%, transparent 40%),
                radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.1) 0%, transparent 40%);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: var(--bg-panel);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid var(--border-color);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 22px;
            font-weight: 700;
            color: white;
            text-align: center;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar h2 i { color: var(--primary); }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            margin-bottom: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(14, 165, 233, 0.1);
            color: var(--primary);
            transform: translateX(5px);
        }

        .logout { margin-top: auto !important; color: #ef4444 !important; }
        .logout:hover { background: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important; }

        /* MAIN AREA */
        .main { flex: 1; display: flex; flex-direction: column; }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 40px;
            border-bottom: 1px solid var(--border-color);
            background: rgba(7, 11, 25, 0.8);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .header h2 { font-size: 24px; font-weight: 600; }
        .header .user-profile {
            display: flex; align-items: center; gap: 10px;
            background: var(--bg-card); padding: 8px 15px; border-radius: 20px;
            font-size: 14px; font-weight: 500; border: 1px solid var(--border-color);
        }

        /* STATS */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; padding: 40px; }

        .stat {
            background: var(--bg-card); backdrop-filter: blur(10px);
            padding: 25px; border-radius: 16px; border: 1px solid var(--border-color);
            transition: all 0.4s ease; display: flex; align-items: center; justify-content: space-between;
            position: relative; overflow: hidden;
        }

        .stat::before {
            content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%;
            background: var(--primary); transition: 0.4s;
        }

        .stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px var(--primary-glow);
            border-color: rgba(14, 165, 233, 0.3);
        }

        .stat-info h3 { font-size: 36px; font-weight: 700; color: white; margin-bottom: 5px; }
        .stat-info p { color: var(--text-muted); font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        
        .stat-icon {
            width: 60px; height: 60px; background: rgba(14, 165, 233, 0.1);
            border-radius: 12px; display: flex; justify-content: center; align-items: center;
            font-size: 28px; color: var(--primary);
        }

        .stat:nth-child(2) .stat-icon { color: #10b981; background: rgba(16, 185, 129, 0.1); }
        .stat:nth-child(2)::before { background: #10b981; }
        .stat:nth-child(3) .stat-icon { color: #f59e0b; background: rgba(245, 158, 11, 0.1); }
        .stat:nth-child(3)::before { background: #f59e0b; }

        /* GRID BUKU TERBARU */
        .section-title { padding: 0 40px; font-size: 20px; font-weight: 600; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
        .section-title i { color: var(--primary); }

        .grid { padding: 20px 40px 40px; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px; }

        /* CARD BUKU */
        .card {
            position: relative; border-radius: 16px; overflow: hidden;
            background: var(--bg-card); border: 1px solid var(--border-color);
            transition: all 0.4s ease; aspect-ratio: 2/3; cursor: pointer;
        }

        .card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.5); border-color: rgba(14, 165, 233, 0.4); }

        .card img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s ease; }
        .card:hover img { transform: scale(1.1); }

        /* OVERLAY GLASSMORPHISM */
        .overlay {
            position: absolute; bottom: 0; left: 0; width: 100%; height: 60%;
            background: linear-gradient(to top, rgba(7, 11, 25, 0.95) 0%, rgba(7, 11, 25, 0.7) 60%, transparent 100%);
            transition: 0.4s;
        }

        .info {
            position: absolute; bottom: 0; left: 0; width: 100%; padding: 20px;
            transform: translateY(10px); transition: 0.4s;
        }

        .card:hover .info { transform: translateY(0); }

        .info h3 { font-size: 16px; font-weight: 600; color: white; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .info p { font-size: 13px; color: var(--primary); font-weight: 500; display: flex; align-items: center; gap: 5px; }

        .badge-new {
            position: absolute; top: 10px; right: 10px; background: var(--primary);
            color: white; font-size: 11px; font-weight: 600; padding: 4px 10px;
            border-radius: 20px; box-shadow: 0 4px 10px var(--primary-glow); z-index: 2;
        }

        /* EMPTY STATE */
        .empty {
            grid-column: 1 / -1; text-align: center; padding: 60px;
            background: var(--bg-card); border-radius: 16px; border: 1px dashed rgba(255,255,255,0.1);
        }
        .empty i { font-size: 40px; color: var(--text-muted); margin-bottom: 15px; }
    </style>
</head>

<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-book-open-reader"></i> Admin</h2>
    <a href="dashboard.php" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
    <a href="buku.php"><i class="fa-solid fa-book"></i> Kelola Buku</a>
    <a href="peminjaman.php"><i class="fa-solid fa-hand-holding-hand"></i> Peminjaman</a>
       <a href="user.php" class="active"><i class="fa-solid fa-users"></i> Kelola Anggota</a>
    <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="main">

    <div class="header">
        <h2>Dashboard Admin</h2>
        <div class="user-profile">
            <i class="fa-solid fa-circle-user" style="font-size: 18px; color: var(--primary);"></i>
            Administrator
        </div>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="stat-info">
                <h3><?= $jml_buku ?></h3>
                <p>Total Buku</p>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-book-bookmark"></i></div>
        </div>

        <div class="stat">
            <div class="stat-info">
                <h3><?= $jml_user ?></h3>
                <p>Total User</p>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
        </div>

        <div class="stat">
            <div class="stat-info">
                <h3><?= $jml_pinjam ?></h3>
                <p>Sedang Dipinjam</p>
            </div>
            <div class="stat-icon"><i class="fa-solid fa-book-open"></i></div>
        </div>
    </div>

    <div class="section-title">
        <i class="fa-solid fa-sparkles"></i> Buku Terbaru Ditambahkan
    </div>

    <div class="grid">
        <?php if(mysqli_num_rows($recent) > 0){ ?>
            <?php while($b=mysqli_fetch_assoc($recent)){ ?>
            
            <div class="card">
                <div class="badge-new">NEW</div>
                <img src="../assets/img/<?= $b['gambar'] ?: 'noimage.png'; ?>" alt="Cover Buku">
                <div class="overlay"></div>
                <div class="info">
                    <h3><?= htmlspecialchars($b['judul']); ?></h3>
                    <p><i class="fa-solid fa-pen-nib"></i> <?= htmlspecialchars($b['penulis']); ?></p>
                </div>
            </div>

            <?php } ?>
        <?php } else { ?>
            <div class="empty">
                <i class="fa-regular fa-folder-open"></i>
                <h3>Belum ada koleksi buku</h3>
                <p style="color: #94a3b8; font-size: 14px; margin-top: 5px;">Buku yang baru ditambahkan akan muncul di sini.</p>
            </div>
        <?php } ?>
    </div>

</div>

</body>
</html>