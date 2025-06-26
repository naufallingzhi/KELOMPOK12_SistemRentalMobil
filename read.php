<?php
include 'functions.php';

$pdo = pdo_connect_mysql();
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 5;
$offset = ($page - 1) * $records_per_page;

// Ambil data dengan pagination
$stmt = $pdo->prepare('SELECT * FROM datasewa LIMIT :offset, :limit');
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total data untuk pagination
$total_records = $pdo->query('SELECT COUNT(*) FROM datasewa')->fetchColumn();

// Helper function untuk format harga
function formatPrice($price) {
    $clean_price = str_replace(['Rp.', ',', ' '], '', $price);
    return 'Rp ' . number_format(floatval($clean_price), 0, ',', '.');
}
?>

<?= template_header('Data Pesanan Rental Mobil') ?>

<div class="content read">
    <h2>DATA PESANAN</h2>
    
    <table border>
        <thead>
            <tr>
                <td>ID</td>
                <td>Nama Penyewa</td>
                <td>No. Telepon</td>
                <td>Alamat</td>
                <td>Mobil</td>
                <td>Transmisi</td>
                <td>Hari</td>
                <td>Total Harga</td>
                <td>Foto KTP</td>
                <td>Foto SIM</td>
                <td>Waktu Pesan</td>
                <td>Aksi</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= htmlspecialchars($order['nama_penyewa']) ?></td>
                <td><?= htmlspecialchars($order['telpon_penyewa']) ?></td>
                <td><?= htmlspecialchars($order['alamat_penyewa']) ?></td>
                <td><?= htmlspecialchars($order['nama_mobil']) ?></td>
                <td><?= htmlspecialchars($order['transmisi_mobil']) ?></td>
                <td><?= $order['jumlah_hari'] ?? 'N/A' ?> hari</td>
                <td><?= formatPrice($order['total_harga']) ?></td>
                <td>
                    <img src="image/<?= htmlspecialchars($order['fotoktp_penyewa']) ?>" 
                         alt="KTP" width="100">
                </td>
                <td>
                    <img src="image/<?= htmlspecialchars($order['fotosim_penyewa']) ?>" 
                         alt="SIM" width="100">
                </td>
                <td><?= $order['time'] ?></td>
                <td class="actions">
                    <a href="delete.php?id=<?= $order['id'] ?>" class="trash">
                        <i class="fas fa-trash fa-xs"></i>
                    </a>
                </td>   
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="read.php?page=<?= $page - 1 ?>">
                <i class="fas fa-angle-double-left fa-sm"></i>
            </a>
        <?php endif; ?>
        
        <?php if ($page * $records_per_page < $total_records): ?>
            <a href="read.php?page=<?= $page + 1 ?>">
                <i class="fas fa-angle-double-right fa-sm"></i>
            </a>
        <?php endif; ?>
    </div>
</div>

<?= template_footer() ?>