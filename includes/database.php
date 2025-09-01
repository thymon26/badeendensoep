<?php
// Database configuratie
$host = 'localhost';
$db   = 'c51543Badeendensoep';
$user = 'c51543Thymen';
$pass = 'Thym3n2oo8!';
$charset = 'utf8mb4';

// DSN (Data Source Name) aanmaken
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opties voor PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Database verbinding mislukt: ' . $e->getMessage());
}
?>