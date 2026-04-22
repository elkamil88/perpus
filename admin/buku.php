<?php
include __DIR__."/../config/session.php";
include __DIR__."/../config/koneksi.php";

// Saya tambahkan "ORDER BY id DESC" agar buku yang baru ditambahkan muncul paling awal
$data = mysqli_query($conn,"SELECT * FROM buku ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku - Perpus Daya</title>

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
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
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

        /* SIDEBAR (Konsisten dengan Dashboard) */
        .sidebar {
            width: 250px; height: 100vh; background: var(--bg-panel);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid var(--border-color); padding: 30px 20px;
            display: flex; flex-direction: column; position: sticky; top: 0; z-index: 100;
        }
        .sidebar h2 {
            margin-bottom: 30px; font-size: 22px; font-weight: 700; color: white;
            text-align: center; letter-spacing: 1px; display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .sidebar h2 i { color: var(--primary); }
        .sidebar a {
            display: flex; align-items: center; gap: 12px; padding: 12px 15px;
            border-radius: 10px; color: var(--text-muted); text-decoration: none;
            margin-bottom: 8px; font-weight: 500; transition: all 0.3s ease;
        }
        .sidebar a:hover, .sidebar a.active {
            background: rgba(14, 165, 233, 0.1); color: var(--primary); transform: translateX(5px);
        }
        .logout { margin-top: auto !important; color: var(--danger) !important; }
        .logout:hover { background: rgba(239, 68, 68, 0.1) !important; color: var(--danger) !important; }

        /* MAIN AREA */
        .main { flex: 1; display: flex; flex-direction: column; }

        /* HEADER */
        .header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 25px 40px; border-bottom: 1px solid var(--border-color);
            background: rgba(7, 11, 25, 0.8); backdrop-filter: blur(10px);
            position: sticky; top: 0; z-index: 10;
        }
        .header-title h2 { font-size: 24px; font-weight: 600; margin-bottom: 5px; }
        .header-title p { font-size: 13px; color: var(--text-muted); }

        .btn-add {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6); color: white;
            padding: 10px 20px; border-radius: 8px; text-decoration: none;
            font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;
            box-shadow: 0 4px 15px var(--primary-glow); transition: 0.3s; border: none;
        }
        .btn-add:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(14, 165, 233, 0.6); }

        /* GRID BUKU */
        .grid {
            padding: 40px; display: grid; gap: 25px;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }

        /* CARD BUKU */
        .card {
            background: var(--bg-card); backdrop-filter: blur(10px);
            border: 1px solid var(--border-color); border-radius: 16px;
            overflow: hidden; display: flex; flex-direction: column;
            transition: all 0.4s ease; position: relative;
        }
        .card:hover {
            transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.5);
            border-color: rgba(14, 165, 233, 0.4);
        }

        /* CARD IMAGE */
        .card-img-wrapper { position: relative; height: 260px; overflow: hidden; }
        .card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s ease; }
        .card:hover .card-img-wrapper img { transform: scale(1.1); }
        
        /* OVERLAY GRADIENT PADA GAMBAR */
        .card-img-wrapper::after {
            content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 50%;
            background: linear-gradient(to top, rgba(7, 11, 25, 0.9), transparent);
        }

        /* BADGE STOK */
        .badge {
            position: absolute; top: 15px; right: 15px; padding: 6px 12px;
            border-radius: 20px; font-size: 11px; font-weight: 600;
            backdrop-filter: blur(5px); z-index: 2; color: white; letter-spacing: 0.5px;
        }
        .stok-aman { background: rgba(34, 197, 94, 0.8); border: 1px solid var(--success); box-shadow: 0 0 10px rgba(34, 197, 94, 0.4); }
        .stok-habis { background: rgba(239, 68, 68, 0.8); border: 1px solid var(--danger); box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); }

        /* CARD BODY */
        .card-body { padding: 20px; display: flex; flex-direction: column; flex: 1; }
        .card-body h3 { font-size: 18px; font-weight: 600; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .card-body p { font-size: 13px; color: var(--text-muted); margin-bottom: 25px; display: flex; align-items: center; gap: 8px; }
        .card-body p i { color: var(--primary); }

        /* CARD ACTIONS (TOMBOL EDIT/HAPUS) */
        .actions { display: flex; gap: 10px; margin-top: auto; }
        .btn-action {
            flex: 1; padding: 10px; border-radius: 8px; font-size: 13px; font-weight: 500;
            text-align: center; text-decoration: none; display: flex; justify-content: center;
            align-items: center; gap: 6px; transition: 0.3s; border: 1px solid transparent;
        }
        .btn-edit { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-color: rgba(59, 130, 246, 0.3); }
        .btn-edit:hover { background: #3b82f6; color: white; box-shadow: 0 0 15px rgba(59, 130, 246, 0.4); }
        
        .btn-hapus { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
        .btn-hapus:hover { background: var(--danger); color: white; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); }

        /* JIKA KOSONG */
        .empty-state { grid-column: 1 / -1; text-align: center; padding: 60px; background: var(--bg-card); border-radius: 16px; border: 1px dashed rgba(255,255,255,0.1); }
        .empty-state i { font-size: 40px; color: var(--text-muted); margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-book-open-reader"></i> Perpus Daya</h2>
    <a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
    <a href="buku.php" class="active"><i class="fa-solid fa-book"></i> Kelola Buku</a>
    <a href="peminjaman.php"><i class="fa-solid fa-hand-holding-hand"></i> Peminjaman</a>
    <a href="user.php" class="active"><i class="fa-solid fa-users"></i> Kelola Anggota</a>
    <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="main">

    <div class="header">
        <div class="header-title">
            <h2>Kelola Buku</h2>
            <p>Manajemen koleksi arsip perpustakaan digital.</p>
        </div>
        <a class="btn-add" href="tambah_buku.php">
            <i class="fa-solid fa-circle-plus"></i> Tambah Buku Baru
        </a>
    </div>

    <div class="grid">
        
        <?php if(mysqli_num_rows($data) > 0): ?>
            <?php while($b = mysqli_fetch_assoc($data)): ?>
            
            <div class="card">
                <div class="card-img-wrapper">
                    <img src="../assets/img/<?= $b['gambar'] ?: 'noimage.png'; ?>" alt="Cover <?= htmlspecialchars($b['judul']); ?>">
                    <div class="badge <?= $b['stok'] > 0 ? 'stok-aman' : 'stok-habis'; ?>">
                        <?= $b['stok'] > 0 ? '<i class="fa-solid fa-check"></i> Stok: '.$b['stok'] : '<i class="fa-solid fa-xmark"></i> Habis'; ?>
                    </div>
                </div>

                <div class="card-body">
                    <h3 title="<?= htmlspecialchars($b['judul']); ?>"><?= htmlspecialchars($b['judul']); ?></h3>
                    <p><i class="fa-solid fa-pen-nib"></i> <?= htmlspecialchars($b['penulis']); ?></p>

                    <div class="actions">
                        <a class="btn-action btn-edit" href="edit_buku.php?id=<?= $b['id']; ?>">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                        <a class="btn-action btn-hapus" href="hapus_buku.php?id=<?= $b['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini dari database?')">
                            <i class="fa-solid fa-trash-can"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-regular fa-folder-open"></i>
                <h3>Belum ada data buku</h3>
                <p style="color: var(--text-muted); font-size: 14px; margin-top: 5px;">Silakan klik tombol "Tambah Buku Baru" di kanan atas.</p>
            </div>
        <?php endif; ?>

    </div>

</div>

</body>
</html>