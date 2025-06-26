<?php
session_start(); // Mulai sesi di awal file
    require 'config.php';

    if(isset($_POST['Login'])){
       
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM akun WHERE username='$username' OR email='$username'";
        $result = $db->query($query);
        $row = mysqli_fetch_array($result);

        if($row && password_verify($password,$row['psw'])){ // Pastikan $row ada sebelum mengaksesnya

            $_SESSION['Login'] = true;
            $_SESSION['user_id'] = $row['id']; // Simpan ID pengguna ke sesi
            $_SESSION['user_nama'] = $row['nama']; // Opsional: simpan nama pengguna juga

            echo"
                <script>
                    alert('Selamat Datang " . htmlspecialchars($row['nama']) . "'); // Menampilkan nama pengguna
                    document.location.href = 'index1.php';
                </script>
            ";
        }else{
            echo"
                <script>
                    alert('Username dan Password Salah');
                </script>
            ";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/play.css">
    <title>Login</title>
</head>
<body>
    <div class="container login">
        <div class="logo">
            <img src="img/auto.webp" alt="logo showroom" width="70%">
        </div>
        <div class="form-login">
            <h3>Login</h3>
            <form action="" method="post">
                <input type="text" name="username" placeholder="email atau username" class="input" required>
                <input type="password" name="password" placeholder="password" class="input" required>

                <input type="submit" name="Login" value="Login" class="submit"><br><br>
            </form>

            <p>Belum punya akun?
                <a href="register-tamu.php">Register</a>
            </p>
        </div>
    </div>
</body>
</html>
