<?php
include __DIR__."/../config/session.php";
include __DIR__."/../config/koneksi.php";

$user = $_SESSION['id'];

/* VALIDASI */
$search = '';
$filter = '';
$error  = '';

if(isset($_GET['search'])){
    $search = trim($_GET['search']);

    if(strlen($search) > 50){
        $error = "Pencarian terlalu panjang!";
        $search = '';
    }

    if(!preg_match("/^[a-zA-Z0-9\s]*$/",$search)){
        $error = "Hanya huruf & angka!";
        $search = '';
    }

    $search = mysqli_real_escape_string($conn,$search);
}

if(isset($_GET['filter'])){
    $allowed = ['','tersedia','habis'];

    if(in_array($_GET['filter'],$allowed)){
        $filter = $_GET['filter'];
    }
}

/* QUERY BUKU */
$where = "WHERE 1=1";

if($search){
    $where .= " AND judul LIKE '%$search%'";
}

if($filter == "tersedia"){
    $where .= " AND stok > 0";
} elseif($filter == "habis"){
    $where .= " AND stok = 0";
}

$buku = mysqli_query($conn,"SELECT * FROM buku $where ORDER BY id DESC");

/* QUERY PEMINJAMAN AKTIF */
$pinjam = mysqli_query($conn,"
SELECT peminjaman.*, buku.judul 
FROM peminjaman 
JOIN buku ON buku.id=peminjaman.buku_id
WHERE user_id='$user' AND status='dipinjam'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Perpus Daya</title>

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

        /* HERO & PENCARIAN */
        .hero { padding: 40px; text-align: center; }
        .hero h2 { font-size: 32px; font-weight: 700; margin-bottom: 10px; }
        .hero p { color: var(--text-muted); margin-bottom: 30px; font-size: 15px; }

        .search-box { max-width: 700px; margin: 0 auto; position: relative; }
        .search-box form { display: flex; gap: 10px; flex-wrap: wrap; }
        
        .search-box input, .search-box select {
            padding: 15px 20px; border-radius: 12px; border: 1px solid var(--border-color);
            background: rgba(15, 23, 42, 0.6); color: white; font-size: 14px;
            backdrop-filter: blur(10px); outline: none; transition: 0.3s;
        }
        .search-box input { flex: 1; min-width: 200px; }
        .search-box input:focus, .search-box select:focus {
            border-color: var(--primary); box-shadow: 0 0 15px var(--primary-glow);
        }
        
        .btn-search {
            padding: 15px 25px; border: none; border-radius: 12px;
            background: linear-gradient(135deg, #0ea5e9, #3b82f6); color: white;
            font-weight: 600; cursor: pointer; transition: 0.3s;
            box-shadow: 0 4px 15px var(--primary-glow);
        }
        .btn-search:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(14, 165, 233, 0.6); }

        .alert-error {
            max-width: 700px; margin: 0 auto 20px; padding: 12px; background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; color: #fca5a5;
            text-align: center; font-size: 14px;
        }

        /* SECTION LAYOUT */
        .section-container { padding: 0 40px 40px; }
        .section-title { font-size: 20px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; }
        .section-title i { color: var(--primary); }

        /* GRID BUKU */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px; }

        /* CARD BUKU (SAMA SEPERTI ADMIN) */
        .card {
            background: var(--bg-card); backdrop-filter: blur(10px);
            border: 1px solid var(--border-color); border-radius: 16px;
            overflow: hidden; transition: 0.4s ease; display: flex; flex-direction: column; position: relative;
        }
        .card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.5); border-color: rgba(14, 165, 233, 0.4); }

        .card-img-wrapper { height: 250px; overflow: hidden; position: relative; }
        .card-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .card:hover .card-img-wrapper img { transform: scale(1.1); }
        .card-img-wrapper::after {
            content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 60%;
            background: linear-gradient(to top, rgba(7, 11, 25, 0.95), transparent);
        }

        .badge {
            position: absolute; top: 15px; right: 15px; padding: 6px 12px; border-radius: 20px;
            font-size: 11px; font-weight: 600; color: white; z-index: 2; backdrop-filter: blur(5px);
        }
        .ada { background: rgba(34, 197, 94, 0.8); border: 1px solid var(--success); box-shadow: 0 0 10px rgba(34, 197, 94, 0.4); }
        .habis { background: rgba(239, 68, 68, 0.8); border: 1px solid var(--danger); box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); }

        .card-body { padding: 20px; flex: 1; display: flex; flex-direction: column; }
        .card-body h3 { font-size: 16px; font-weight: 600; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .card-body p.author { font-size: 13px; color: var(--text-muted); margin-bottom: 20px; }

        .btn-pinjam {
            margin-top: auto; padding: 10px; border-radius: 8px; text-align: center;
            background: linear-gradient(135deg, #0ea5e9, #3b82f6); color: white;
            text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.3s;
            box-shadow: 0 4px 15px var(--primary-glow);
        }
        .btn-pinjam:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(14, 165, 233, 0.6); }

        .btn-disabled {
            margin-top: auto; padding: 10px; border-radius: 8px; text-align: center;
            background: rgba(255, 255, 255, 0.05); color: var(--danger);
            font-size: 13px; font-weight: 600; border: 1px dashed var(--danger);
        }

        /* CARD BUKU DIPINJAM (SPECIAL STYLING) */
        .card-pinjam {
            background: rgba(14, 165, 233, 0.05); border: 1px solid rgba(14, 165, 233, 0.3);
            border-radius: 16px; padding: 20px; display: flex; flex-direction: column;
            justify-content: space-between; transition: 0.3s;
        }
        .card-pinjam:hover { transform: translateY(-5px); box-shadow: 0 10px 20px var(--primary-glow); background: rgba(14, 165, 233, 0.1); }
        .card-pinjam h3 { font-size: 16px; margin-bottom: 15px; color: white; }
        
        .btn-kembali {
            padding: 10px; border-radius: 8px; text-align: center; text-decoration: none;
            background: rgba(245, 158, 11, 0.1); color: var(--warning); border: 1px solid rgba(245, 158, 11, 0.4);
            font-size: 13px; font-weight: 600; transition: 0.3s;
        }
        .btn-kembali:hover { background: var(--warning); color: white; box-shadow: 0 0 15px rgba(245, 158, 11, 0.5); }

        /* EMPTY STATE */
        .empty-state {
            grid-column: 1 / -1; text-align: center; padding: 50px; background: var(--bg-card);
            border-radius: 16px; border: 1px dashed rgba(255,255,255,0.1); color: var(--text-muted);
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="brand">
            <i class="fa-solid fa-book-open-reader"></i> Perpus Daya
        </div>
        <div class="nav-links">
            <a href="dashboard.php" class="active"><i class="fa-solid fa-house"></i> Beranda</a>
            <a href="riwayat.php"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pinjam</a>
            <a href="../logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
        </div>
    </div>

    <div class="hero">
        <h2>Selamat Datang 👋</h2>
        <p>Jelajahi, cari, dan pinjam buku favoritmu dari koleksi digital kami.</p>

        <?php if($error){ ?>
            <div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?= $error; ?></div>
        <?php } ?>

        <div class="search-box">
            <form method="GET">
                <input type="text" name="search" placeholder="Ketik judul buku yang ingin dicari..." value="<?= htmlspecialchars($search); ?>">
                
                <select name="filter">
                    <option value="">Semua Status</option>
                    <option value="tersedia" <?= $filter=='tersedia'?'selected':''; ?>>Buku Tersedia</option>
                    <option value="habis" <?= $filter=='habis'?'selected':''; ?>>Stok Habis</option>
                </select>

                <button class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
            </form>
        </div>
    </div>

    <?php if(mysqli_num_rows($pinjam) > 0){ ?>
    <div class="section-container">
        <div class="section-title">
            <i class="fa-solid fa-book-open"></i> Sedang Dipinjam
        </div>
        <div class="grid">
            <?php while($p=mysqli_fetch_assoc($pinjam)){ ?>
            <div class="card-pinjam">
                <h3><i class="fa-solid fa-bookmark" style="color: var(--primary); margin-right: 8px;"></i> <?= htmlspecialchars($p['judul']); ?></h3>
                <a class="btn-kembali" href="kembali.php?id=<?= $p['id']; ?>" onclick="return confirm('Kembalikan buku ini sekarang?');">
                    <i class="fa-solid fa-rotate-left"></i> Kembalikan Buku
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>

    <div class="section-container">
        <div class="section-title">
            <i class="fa-solid fa-swatchbook"></i> Jelajahi Koleksi
        </div>
        
        <div class="grid">
            <?php if(mysqli_num_rows($buku) > 0){ ?>
                <?php while($b=mysqli_fetch_assoc($buku)){ ?>
                <div class="card">
                    <div class="card-img-wrapper">
                        <img src="../assets/img/<?= $b['gambar'] ?: 'noimage.png'; ?>" alt="Cover Buku">
                        <div class="badge <?= $b['stok']>0?'ada':'habis'; ?>">
                            <?= $b['stok']>0 ? '<i class="fa-solid fa-check"></i> Tersedia: '.$b['stok'] : '<i class="fa-solid fa-xmark"></i> Habis'; ?>
                        </div>
                    </div>

                    <div class="card-body">
                        <h3 title="<?= htmlspecialchars($b['judul']); ?>"><?= htmlspecialchars($b['judul']); ?></h3>
                        <p class="author"><i class="fa-solid fa-pen-nib"></i> <?= htmlspecialchars($b['penulis']); ?></p>

                        <?php if($b['stok'] > 0){ ?>
                            <a class="btn-pinjam" href="pinjam.php?id=<?= $b['id']; ?>">
                                <i class="fa-solid fa-hand-holding-hand"></i> Pinjam Buku
                            </a>
                        <?php } else { ?>
                            <div class="btn-disabled"><i class="fa-solid fa-ban"></i> Sedang Kosong</div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            <?php } else { ?>
                <div class="empty-state">
                    <i class="fa-solid fa-magnifying-glass-minus" style="font-size: 40px; margin-bottom: 15px; display: block;"></i>
                    Tidak ada buku yang ditemukan.
                </div>
            <?php } ?>
        </div>
    </div>

</body>
</html>