<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
$id = $_GET['id'];
$stmt = $pdo->prepare('DELETE  FROM car WHERE id = ?');
$stmt->execute([$_GET['id']]);
header("Location: tambah_mobil.php") ?>