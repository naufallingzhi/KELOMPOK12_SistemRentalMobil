<?php
// Mengimpor fungsi-fungsi umum
include 'functions.php';

// Menghubungkan ke database MySQL
$pdo = pdo_connect_mysql();

// --- Logika Penanganan Update Status Mobil ---
if (isset($_POST['update_status'])) {
    $car_id_to_update = $_POST['car_id'];
    $new_status = $_POST['new_status'];

    // Pastikan ID valid dan status adalah salah satu dari yang diizinkan
    if (!empty($car_id_to_update) && in_array($new_status, ['tersedia', 'tidak_tersedia'])) {
        try {
            $stmt = $pdo->prepare('UPDATE car SET status = ? WHERE id = ?');
            $stmt->execute([$new_status, $car_id_to_update]);
            $msg = 'Status mobil berhasil diperbarui!';
        } catch (PDOException $e) {
            $msg = 'Gagal memperbarui status: ' . $e->getMessage();
        }
    } else {
        $msg = 'Data pembaruan status tidak valid.';
    }
}

// Mengatur halaman saat ini melalui parameter GET 'page', default ke 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Jumlah record (baris data) yang akan ditampilkan per halaman
$records_per_page = 5;

// Menyiapkan statement SQL untuk mengambil data dari tabel 'car'
// Mengurutkan berdasarkan 'id' dan membatasi jumlah record per halaman
$stmt = $pdo->prepare('SELECT * FROM car ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Mengambil semua record yang ditemukan sebagai array asosiatif
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mendapatkan total jumlah record di tabel 'car'
$num_contacts = $pdo->query('SELECT COUNT(*) FROM car')->fetchColumn();
?>

<?=template_header('Data Mobil Tersedia')?>

<!-- Memuat DataTables CSS dan JS untuk tampilan tabel yang lebih baik -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

<div class="content read">
	<h2>DATA MOBIL</h2>
    <a href="create1.php" class="create-contact">Tambah Mobil Baru</a>
    
    <?php if (isset($msg)): ?>
    <p class="msg-info" style="color: green; font-weight: bold;"><?= $msg ?></p>
    <?php endif; ?>

    <!-- Tabel untuk menampilkan data mobil -->
	<table border id="table_id" class="display">
        <thead>
            <tr>    
                <td>ID</td>
                <td>FOTO MOBIL</td>
                <td>NAMA MOBIL</td>
                <td>HARGA</td>
                <td>TRANSMISI</td>
                <td>STATUS</td> <!-- Kolom baru untuk status ketersediaan -->
                <td>Aksi</td>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Loop melalui setiap baris data mobil
            foreach ($contacts as $contact): ?>
            <tr>
                <td><?=$contact['id']?></td>
                <!-- Menampilkan gambar mobil -->
                <td><img src="image/<?php echo $contact["foto"]; ?>" alt="Foto Mobil" width="100px" height="auto"></td>
                <td><?=$contact['name']?></td>
                <td>
                    Rp <?php
                    // Membersihkan string harga dari karakter non-numerik seperti 'Rp.', ',', dan spasi,
                    // kemudian mengkonversinya menjadi float sebelum diformat.
                    $clean_harga = str_replace(['Rp.', ',', ' '], '', $contact['harga']);
                    echo number_format(floatval($clean_harga), 0, ',', '.');
                    ?>
                </td>
                <td><?=$contact['transmisi']?></td>
                <td>
                    <!-- Formulir untuk mengubah status ketersediaan -->
                    <form action="tambah_mobil.php" method="post" style="display: flex; align-items: center; gap: 5px;">
                        <input type="hidden" name="car_id" value="<?=$contact['id']?>">
                        <select name="new_status" onchange="this.form.submit()">
                            <option value="tersedia" <?= $contact['status'] == 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                            <option value="tidak_tersedia" <?= $contact['status'] == 'tidak_tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                        </select>
                        <input type="hidden" name="update_status" value="1">
                        <!-- Tombol submit otomatis saat pilihan diubah -->
                        <!-- <button type="submit" name="update_status">Update</button> -->
                    </form>
                </td>
                <td class="actions">
                    <!-- Tombol edit (Anda masih perlu membuat file update_mobil.php jika ingin fitur edit penuh) -->
                    <a href="update_mobil.php?id=<?=$contact['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <!-- Tombol hapus -->
                    <a href="delete_mobil.php?id=<?=$contact['id']?>"onclick="return confirm('Yakin ingin di hapus? ')" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="tambah_mobil.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_contacts): ?>
		<a href="tambah_mobil.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<script>
    // Inisialisasi DataTables setelah dokumen siap
    $(document).ready(function () {
        $('#table_id').DataTable();
    });
</script>

<?=template_footer()?>
