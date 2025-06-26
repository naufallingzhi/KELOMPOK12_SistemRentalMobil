<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

// Update status mobil
if (isset($_POST['update_status']) && !empty($_POST['car_id']) && in_array($_POST['new_status'], ['tersedia', 'tidak_tersedia'])) {
    try {
        $stmt = $pdo->prepare('UPDATE car SET status = ? WHERE id = ?');
        $stmt->execute([$_POST['new_status'], $_POST['car_id']]);
        $msg = 'Status mobil berhasil diperbarui!';
    } catch (PDOException $e) {
        $msg = 'Gagal memperbarui status: ' . $e->getMessage();
    }
}

// Ambil semua data mobil
$stmt = $pdo->prepare('SELECT * FROM car ORDER BY id');
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Data Mobil Tersedia')?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<div class="content read">
    <h2>DATA MOBIL</h2>
    <a href="create1.php" class="create-contact">Tambah Mobil Baru</a>
    
    <?php if (isset($msg)): ?>
    <p class="msg-info" style="color: green; font-weight: bold;"><?= $msg ?></p>
    <?php endif; ?>

    <table border id="table_id" class="display">
        <thead>
            <tr>    
                <td>ID</td>
                <td>FOTO MOBIL</td>
                <td>NAMA MOBIL</td>
                <td>HARGA</td>
                <td>TRANSMISI</td>
                <td>STATUS</td>
                <td>Aksi</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?=$contact['id']?></td>
                <td><img src="image/<?=$contact['foto']?>" alt="Foto Mobil" width="100px"></td>
                <td><?=$contact['name']?></td>
                <td>Rp <?=number_format((float)str_replace(['Rp.', ',', ' '], '', $contact['harga']), 0, ',', '.')?></td>
                <td><?=$contact['transmisi']?></td>
                <td>
                    <form action="tambah_mobil.php" method="post" style="display: flex; align-items: center; gap: 5px;">
                        <input type="hidden" name="car_id" value="<?=$contact['id']?>">
                        <input type="hidden" name="update_status" value="1">
                        <select name="new_status" onchange="this.form.submit()">
                            <option value="tersedia" <?=$contact['status'] == 'tersedia' ? 'selected' : ''?>>Tersedia</option>
                            <option value="tidak_tersedia" <?=$contact['status'] == 'tidak_tersedia' ? 'selected' : ''?>>Tidak Tersedia</option>
                        </select>
                    </form>
                </td>
                <td class="actions">
                    <a href="update_mobil.php?id=<?=$contact['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete_mobil.php?id=<?=$contact['id']?>" onclick="return confirm('Yakin ingin di hapus? ')" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<script>
$(document).ready(function() {
    $('#table_id').DataTable({
        "info": false,      // Menghilangkan "Showing 1 to 5 of 5 entries"
        "paging": false     // Menghilangkan pagination DataTables
    });
});
</script>

<?=template_footer()?>