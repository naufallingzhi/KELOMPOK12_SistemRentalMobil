<?php
session_start(); 
include 'functions1.php'; 
$pdo = pdo_connect_mysql();
date_default_timezone_set("Asia/Jakarta");

// Check authentication
if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id_penyewa = $_SESSION['user_id']; 
$selected_car_id = intval($_GET['car_id'] ?? 0);
$msg = '';

// Get car details
if ($selected_car_id <= 0) exit('ID Mobil tidak ditentukan.');

$stmt = $pdo->prepare('SELECT name, harga FROM car WHERE id = ?');
$stmt->execute([$selected_car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) exit('Mobil tidak ditemukan atau tidak valid.');

$car_name = htmlspecialchars($car['name']);
$car_price = floatval(str_replace(['Rp.', ',', ' '], '', $car['harga']));

// Process form submission
if ($_POST) {
    $jumlah_hari = intval($_POST['jumlah_hari'] ?? 0);
    $total_harga = floatval($_POST['total_harga'] ?? 0);
    
    if ($jumlah_hari <= 0) {
        $msg = 'Harap lengkapi semua data yang diperlukan (Jumlah Hari).';
    } else {
        try {
            $pdo->beginTransaction();
            
            // Insert rental record
            $stmt = $pdo->prepare('INSERT INTO rentcar (id_penyewa, id_mobil, jumlah_hari, total_harga, time) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$id_penyewa, $selected_car_id, $jumlah_hari, $total_harga, date("d-m-Y h:i:sa")]);
            
            // Update car status
            $stmt = $pdo->prepare('UPDATE car SET status = ? WHERE id = ?');
            $stmt->execute(['tidak_tersedia', $selected_car_id]);
            
            $pdo->commit();
            header("Location: index1.php");
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $msg = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
}
?>

<?= template_header1('Buat Pesanan Baru') ?>
<div class="content update">
    <h2>FORMULIR PESANAN MOBIL</h2>
    <form action="create_user.php?car_id=<?= $selected_car_id ?>" method="post">
        <label for="mobil_dipilih">MOBIL YANG DIPILIH</label>
        <input type="text" id="mobil_dipilih" value="<?= $car_name ?>" readonly>
        
        <label for="car_price_display">HARGA PER HARI</label>
        <input type="text" id="car_price_display" value="Rp <?= number_format($car_price, 0, ',', '.') ?>" readonly>
        
        <label for="jumlah_hari">JUMLAH HARI RENTAL</label>
        <input type="number" name="jumlah_hari" id="jumlah_hari" min="1" value="1" required>
        
        <label for="total_harga_display">TOTAL HARGA</label>
        <input type="text" id="total_harga_display" value="Rp 0" readonly>
        <input type="hidden" name="total_harga" id="total_harga_input" value="0">
        
        <input type="submit" value="Buat Pesanan">
    </form>
    <?php if ($msg): ?>
        <p class="msg-info"><?= $msg ?></p>
    <?php endif; ?>
</div>

<script>
const jumlahHari = document.getElementById('jumlah_hari');
const totalDisplay = document.getElementById('total_harga_display');
const totalInput = document.getElementById('total_harga_input');
const carPrice = <?= $car_price ?>;

function calculateTotal() {
    const days = parseInt(jumlahHari.value) || 0;
    const total = days > 0 ? carPrice * days : 0;
    totalDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
    totalInput.value = total;
}

jumlahHari.addEventListener('input', calculateTotal);
calculateTotal();
</script>
<?= template_footer1() ?>