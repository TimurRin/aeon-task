<?php

$config = json_decode(file_get_contents('config.json'), true);

$host = $config['host'];
$db = $config['db'];
$user = $config['user'];
$pass = $config['pass'];

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);
?>