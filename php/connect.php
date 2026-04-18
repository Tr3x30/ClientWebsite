<?php
/**
 * Main DB connection file
 * Use this everywhere.
 */

$host = 'localhost';
$db = 'gofft1_db';
$user = 'gofft1_local';
$pass = 'j<@h!V}O';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    /* keep backward compatibility with submit.php */
    $dbh = $pdo;
} catch (PDOException $e) {
    die("ERROR: Couldn't connect. " . $e->getMessage());
}
?>