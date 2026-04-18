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
    die('All fields are required');
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try 
{
    $stmt = $pdo->prepare("INSERT INTO pending_users (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$username, $hashedPassword]);
    exit;

} catch (PDOException $e) 
{
    if ($e->getCode() == 23000) {
        die('This username is already taken or a request is already pending.');
    }

    error_log($e->getMessage());
    die('An error occurred while processing your request. Please try again later.');
}