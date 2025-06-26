<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if ($_POST) {
    $name = $_POST['name'] ?? '';
    $harga = floatval(str_replace(['Rp.', ',', ' '], '', $_POST['harga'] ?? ''));
    $transmisi = $_POST['transmisi'] ?? '';
    $fotom = '';
    
    // Handle file upload
    if (isset($_FILES['fotom']) && $_FILES['fotom']['error'] == 0) {
        $target_dir = "image/";
        $file_name = basename($_FILES["fotom"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Validate image
        if (getimagesize($_FILES["fotom"]["tmp_name"]) && 
            in_array($file_type, ["jpg", "png", "jpeg", "gif"]) &&
            move_uploaded_file($_FILES["fotom"]["tmp_name"], $target_file)) {
            $fotom = $file_name;
        } else {
            $msg = 'Error upload gambar. Format harus JPG, PNG, JPEG, atau GIF.';
        }
    } else {
        $msg = 'Gambar mobil wajib diunggah.';
    }
    
    // Save to database if no upload error
    if (empty($msg)) {
        $stmt = $pdo->prepare('INSERT INTO car (foto, name, harga, transmisi, status) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$fotom, $name, $harga, $transmisi, 'tersedia']);
        header("Location: tambah_mobil.php?success=1");
        exit();
    }
}
?>

<?= template_header('Tambah Mobil Baru') ?>

<div class="content update">
    <h2>TAMBAH MOBIL BARU</h2>
    <form action="create1.php" method="post" enctype="multipart/form-data">
        <label for="fotom">FOTO MOBIL</label>
        <input type="file" name="fotom" id="fotom" accept="image/*" required>
        
        <label for="name">NAMA MOBIL</label>
        <input type="text" name="name" id="name" required placeholder="Cth: Toyota Avanza">
        
        <label for="harga">HARGA PER HARI</label>
        <input type="text" name="harga" id="harga" required placeholder="Cth: 250000">
        
        <label for="transmisi">TRANSMISI</label>
        <input type="text" name="transmisi" id="transmisi" required placeholder="Cth: Otomatis / Manual">

        <input type="submit" value="Simpan Mobil">
    </form>
    <?php if ($msg): ?>
        <p class="msg-info"><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>