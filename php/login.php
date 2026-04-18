<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    die('Username and password are required');
}

$stmt = $pdo->prepare("
    SELECT id, username, password_hash, display_name, is_admin, active
    FROM users
    WHERE username = ?
    LIMIT 1
");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user) {
    die('Invalid username/password or account not yet approved.');
}

if ((int)$user['active'] !== 1) {
    die('This account is inactive.');
}

if (!password_verify($password, $user['password_hash'])) {
    die('Invalid username/password or account not yet approved.');
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['display_name'] = $user['display_name'];
$_SESSION['is_admin'] = (int)$user['is_admin'];

$updateLogin = $pdo->prepare("UPDATE users SET updated_at = NOW() WHERE id = ?");
$updateLogin->execute([$user['id']]);

if ((int)$user['is_admin'] === 1) {
    header("Location: admin.php");
    exit;
}

header("Location: ../Member.php");
exit;
?>