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

if ($row) 
{
    if ($row && password_verify($password, $row['password_hash'])) 
    {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;
        header("Location: ../index.html");
        exit;
    } 
    else 
    {
        die('Invalid username/password or account not yet approved.');
    }
}