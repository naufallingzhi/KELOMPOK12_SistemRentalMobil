<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
date_default_timezone_set("Asia/Makassar");

if (!isset($_GET['id']))
    exit('Tidak ada ID yang ditentukan!');

// Ambil data mobil saat ini
$stmt = $pdo->prepare('SELECT * FROM car WHERE id = ?');
$stmt->execute([$_GET['id']]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$contact)
    exit('Mobil tidak ditemukan dengan ID tersebut!');

// Proses update data
if (!empty($_POST)) {
    $id = $_POST['id'];
    $name = $_POST['name'] ?? '';
    $harga = (float) str_replace(['Rp.', ',', ' '], '', $_POST['harga'] ?? '');
    $transmisi = $_POST['transmisi'] ?? '';
    $status = $_POST['status'] ?? 'tersedia';
    $fotom = $contact['foto']; // Default gunakan foto lama

    // Handle upload foto baru
    if (isset($_FILES['fotom']) && $_FILES['fotom']['error'] == 0) {
        $target_dir = "image/";
        $target_file = $target_dir . basename($_FILES['fotom']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES['fotom']['tmp_name']);
        if ($check !== false && in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            // Hapus foto lama jika ada
            if ($fotom && file_exists($target_dir . $fotom)) {
                unlink($target_dir . $fotom);
            }
            // Upload foto baru
            if (move_uploaded_file($_FILES['fotom']['tmp_name'], $target_file)) {
                $fotom = basename($_FILES['fotom']['name']);
            } else {
                $msg = 'Gagal mengunggah foto mobil.';
            }
        } else {
            $msg = 'File bukan gambar atau format tidak diizinkan.';
        }
    }

    // Update database jika tidak ada error
    if (empty($msg)) {
        $stmt = $pdo->prepare('UPDATE car SET foto = ?, name = ?, harga = ?, transmisi = ?, status = ? WHERE id = ?');
        $stmt->execute([$fotom, $name, $harga, $transmisi, $status, $id]);
        header("Location: tambah_mobil.php");
        exit();
    }
}
?>

<?= template_header('Edit Data Mobil') ?>

<div class="content update">
    <h2>EDIT DATA MOBIL #<?= $contact['id'] ?></h2>
    <form action="update_mobil.php?id=<?= $contact['id'] ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $contact['id'] ?>">

        <label for="name">NAMA MOBIL</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($contact['name']) ?>" required
            placeholder="Cth: Toyota Avanza">

        <label for="harga">HARGA PER HARI</label>
        <input type="text" name="harga" id="harga"
            value="<?= htmlspecialchars(str_replace(['Rp.', ',', ' '], '', $contact['harga'])) ?>" required
            placeholder="Cth: 250000">

        <label for="transmisi">TRANSMISI</label>
        <input type="text" name="transmisi" id="transmisi" value="<?= htmlspecialchars($contact['transmisi']) ?>" required
            placeholder="Cth: Otomatis / Manual">

        <label for="status">STATUS KETERSEDIAAN</label>
        <select name="status" id="status" required>
            <option value="tersedia" <?= $contact['status'] == 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
            <option value="tidak_tersedia" <?= $contact['status'] == 'tidak_tersedia' ? 'selected' : '' ?>>Tidak Tersedia
            </option>
        </select>

        <label for="fotom">FOTO MOBIL (Kosongkan jika tidak ingin mengubah)</label>
        <?php if ($contact['foto']): ?>
            <img src="image/<?= htmlspecialchars($contact['foto']) ?>" width="150"
                style="margin-bottom: 5px; display: block;"><br>
        <?php endif; ?>
        <input type="file" name="fotom" id="fotom" accept="image/*">

        <input type="submit" value="Update Mobil">
    </form>
    <?php if ($msg): ?>
        <p class="msg-info"><?= $msg ?></p>
    <?php endif; ?>
</div>

<?= template_footer() ?>