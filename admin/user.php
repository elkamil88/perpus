<?php
include __DIR__."/../config/session.php";
include __DIR__."/../config/koneksi.php";

// Menambahkan ORDER BY agar anggota terbaru muncul paling atas
$data = mysqli_query($conn,"SELECT * FROM user ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Anggota - Perpus Daya</title>

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
            --admin-badge: #8b5cf6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        body {
            display: flex; background: var(--bg-dark);
            background-image: 
                radial-gradient(circle at top right, rgba(30, 41, 59, 0.5) 0%, transparent 40%),
                radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.1) 0%, transparent 40%);
            color: var(--text-main); min-height: 100vh; overflow-x: hidden;
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
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;
        }
        .header-title h2 { font-size: 28px; font-weight: 700; display: flex; align-items: center; gap: 10px; margin-bottom: 5px; }
        .header-title p { color: var(--text-muted); font-size: 14px; }

        .btn-add {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6); color: white;
            padding: 10px 20px; border-radius: 8px; text-decoration: none;
            font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;
            box-shadow: 0 4px 15px var(--primary-glow); transition: 0.3s; border: none;
        }
        .btn-add:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(14, 165, 233, 0.6); }

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
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        th:first-child { border-top-left-radius: 10px; }
        th:last-child { border-top-right-radius: 10px; text-align: center; }

        td {
            padding: 15px 20px; font-size: 14px; color: var(--text-light);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05); transition: 0.3s;
        }

        tbody tr:hover { background: rgba(255, 255, 255, 0.03); }
        tbody tr:last-child td { border-bottom: none; }

        /* ROLE BADGES */
        .role-badge {
            padding: 5px 12px; border-radius: 20px; font-size: 11px;
            font-weight: 600; text-transform: uppercase; letter-spacing: 1px;
        }
        .role-admin { background: rgba(139, 92, 246, 0.1); color: var(--admin-badge); border: 1px solid rgba(139, 92, 246, 0.3); }
        .role-user { background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.3); }

        /* ACTION BUTTONS */
        .action-btns { display: flex; gap: 10px; justify-content: center; }
        
        .btn-action {
            width: 35px; height: 35px; border-radius: 8px; display: flex;
            justify-content: center; align-items: center; text-decoration: none;
            transition: 0.3s; border: 1px solid transparent;
        }
        .btn-edit { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-color: rgba(59, 130, 246, 0.3); }
        .btn-edit:hover { background: #3b82f6; color: white; box-shadow: 0 0 10px rgba(59, 130, 246, 0.4); transform: translateY(-2px); }
        
        .btn-hapus { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
        .btn-hapus:hover { background: var(--danger); color: white; box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); transform: translateY(-2px); }

    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-book-open-reader"></i> Perpus Daya</h2>
    <a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
    <a href="buku.php"><i class="fa-solid fa-book"></i> Kelola Buku</a>
    <a href="peminjaman.php"><i class="fa-solid fa-hand-holding-hand"></i> Peminjaman</a>
    <a href="user.php" class="active"><i class="fa-solid fa-users"></i> Kelola Anggota</a>
    <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>

<div class="main">

    <div class="header">
        <div class="header-title">
            <h2><i class="fa-solid fa-users-gear"></i> Kelola Anggota</h2>
            <p>Manajemen data akun administrator dan siswa (user).</p>
        </div>
        <a class="btn-add" href="tambah_user.php">
            <i class="fa-solid fa-user-plus"></i> Tambah Anggota
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($u = mysqli_fetch_assoc($data)){ 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="font-weight: 500; color: white;">
                        <i class="fa-regular fa-circle-user" style="color: var(--primary); margin-right: 5px;"></i>
                        <?= htmlspecialchars($u['username']) ?>
                    </td>
                    <td style="color: var(--text-muted);"><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span class="role-badge <?= $u['role'] == 'admin' ? 'role-admin' : 'role-user' ?>">
                            <?= $u['role'] == 'admin' ? '<i class="fa-solid fa-shield-halved"></i> ADMIN' : '<i class="fa-solid fa-user"></i> USER' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a class="btn-action btn-edit" href="edit_user.php?id=<?= $u['id'] ?>" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <?php if($u['id'] != $_SESSION['id']): ?>
                            <a class="btn-action btn-hapus" href="hapus_user.php?id=<?= $u['id'] ?>" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini secara permanen?')">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                            <?php else: ?>
                            <div class="btn-action" style="opacity: 0.3; cursor: not-allowed;" title="Tidak dapat menghapus akun sendiri">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php } ?>

                <?php if(mysqli_num_rows($data) == 0): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">
                        Belum ada data anggota yang terdaftar.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>