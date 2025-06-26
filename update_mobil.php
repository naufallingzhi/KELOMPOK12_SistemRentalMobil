<?php
// Mengimpor fungsi-fungsi umum seperti koneksi database dan template header/footer
include 'functions.php'; 

// Menghubungkan ke database MySQL
$pdo = pdo_connect_mysql();

// Variabel untuk menyimpan pesan status (misalnya, berhasil/gagal)
$msg = '';

// Menetapkan zona waktu ke Asia/Makassar
date_default_timezone_set("Asia/Makassar");

// Cek apakah ID mobil diberikan di URL (GET request)
if (isset($_GET['id'])) {
    // --- Proses Formulir Ketika Dikirim (POST request) ---
    if (!empty($_POST)) {
        // Mengambil data dari formulir POST
        $id = $_POST['id']; // ID mobil yang sedang diedit
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        
        $harga_raw = isset($_POST['harga']) ? $_POST['harga'] : '';
        // Bersihkan string harga dari karakter non-numerik dan konversi ke float
        $harga = floatval(str_replace(['Rp.', ',', ' '], '', $harga_raw)); 

        $transmisi = isset($_POST['transmisi']) ? $_POST['transmisi'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : 'tersedia'; 

        // Ambil nama file gambar lama dari database sebelum proses upload baru
        $stmt_current_image = $pdo->prepare('SELECT foto FROM car WHERE id = ?');
        $stmt_current_image->execute([$id]);
        $current_image = $stmt_current_image->fetch(PDO::FETCH_ASSOC);

        $fotom = $current_image['foto']; // Default: gunakan nama file yang sudah ada

        $target_dir = "image/"; // Direktori tempat gambar akan disimpan

        // --- Penanganan Upload File Gambar Mobil (Revisi untuk Update) ---
        // Fungsi ini akan menghapus file lama jika ada file baru yang diunggah
        function handleCarImageUploadUpdate($fileInputName, $targetDir, $currentFileName) {
            global $msg; // Akses variabel $msg global untuk menambahkan pesan error
            $fileNameToDb = $currentFileName; // Default: gunakan nama file yang sudah ada

            if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
                $target_file = $targetDir . basename($_FILES[$fileInputName]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                $check = getimagesize($_FILES[$fileInputName]["tmp_name"]);
                if ($check !== false && in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                    // Jika ada upload baru, hapus file lama dari server jika ada
                    if ($currentFileName && file_exists($targetDir . $currentFileName)) {
                        unlink($targetDir . $currentFileName);
                    }
                    // Pindahkan file yang diunggah ke direktori tujuan
                    if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $target_file)) {
                        $fileNameToDb = basename($_FILES[$fileInputName]["name"]); // Simpan hanya nama file
                    } else {
                        $msg .= 'Maaf, ada kesalahan saat mengunggah foto mobil baru. ';
                    }
                } else {
                    $msg .= 'File foto mobil bukan gambar atau format tidak diizinkan. ';
                }
            } else if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] > 0 && $_FILES[$fileInputName]['error'] != 4) {
                 // Menangani error upload selain "No file was uploaded" (error 4)
                $phpFileUploadErrors = array(
                    1 => 'Ukuran file melebihi upload_max_filesize di php.ini',
                    2 => 'Ukuran file melebihi MAX_FILE_SIZE yang ditentukan',
                    3 => 'File hanya terunggah sebagian',
                    6 => 'Folder sementara tidak ada',
                    7 => 'Gagal menulis file ke disk.',
                    8 => 'Ekstensi PHP menghentikan unggahan file.',
                );
                $msg .= 'Error upload foto mobil: ' . (isset($phpFileUploadErrors[$_FILES[$fileInputName]['error']]) ? $phpFileUploadErrors[$_FILES[$fileInputName]['error']] : 'Error tidak dikenal') . '. ';
            }
            return $fileNameToDb;
        }

        // Panggil fungsi penanganan upload untuk foto mobil
        $fotom = handleCarImageUploadUpdate('fotom', $target_dir, $fotom);
        
        // --- Simpan Perubahan ke Database ---
        if (empty($msg)) {
            // Query UPDATE data mobil ke tabel 'car' berdasarkan ID
            $stmt = $pdo->prepare('UPDATE car SET foto = ?, name = ?, harga = ?, transmisi = ?, status = ? WHERE id = ?');
            $stmt->execute([$fotom, $name, $harga, $transmisi, $status, $id]);
            
            // Redirect ke halaman tabel setelah berhasil update
            header("Location: tambah_mobil.php");
            exit(); 
        } else {
             $msg = 'Terjadi kesalahan saat memperbarui data mobil: ' . $msg;
        }
    }

    // --- Ambil Data Mobil Saat Ini untuk Ditampilkan di Formulir ---
    // Siapkan statement SQL untuk mengambil record berdasarkan ID dari GET parameter
    $stmt = $pdo->prepare('SELECT * FROM car WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika tidak ada record dengan ID tersebut, tampilkan pesan error dan keluar
    if (!$contact) {
        exit('Mobil tidak ditemukan dengan ID tersebut!');
    }

} else {
    // Jika tidak ada ID yang ditentukan di URL, tampilkan pesan error dan keluar
    exit('Tidak ada ID yang ditentukan!');
}
?>

<?=template_header('Edit Data Mobil')?>

<div class="content update">
    <h2>EDIT DATA MOBIL #<?=$contact['id']?></h2>
    <!-- Tambahkan enctype="multipart/form-data" untuk mengizinkan upload file -->
    <form action="update_mobil.php?id=<?=$contact['id']?>" method="post" enctype="multipart/form-data">
        <!-- Input tersembunyi untuk menyimpan ID mobil -->
        <input type="hidden" name="id" value="<?=$contact['id']?>">

        <label for="name">NAMA MOBIL</label>
        <input type="text" name="name" id="name" value="<?=htmlspecialchars($contact['name'])?>" required placeholder="Cth: Toyota Avanza">
        
        <label for="harga">HARGA PER HARI</label>
        <!-- Tampilkan harga yang sudah bersih untuk di edit -->
        <input type="text" name="harga" id="harga" value="<?=htmlspecialchars(str_replace(['Rp.', ',', ' '], '', $contact['harga']))?>" required placeholder="Cth: 250000">
        
        <label for="transmisi">TRANSMISI</label>
        <input type="text" name="transmisi" id="transmisi" value="<?=htmlspecialchars($contact['transmisi'])?>" required placeholder="Cth: Otomatis / Manual">

        <label for="status">STATUS KETERSEDIAAN</label>
        <select name="status" id="status" required>
            <option value="tersedia" <?= $contact['status'] == 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
            <option value="tidak_tersedia" <?= $contact['status'] == 'tidak_tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
        </select>
        
        <label for="fotom">FOTO MOBIL (Kosongkan jika tidak ingin mengubah)</label>
        <?php if ($contact['foto']): // Tampilkan foto mobil saat ini jika ada ?>
            <img src="image/<?= htmlspecialchars($contact['foto']) ?>" width="150" style="margin-bottom: 5px; display: block;"><br>
        <?php endif; ?>
        <input type="file" name="fotom" id="fotom" accept="image/*">
        
        <input type="submit" value="Update Mobil">
    </form>
    <?php if ($msg): ?>
    <p class="msg-info"><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
