<?php
session_start(); // Mulai sesi di awal file
// Mengimpor fungsi-fungsi umum
include 'functions1.php'; // Pastikan ini mengacu pada functions.php yang benar
$pdo = pdo_connect_mysql();
$msg = '';

date_default_timezone_set("Asia/Makassar");

// --- Cek apakah pengguna sudah login ---
if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== true || !isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Ambil ID pengguna dari sesi
$id_penyewa = $_SESSION['user_id']; 

// Mendapatkan Detail Mobil dari URL (car_id)
$selected_car_id = isset($_GET['car_id']) ? intval($_GET['car_id']) : 0;
$selected_car_name = ''; // Akan diambil dari DB
$selected_car_price = 0.00; // Akan diambil dari DB

// Ambil detail mobil dari tabel 'car' berdasarkan car_id
if ($selected_car_id > 0) {
    $stmt_car = $pdo->prepare('SELECT name, harga FROM car WHERE id = ?');
    $stmt_car->execute([$selected_car_id]);
    $car_details = $stmt_car->fetch(PDO::FETCH_ASSOC);

    if ($car_details) {
        $selected_car_name = htmlspecialchars($car_details['name']);
        // Bersihkan harga dari format non-numerik jika masih ada di database
        $selected_car_price = floatval(str_replace(['Rp.', ',', ' '], '', $car_details['harga']));
    } else {
        exit('Mobil tidak ditemukan atau tidak valid.');
    }
} else {
    exit('ID Mobil tidak ditentukan.');
}

// Proses Formulir Ketika Dikirim (POST request)
if (!empty($_POST)) {
    // ID Penyewa sudah diambil dari sesi di atas, tidak perlu lagi input manual atau hardcode.
    
    $jumlah_hari = isset($_POST['jumlah_hari']) ? intval($_POST['jumlah_hari']) : 0;
    $total_harga = isset($_POST['total_harga']) ? floatval($_POST['total_harga']) : 0.00;

    // Validasi dasar
    if ($jumlah_hari <= 0 || $selected_car_id <= 0) {
        $msg = 'Harap lengkapi semua data yang diperlukan (Jumlah Hari).';
    } 
    
    $d = strtotime("now");
    $time = date("d-m-Y h:i:sa", $d);

    if (empty($msg)) {
        try {
            // Memulai transaksi
            $pdo->beginTransaction();

            // 1. Query INSERT data ke tabel 'rentcar'
            $stmt_insert_rental = $pdo->prepare('INSERT INTO rentcar (id_penyewa, id_mobil, jumlah_hari, total_harga, time) VALUES (?, ?, ?, ?, ?)');
            $stmt_insert_rental->execute([$id_penyewa, $selected_car_id, $jumlah_hari, $total_harga, $time]); 
            
            // 2. Query UPDATE status mobil di tabel 'car'
            $stmt_update_car_status = $pdo->prepare('UPDATE car SET status = ? WHERE id = ?');
            $stmt_update_car_status->execute(['tidak_tersedia', $selected_car_id]);

            // Commit transaksi jika kedua operasi berhasil
            $pdo->commit();
            
            // Redirect ke halaman tabel setelah berhasil
            header("Location: index1.php");
            exit(); 

        } catch (PDOException $e) {
            // Rollback transaksi jika terjadi kesalahan
            $pdo->rollBack();
            $msg = 'Terjadi kesalahan saat membuat pesanan atau memperbarui status mobil: ' . $e->getMessage();
            // Anda bisa log $e->getMessage() untuk debugging lebih lanjut
        }
    }
}
?>

<?= template_header1('Buat Pesanan Baru') ?>

<div class="content update">
    <h2>FORMULIR PESANAN MOBIL</h2>
    <form action="create_user.php?car_id=<?= $selected_car_id ?>" method="post">
        <!-- Tampilkan detail mobil yang dipilih (readonly) -->
        <label for="mobil_dipilih">MOBIL YANG DIPILIH</label>
        <input type="text" id="mobil_dipilih" value="<?= $selected_car_name ?>" readonly>
        
        <label for="car_price_display">HARGA PER HARI</label>
        <input type="text" id="car_price_display" value="Rp <?= number_format($selected_car_price, 0, ',', '.') ?>" readonly>

        
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
    const jumlahHariInput = document.getElementById('jumlah_hari');
    const totalHargaDisplay = document.getElementById('total_harga_display');
    const totalHargaInput = document.getElementById('total_harga_input');
    const carPrice = <?= $selected_car_price ?>; 

    function calculateTotalPrice() {
        const jumlahHari = parseInt(jumlahHariInput.value);

        if (!isNaN(jumlahHari) && jumlahHari > 0) {
            const calculatedPrice = carPrice * jumlahHari;
            totalHargaDisplay.value = 'Rp ' + calculatedPrice.toLocaleString('id-ID');
            totalHargaInput.value = calculatedPrice;
        } else {
            totalHargaDisplay.value = 'Rp 0';
            totalHargaInput.value = 0;
        }
    }

    jumlahHariInput.addEventListener('input', calculateTotalPrice);
    calculateTotalPrice(); // Hitung saat halaman dimuat
</script>

<?= template_footer1() ?>
