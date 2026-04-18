<?php
session_start();
require_once 'connect_spencer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

$username = trim($_POST['username']);
$password = $_POST['password'];

if ($username === '' || $password === '') {
    die('All fields are required');
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO pending_users (username, password_hash, request_date) VALUES (?, ?, NOW())");

try {
    $stmt->execute([$username, $hashedPassword]);
    header("Location: ../index.html?status=pending");
    exit;
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        die('Username already exists or request is pending.');
    }
    die('Error processing request.');
}