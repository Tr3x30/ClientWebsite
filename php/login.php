<?php
session_start();
require_once 'connect.php';

function sendResponse($status, $message, $redirect = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'redirect' => $redirect
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse('error', 'Invalid request method');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    sendResponse('error', 'Username and password are required');
}

$stmt = $pdo->prepare("SELECT id, username, password_hash, display_name, is_admin, active FROM users WHERE username = ? LIMIT 1");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user)
{
    sendResponse('error', 'Account doesn\'t exist');
}

if (!password_verify($password, $user['password_hash'])) {
    sendResponse('error', 'Invalid username or password.');
}

if ((int)$user['active'] !== 1) {
    sendResponse('error', 'This account is inactive.');
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['display_name'] = $user['display_name'];
$_SESSION['is_admin'] = (int)$user['is_admin'];

$updateLogin = $pdo->prepare("UPDATE users SET updated_at = NOW() WHERE id = ?");
$updateLogin->execute([$user['id']]);

$redirectUrl = ((int)$user['is_admin'] === 1) ? "php/admin.php" : "Member.php";

sendResponse('success', 'Logged in successfully', $redirectUrl);
?>