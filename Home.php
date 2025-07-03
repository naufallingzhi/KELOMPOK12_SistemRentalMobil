<?php
session_start();
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Mobil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .vehicles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .car-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
            padding: 1.5rem;
            text-align: center;
            transition: transform .2s;
        }

        .car-card:hover {
            transform: translateY(-5px);
        }

        .car-card a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .car-card img {
            height: 15rem;
            width: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .car-card h3 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .car-card .price {
            font-size: 1.6rem;
            margin-bottom: 1rem;
            font-weight: bold;
        }

        .car-card p {
            font-size: 1.4rem;
            line-height: 1.8;
        }

        @media (max-width: 768px) {
            .vehicles-grid {
                grid-template-columns: 1fr;
                padding: 1rem;
            }
        }

        .no-cars {
            text-align: center;
            font-size: 1.5rem;
            color: #666;
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>
    
<header class="header">
    <div id="menu-btn" class="fas fa-bars"></div>
    <a href="#" class="logo"><span>RENT</span>CARS</a>

    <div id="login-btn">
        <?php if (isset($_SESSION['Login']) && $_SESSION['Login'] === true): ?>
            <a href="logout.php"><button class="btn">Logout</button></a>
        <?php else: ?>
            <a href="login.php"><button class="btn">Login</button></a>
        <?php endif; ?>
        <i class="far fa-user"></i>
    </div>
</header> 

<section class="home" id="home"></section>

<section class="vehicles" id="vehicles">
    <h1 class="heading">Kendaraan <span>Tersedia</span></h1>

    <div class="vehicles-grid">
        <?php 
        $result = mysqli_query($db, "SELECT * FROM car WHERE status = 'tersedia'");
        
        if (mysqli_num_rows($result) > 0) {
            while ($car = mysqli_fetch_array($result)) {
                // Bersihkan harga untuk URL
                $clean_price = str_replace(['Rp.', ',', ' '], '', $car['harga']);
                $numeric_price = floatval($clean_price);
                
                // Format harga untuk tampilan
                $display_price = number_format($numeric_price, 0, ',', '.');
                
                // URL untuk pemesanan
                $order_url = "create_user.php?car_id={$car['id']}&car_name=" . 
                           urlencode($car['name']) . "&car_price=" . urlencode($numeric_price);
        ?>
        <div class="car-card">
            <a href="<?= $order_url ?>">
                <img src="image/<?= $car['foto'] ?>" alt="<?= $car['name'] ?>">
                <div class="content">
                    <h3><?= $car['name'] ?></h3>
                    <div class="price">Rp <?= $display_price ?> <small>/ 24 jam</small></div>
                    <p><i class="fas fa-circle"></i> <?= $car['transmisi'] ?></p>
                </div>
            </a>
        </div>
        <?php
            }
        } else {
            echo '<div class="no-cars">Tidak ada mobil tersedia saat ini.</div>';
        }
        ?>
    </div>
</section>

<script src="js/script.js"></script>
</body>
</html>