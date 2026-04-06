<?php
session_start();
require_once 'connect_spencer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

if (!isset($_POST['username'], $_POST['password'])) {
    die('Invalid form submission');
}

$username = trim($_POST['username']);
$password = $_POST['password'];

if ($username === '' || $password === '') {
    die('Username and password are required');
}

$stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
$stmt->execute([$username]);
$row = $stmt->fetch();

if ($row) {
    if (password_verify($password, $row['password_hash'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;
    }

    header("Location: ../index.html");
    exit;
} else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insertStmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $insertStmt->execute([$username, $hashedPassword]);

    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['username'] = $username;

    header("Location: ../index.html");
    exit;
}