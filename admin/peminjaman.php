<?php
session_start();
include '../config/koneksi.php';

// 🔐 cek login admin
if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
    exit;
}

// 🔍 ambil data peminjaman + user + buku
$data = mysqli_query($conn, "
    SELECT peminjaman.*, user.username, buku.judul 
    FROM peminjaman
    JOIN user ON user.id = peminjaman.user_id
    JOIN buku ON buku.id = peminjaman.buku_id
    ORDER BY peminjaman.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peminjaman Admin - Perpus Daya</title>

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
            --info: #06b6d4; /* Warna khusus untuk menunggu_kembali */
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

        /* SIDEBAR KONSISTEN */
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
        .main { flex: 1; display: flex; flex-direction: column; padding: 40px; }

        /* HEADER */
        .header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px;
        }
        .header-title h2 { font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .header-title h2 i { color: var(--primary); }
        .header-title p { color: var(--text-muted); font-size: 14px; margin-top: 5px; }

        /* TABEL GLASSMORPHISM */
        .table-container {
            background: var(--bg-card); backdrop-filter: blur(10px);
            border: 1px solid var(--border-color); border-radius: 16px;
            padding: 20px; overflow-x: auto; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        
        thead { background: rgba(0, 0, 0, 0.2); }
        th {
            padding: 15px 20px; font-size: 13px; font-weight: 600;
            color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); text-align: center;
        }
        th:nth-child(2), th:nth-child(3) { text-align: left; }
        th:first-child { border-top-left-radius: 10px; }
        th:last-child { border-top-right-radius: 10px; }

        td {
            padding: 15px 20px; font-size: 14px; color: var(--text-light); text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: 0.3s;
        }
        td:nth-child(2) { font-weight: 600; color: white; text-align: left; }
        td:nth-child(3) { text-align: left; }

        tbody tr:hover { background: rgba(255, 255, 255, 0.03); }
        tbody tr:last-child td { border-bottom: none; }

        .date-cell { font-family: monospace; font-size: 13px; color: #cbd5e1; }

        /* BADGE STATUS NEON */
        .badge {
            padding: 6px 12px; border-radius: 20px; font-size: 11px;
            font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
            display: inline-block; text-align: center; min-width: 120px; /* Diperlebar untuk teks panjang */
        }
        .badge.menunggu { background: rgba(245, 158, 11, 0.1); color: var(--warning); border: 1px solid rgba(245, 158, 11, 0.3); box-shadow: 0 0 10px rgba(245, 158, 11, 0.2); }
        .badge.menunggu_kembali { background: rgba(6, 182, 212, 0.1); color: var(--info); border: 1px solid rgba(6, 182, 212, 0.3); box-shadow: 0 0 10px rgba(6, 182, 212, 0.2); }
        .badge.dipinjam { background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.3); }
        .badge.kembali { background: rgba(14, 165, 233, 0.1); color: var(--primary); border: 1px solid rgba(14, 165, 233, 0.3); }
        .badge.ditolak { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.3); }

        /* BUTTONS AKSI */
        .action-btns { display: flex; gap: 8px; justify-content: center; align-items: center; }
        
        .btn {
            padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 600;
            text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 5px;
            transition: 0.3s; border: 1px solid transparent; white-space: nowrap;
        }
        .btn.approve { background: rgba(34, 197, 94, 0.1); color: var(--success); border-color: rgba(34, 197, 94, 0.3); }
        .btn.approve:hover { background: var(--success); color: white; box-shadow: 0 0 15px rgba(34, 197, 94, 0.4); transform: translateY(-2px); }
        
        .btn.tolak { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
        .btn.tolak:hover { background: var(--danger); color: white; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); transform: translateY(-2px); }

        .btn.kembali-btn { background: rgba(14, 165, 233, 0.1); color: var(--primary); border-color: rgba(14, 165, 233, 0.3); }
        .btn.kembali-btn:hover { background: var(--primary); color: white; box-shadow: 0 0 15px rgba(14, 165, 233, 0.4); transform: translateY(-2px); }

        .btn-disabled { color: var(--text-muted); font-size: 18px; }

    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-book-open-reader"></i> Perpus Daya</h2>
    <a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
    <a href="buku.php"><i class="fa-solid fa-book"></i> Kelola Buku</a>
    <a href="peminjaman.php" class="active"><i class="fa-solid fa-hand-holding-hand"></i> Peminjaman</a>
    <a href="user.php"><i class="fa-solid fa-users"></i> Kelola Anggota</a>
    <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
</div>

<div class="main">

    <div class="header">
        <div class="header-title">
            <h2><i class="fa-solid fa-boxes-stacked"></i> Data Peminjaman</h2>
            <p>Otorisasi status pengajuan pinjam dan pengembalian buku.</p>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Judul Buku</th>
                    <th>Status</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Aksi Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                while($d = mysqli_fetch_assoc($data)){ 
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <i class="fa-regular fa-circle-user" style="color: var(--primary); margin-right: 5px;"></i> 
                        <?= htmlspecialchars($d['username']); ?>
                    </td>
                    <td><i class="fa-solid fa-bookmark" style="color: var(--text-muted); margin-right: 5px;"></i> <?= htmlspecialchars($d['judul']); ?></td>
                    
                    <td>
                        <span class="badge <?= $d['status']; ?>">
                            <?php 
                                if($d['status'] == 'menunggu') echo '<i class="fa-solid fa-hourglass-half"></i> Menunggu';
                                elseif($d['status'] == 'menunggu_kembali') echo '<i class="fa-solid fa-rotate"></i> Menunggu Kembali';
                                elseif($d['status'] == 'dipinjam') echo '<i class="fa-solid fa-book-open"></i> Dipinjam';
                                elseif($d['status'] == 'kembali') echo '<i class="fa-solid fa-check-double"></i> Selesai';
                                elseif($d['status'] == 'ditolak') echo '<i class="fa-solid fa-ban"></i> Ditolak';
                                else echo htmlspecialchars($d['status']);
                            ?>
                        </span>
                    </td>

                    <td class="date-cell">
                        <?= $d['tanggal_pinjam'] ? date('d M Y', strtotime($d['tanggal_pinjam'])) : '-'; ?>
                    </td>
                    <td class="date-cell">
                        <?= $d['tanggal_kembali'] ? date('d M Y', strtotime($d['tanggal_kembali'])) : '-'; ?>
                    </td>

                    <td>
                        <div class="action-btns">
                            <?php if($d['status'] == 'menunggu'){ ?>
                                <a href="approve_pinjam.php?id=<?= $d['id']; ?>" class="btn approve" title="Setujui Peminjaman">
                                    <i class="fa-solid fa-check"></i> ACC
                                </a>
                                <a href="tolak.php?id=<?= $d['id']; ?>" class="btn tolak" title="Tolak Peminjaman" onclick="return confirm('Yakin ingin menolak peminjaman ini?');">
                                    <i class="fa-solid fa-xmark"></i> Tolak
                                </a>
                            <?php } elseif($d['status'] == 'menunggu_kembali'){ ?>
                                <a href="approve_kembali.php?id=<?= $d['id']; ?>" class="btn kembali-btn" title="Verifikasi Pengembalian Buku">
                                    <i class="fa-solid fa-check-to-slot"></i> ACC Kembali
                                </a>
                            <?php } else { ?>
                                <span class="btn-disabled"><i class="fa-solid fa-minus"></i></span>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <?php } ?>

                <?php if(mysqli_num_rows($data) == 0): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fa-solid fa-inbox" style="font-size: 30px; margin-bottom: 10px; display: block;"></i>
                        Belum ada data peminjaman buku.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>