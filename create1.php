<?php
// Mengimpor fungsi-fungsi umum
include 'functions.php';

// Menghubungkan ke database MySQL
$pdo = pdo_connect_mysql();

// Variabel untuk menyimpan pesan status
$msg = '';

// --- Proses Formulir Ketika Dikirim (POST request) ---
if (!empty($_POST)) {
    // Mengambil data dari formulir
    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    
    $harga_raw = isset($_POST['harga']) ? $_POST['harga'] : '';
    // --- PENTING: Bersihkan string harga dari karakter non-numerik
    // Hapus "Rp.", koma (,), dan spasi, lalu konversi ke float.
    $harga = floatval(str_replace(['Rp.', ',', ' '], '', $harga_raw)); 

    $transmisi = isset($_POST['transmisi']) ? $_POST['transmisi'] : '';
    // Status default untuk mobil baru adalah 'tersedia'
    $status = 'tersedia'; 

    // Inisialisasi variabel untuk nama file gambar mobil
    $fotom = '';

    // Direktori tempat gambar mobil akan disimpan
    $target_dir = "image/";

    // --- Penanganan Upload File Gambar Mobil ---
    if (isset($_FILES['fotom']) && $_FILES['fotom']['error'] == 0) {
        $target_file = $target_dir . basename($_FILES["fotom"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Periksa apakah file adalah gambar dan formatnya diizinkan
        $check = getimagesize($_FILES["fotom"]["tmp_name"]);
        if ($check !== false) {
            if(in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                // Pindahkan file yang diunggah
                if (move_uploaded_file($_FILES["fotom"]["tmp_name"], $target_file)) {
                    $fotom = basename($_FILES["fotom"]["name"]); // Simpan nama file ke database
                } else {
                    $msg = 'Maaf, ada kesalahan saat mengunggah file gambar mobil.';
                }
            } else {
                $msg = 'Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan untuk gambar mobil.';
            }
        } else {
            $msg = 'File yang diunggah bukan gambar.';
        }
    } else {
        $msg = 'Tidak ada file gambar mobil yang diunggah atau terjadi error.';
    }

    // --- Simpan Data ke Database Jika Tidak Ada Error Upload ---
    if (empty($msg) || strpos($msg, 'Tidak ada file gambar mobil yang diunggah') !== false) {
        // Query INSERT data mobil ke tabel 'car'
        // Perhatikan urutan kolom: id, foto, name, harga, transmisi, status
        $stmt = $pdo->prepare('INSERT INTO car (id, foto, name, harga, transmisi, status) VALUES (?, ?, ?, ?, ?, ?)');
        // Menggunakan NULL untuk ID jika auto-increment
        $stmt->execute([NULL, $fotom, $name, $harga, $transmisi, $status]);
        $msg = 'Data mobil berhasil ditambahkan!';
    }
}
?>

<?=template_header('Tambah Mobil Baru')?>

<div class="content update">
	<h2>TAMBAH MOBIL BARU</h2>
    <!-- Atribut enctype="multipart/form-data" WAJIB untuk formulir yang mengunggah file -->
    <form action="create1.php" method="post" enctype="multipart/form-data">
        <label for="id">ID Mobil</label>
        <!-- Input ID diatur sebagai readonly karena biasanya otomatis (AUTO_INCREMENT) -->
        <input type="text" name="id" value="auto" id="id" readonly> 
        
        <label for="fotom">FOTO MOBIL</label>
        <!-- accept="image/*" membatasi pilihan hanya ke file gambar -->
        <input type="file" name="fotom" id="fotom" accept="image/*" required>
        
        <label for="name">NAMA MOBIL</label>
        <input type="text" name="name" id="name" required placeholder="Cth: Toyota Avanza">
        
        <label for="harga">HARGA PER HARI</label>
        <!-- Penting: Minta user memasukkan harga dalam format numerik saja (tanpa Rp, titik) -->
        <input type="text" name="harga" id="harga" required placeholder="Cth: 250000">
        
        <label for="transmisi">TRANSMISI</label>
        <input type="text" name="transmisi" id="transmisi" required placeholder="Cth: Otomatis / Manual">

        <input type="submit" value="Simpan Mobil">
    </form>
    <?php if ($msg): ?>
    <p class="msg-info"><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
