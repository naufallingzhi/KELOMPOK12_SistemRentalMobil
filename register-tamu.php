<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Register</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="container regis">

        <div class="judul">
            <h2>Registrasi</h2>
        </div>
        
        <div class="form">
            <!-- Tambahkan enctype="multipart/form-data" untuk mengizinkan upload file -->
            <form action="" method="post" enctype="multipart/form-data">
                <label for="nama">Nama</label><br>
                <input type="text" name="nama" class="input" placeholder="Masukkan nama" required><br>

                <label for="email">Email</label><br>
                <input type="email" name="email" class="input" placeholder="Masukkan email" required><br>

                <label for="username">Username</label><br>
                <input type="text" name="username" class="input" placeholder="Masukkan username" required><br>

                <label for="password">Password</label><br>
                <input type="password" name="password" class="input" placeholder="Password" required><br>

                <label for="konfirmasi">Konfirmasi Password</label><br>
                <input type="password" name="konfirm" class="input" placeholder="Konfirmasi password" required><br>

                <label for="alamat">Alamat</label><br>
                <input type="text" name="alamat" class="input" placeholder="Masukkan alamat lengkap" required><br>

                <label for="telpon">Nomor Telepon</label><br>
                <input type="text" name="telepon" class="input" placeholder="Masukkan nomor telepon" required><br>

                <label for="fotoktp">Foto KTP</label><br>
                <input type="file" name="fotoktp" id="fotoktp" class="input-file" accept="image/*" required><br>

                <label for="fotosim">Foto SIM</label><br>
                <input type="file" name="fotosim" id="fotosim" class="input-file" accept="image/*" required><br>

                <input type="submit" name="regis" class="submit" value="Registrasi"><br><br>
            </form>

            <p>Sudah punya akun?
                <a href="login.php">Login</a>
            </p>
        
        </div>
    </div>
</body>
</html>

<?php
require 'config.php'; // Pastikan ini mengacu ke file koneksi database Anda
if(isset($_POST['regis'])){
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirm'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];

    // Inisialisasi variabel untuk nama file gambar
    $fotoktp = '';
    $fotosim = '';

    $target_dir = "image/"; // Direktori tempat gambar akan disimpan

    // Fungsi bantuan untuk mengunggah file
    function handleUpload($fileInputName, $targetDir) {
        global $msg; // Mengakses variabel $msg global untuk pesan error
        $fileNameToDb = '';

        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) { // Hanya proses jika tidak ada error upload
            $target_file = $targetDir . basename($_FILES[$fileInputName]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES[$fileInputName]["tmp_name"]);
            if ($check !== false) {
                if(in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                    if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $target_file)) {
                        $fileNameToDb = basename($_FILES[$fileInputName]["name"]);
                    } else {
                        $msg .= "Maaf, ada kesalahan saat mengunggah file {$fileInputName}. ";
                    }
                } else {
                    $msg .= "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan untuk {$fileInputName}. ";
                }
            } else {
                $msg .= "File yang diunggah untuk {$fileInputName} bukan gambar. ";
            }
        } else { // Jika ada error upload apa pun (selain error 0)
            // Ini akan menangkap semua error lainnya, termasuk UPLOAD_ERR_NO_FILE (error 4)
            // Di sini Anda bisa menambahkan pesan error umum jika ingin
            $msg .= "Gagal mengunggah file {$fileInputName}. Harap pastikan file dipilih dengan benar. ";
        }
        return $fileNameToDb;
    }

    // Panggil fungsi upload untuk foto KTP dan SIM
    $fotoktp = handleUpload('fotoktp', $target_dir);
    $fotosim = handleUpload('fotosim', $target_dir);

    // Periksa apakah username sudah digunakan
    $user_check_stmt = $db->prepare("SELECT * FROM akun WHERE username=?");
    $user_check_stmt->bind_param("s", $username);
    $user_check_stmt->execute();
    $user_check_result = $user_check_stmt->get_result();
    $num_user = $user_check_result->num_rows;
    $user_check_stmt->close();

    if ($num_user > 0) {
        $msg = 'Username telah digunakan. Silakan pilih username lain.';
    } else {
        if ($password == $konfirmasi) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            if (empty($msg)) {
                $query = "INSERT INTO akun (nama, email, username, psw, alamat, telepon, fotoktp, fotosim)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_insert = $db->prepare($query);
                $stmt_insert->bind_param("ssssssss", $nama, $email, $username, $hashed_password, $alamat, $telepon, $fotoktp, $fotosim);
                $result = $stmt_insert->execute();
                $stmt_insert->close();

                if ($result) {
                    echo "<script>
                            alert('Registrasi berhasil!');
                            document.location.href = 'login.php';
                          </script>";
                } else {
                    $msg = 'Registrasi gagal. Terjadi kesalahan database.';
                }
            }
        } else {
            $msg = 'Konfirmasi Password Salah. Password tidak cocok.';
        }
    }

    // Tampilkan pesan jika ada error
    if (!empty($msg) && strpos($msg, 'Registrasi berhasil') === false) {
        echo "<script>alert('{$msg}');</script>";
    }
}
?>
