<?php
session_start();
require 'config.php';

if (isset($_POST['Login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $db->prepare("SELECT * FROM akun WHERE username=? OR email=?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row && password_verify($password, $row['psw'])) {
        $_SESSION['Login'] = true;
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_nama'] = $row['nama'];
        
        $nama = htmlspecialchars($row['nama']);
        echo "<script>alert('Selamat Datang $nama'); location.href='Home.php';</script>";
    } else {
        echo "<script>alert('Username dan Password Salah');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/play.css">
    <title>Login</title>
</head>
<body>
    <div class="container login">
        <div class="form-login">
            <h3>LOGIN   </h3>
            <form method="post">
                <input type="text" name="username" placeholder="Email atau Username" class="input" required>
                <input type="password" name="password" placeholder="Password" class="input" required>
                <input type="submit" name="Login" value="Login" class="submit">
            </form>
            <p>Belum punya akun? <a href="register-tamu.php">Register</a></p>
        </div>
    </div>
</body>
</html>