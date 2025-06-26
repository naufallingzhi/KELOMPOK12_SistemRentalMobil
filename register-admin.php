<?php
require 'config.php';

if (isset($_POST['regis'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirm'];
    
    // Check if username exists
    $stmt = $db->prepare("SELECT id FROM admin WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        echo "<script>alert('Username telah digunakan');</script>";
    } elseif ($password !== $konfirmasi) {
        echo "<script>alert('Konfirmasi Password Salah');</script>";
    } else {
        // Insert new admin
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO admin (nama, email, username, psw) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $email, $username, $hashed_password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registrasi berhasil'); location.href='login_admin.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="container regis">
        <div class="judul">
            <h2>Registrasi Admin</h2>
        </div>
        <div class="form">
            <form method="post">
                <label for="nama">Nama</label>
                <input type="text" name="nama" class="input" placeholder="Masukkan nama" required>
                
                <label for="email">Email</label>
                <input type="email" name="email" class="input" placeholder="Masukkan email" required>
                
                <label for="username">Username</label>
                <input type="text" name="username" class="input" placeholder="Masukkan username" required>
                
                <label for="password">Password</label>
                <input type="password" name="password" class="input" placeholder="Password" required>
                
                <label for="konfirmasi">Konfirmasi Password</label>
                <input type="password" name="konfirm" class="input" placeholder="Konfirmasi password" required>
                
                <input type="submit" name="regis" class="submit" value="Registrasi">
            </form>
            <p>Sudah punya akun? <a href="login_admin.php">Login</a></p>
        </div>
    </div>
</body>
</html>