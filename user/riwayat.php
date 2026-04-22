<?php
session_start();
include __DIR__."/../config/koneksi.php";

/* CEK LOGIN */
if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['id'];

/* QUERY */
$data = mysqli_query($conn,"
SELECT peminjaman.*, buku.judul 
FROM peminjaman
JOIN buku ON buku.id = peminjaman.buku_id
WHERE peminjaman.user_id='$user_id'
ORDER BY peminjaman.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - Perpus Daya</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #070b19;
            --bg-card: rgba(30, 41, 59, 0.6);
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
            background: var(--bg-dark);
            background-image: 
                radial-gradient(circle at top right, rgba(30, 41, 59, 0.5) 0%, transparent 40%),
                radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.1) 0%, transparent 40%);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* NAVBAR ATAS (GLASSMORPHISM) */
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 40px; background: rgba(7, 11, 25, 0.8);
            backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-color);
            position: sticky; top: 0; z-index: 100;
        }
        .brand { font-size: 22px; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .brand i { color: var(--primary); }
        
        .nav-links { display: flex; gap: 20px; align-items: center; }
        .nav-links a {
            color: var(--text-muted); text-decoration: none; font-weight: 500;
            font-size: 15px; transition: 0.3s; display: flex; align-items: center; gap: 8px;
        }
        .nav-links a:hover, .nav-links a.active { color: var(--primary); }
        
        .logout {
            background: rgba(239, 68, 68, 0.1); color: var(--danger) !important;
            padding: 8px 15px; border-radius: 8px; border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .logout:hover { background: var(--danger); color: white !important; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); }

        /* CONTAINER & HEADER */
        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        
        .page-header { margin-bottom: 30px; }
        .page-header h2 { font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 10px; margin-bottom: 5px; }
        .page-header h2 i { color: var(--primary); }
        .page-header p { color: var(--text-muted); font-size: 14px; }

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
        th:nth-child(2) { text-align: left; } /* Judul buku rata kiri */
        th:first-child { border-top-left-radius: 10px; }
        th:last-child { border-top-right-radius: 10px; }

        td {
            padding: 15px 20px; font-size: 14px; color: var(--text-light); text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: 0.3s;
        }
        td:nth-child(2) { text-align: left; font-weight: 500; color: white; }

        tbody tr:hover { background: rgba(255, 255, 255, 0.03); }
        tbody tr:last-child td { border-bottom: none; }

        /* BADGE STATUS NEON */
        .badge {
            padding: 6px 12px; border-radius: 20px; font-size: 11px;
            font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
            display: inline-block; text-align: center; min-width: 90px;
        }
        .menunggu { background: rgba(245, 158, 11, 0.1); color: var(--warning); border: 1px solid rgba(245, 158, 11, 0.3); box-shadow: 0 0 10px rgba(245, 158, 11, 0.2); }
        .dipinjam { background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.3); box-shadow: 0 0 10px rgba(34, 197, 94, 0.2); }
        .kembali { background: rgba(14, 165, 233, 0.1); color: var(--primary); border: 1px solid rgba(14, 165, 233, 0.3); }
        .ditolak { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.3); }

        .date-cell { font-family: monospace; font-size: 13px; color: #cbd5e1; }

    </style>
</head>
<body>

    <div class="navbar">
        <div class="brand">
            <i class="fa-solid fa-book-open-reader"></i> Perpus Daya
        </div>
        <div class="nav-links">
            <a href="dashboard.php"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="riwayat.php" class="active"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pinjam</a>
            <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </div>
    </div>

    <div class="container">
        
        <div class="page-header">
            <h2><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Peminjaman</h2>
            <p>Pantau status buku yang sedang Anda ajukan, pinjam, maupun yang sudah dikembalikan.</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Status</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
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
                            <i class="fa-solid fa-bookmark" style="color: var(--primary); margin-right: 8px;"></i>
                            <?= htmlspecialchars($d['judul']); ?>
                        </td>

                        <td>
                            <span class="badge <?= $d['status']; ?>">
                                <?php 
                                    if($d['status'] == 'menunggu') echo '<i class="fa-solid fa-hourglass-half"></i> Menunggu';
                                    elseif($d['status'] == 'dipinjam') echo '<i class="fa-solid fa-book-open"></i> Dipinjam';
                                    elseif($d['status'] == 'kembali') echo '<i class="fa-solid fa-check-double"></i> Kembali';
                                    elseif($d['status'] == 'ditolak') echo '<i class="fa-solid fa-ban"></i> Ditolak';
                                    else echo $d['status'];
                                ?>
                            </span>
                        </td>

                        <td class="date-cell">
                            <?= $d['tanggal_pinjam'] ? date('d M Y', strtotime($d['tanggal_pinjam'])) : '-'; ?>
                        </td>
                        <td class="date-cell">
                            <?= $d['tanggal_kembali'] ? date('d M Y', strtotime($d['tanggal_kembali'])) : '-'; ?>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if(mysqli_num_rows($data) == 0): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            <i class="fa-solid fa-book-journal-whills" style="font-size: 30px; margin-bottom: 10px; display: block;"></i>
                            Anda belum pernah meminjam buku.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>