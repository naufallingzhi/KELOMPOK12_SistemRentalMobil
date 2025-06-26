<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

// Inisialisasi variabel
$stats = [
    'count' => 0,
    'sum' => 0,
    'avg' => 0,
    'min' => 0,
    'max' => 0,
    'min_car' => 'N/A',
    'max_car' => 'N/A'
];

try {
    // COUNT - Jumlah total mobil
    $count_result = $pdo->query('SELECT COUNT(*) FROM car')->fetchColumn();
    $stats['count'] = $count_result;

    // SUM - Total harga semua mobil
    $sum_query = 'SELECT SUM(CAST(REPLACE(REPLACE(harga, "Rp.", ""), ".", "") AS DECIMAL(10,2))) FROM car';
    $sum_result = $pdo->query($sum_query)->fetchColumn();
    $stats['sum'] = floatval($sum_result);

    // AVG - Rata-rata harga mobil
    $avg_query = 'SELECT AVG(CAST(REPLACE(REPLACE(harga, "Rp.", ""), ".", "") AS DECIMAL(10,2))) FROM car';
    $avg_result = $pdo->query($avg_query)->fetchColumn();
    $stats['avg'] = floatval($avg_result);

    // MIN - Mobil termurah
    $min_query = 'SELECT name, harga FROM car ORDER BY CAST(REPLACE(REPLACE(harga, "Rp.", ""), ".", "") AS DECIMAL(10,2)) ASC LIMIT 1';
    $min_result = $pdo->query($min_query)->fetch(PDO::FETCH_ASSOC);
    if ($min_result) {
        $stats['min_car'] = $min_result['name'];
        $stats['min'] = floatval(str_replace(['Rp.', '.', ' '], '', $min_result['harga']));
    }

    // MAX - Mobil termahal
    $max_query = 'SELECT name, harga FROM car ORDER BY CAST(REPLACE(REPLACE(harga, "Rp.", ""), ".", "") AS DECIMAL(10,2)) DESC LIMIT 1';
    $max_result = $pdo->query($max_query)->fetch(PDO::FETCH_ASSOC);
    if ($max_result) {
        $stats['max_car'] = $max_result['name'];
        $stats['max'] = floatval(str_replace(['Rp.', '.', ' '], '', $max_result['harga']));
    }

} catch (PDOException $e) {
    exit('Error: ' . $e->getMessage());
}
?>

<?= template_header('Laporan Statistik Mobil') ?>

<style>
    .content {
        max-width: 900px;
        margin: 2rem auto;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
    }
    
    .content h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 2rem;
        font-size: 2.5rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 0.8rem;
        box-shadow: 0 0.3rem 0.8rem rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-card:nth-child(1) { border-left: 4px solid #3498db; }
    .stat-card:nth-child(2) { border-left: 4px solid #2ecc71; }
    .stat-card:nth-child(3) { border-left: 4px solid #f39c12; }
    .stat-card:nth-child(4) { border-left: 4px solid #e74c3c; }
    .stat-card:nth-child(5) { border-left: 4px solid #9b59b6; }
    
    .stat-card h3 {
        color: #34495e;
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }
    
    .stat-card .value {
        font-size: 2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .stat-card .desc {
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    
    .back-btn {
        display: block;
        width: fit-content;
        margin: 0 auto;
        padding: 1rem 2rem;
        background: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 0.5rem;
        transition: background 0.3s ease;
    }
    
    .back-btn:hover {
        background: #2980b9;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .content {
            margin: 1rem;
            padding: 1rem;
        }
    }
</style>

<div class="content">
    <h2>Statistik Mobil</h2>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Mobil</h3>
            <div class="value"><?= $stats['count'] ?></div>
            <div class="desc">Unit kendaraan</div>
        </div>
        
        <div class="stat-card">
            <h3>Total Harga</h3>
            <div class="value">Rp <?= number_format($stats['sum'], 0, ',', '.') ?></div>
            <div class="desc">Jumlah harga semua mobil</div>
        </div>
        
        <div class="stat-card">
            <h3>Rata-rata Harga</h3>
            <div class="value">Rp <?= number_format($stats['avg'], 0, ',', '.') ?></div>
            <div class="desc">Per hari</div>
        </div>
        
        <div class="stat-card">
            <h3>Mobil Termurah</h3>
            <div class="value"><?= htmlspecialchars($stats['min_car']) ?></div>
            <div class="desc">Rp <?= number_format($stats['min'], 0, ',', '.') ?></div>
        </div>
        
        <div class="stat-card">
            <h3>Mobil Termahal</h3>
            <div class="value"><?= htmlspecialchars($stats['max_car']) ?></div>
            <div class="desc">Rp <?= number_format($stats['max'], 0, ',', '.') ?></div>
        </div>
    </div>
    
    <a href="tambah_mobil.php" class="back-btn">Kembali ke Data Mobil</a>
</div>

<?= template_footer() ?>