<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$pdo->prepare('DELETE FROM rentcar WHERE id = ?')->execute([$_GET['id']]);
header("Location: read.php");
exit;
?>