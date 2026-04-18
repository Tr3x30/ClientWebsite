<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    die('All fields are required');
}

$displayName = $username;
$email = filter_var($username, FILTER_VALIDATE_EMAIL) ? $username : null;
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $checkApproved = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $checkApproved->execute([$username]);

    if ($checkApproved->fetch()) {
        die('That username already exists.');
    }

    $checkPending = $pdo->prepare("SELECT id FROM pending_users WHERE username = ?");
    $checkPending->execute([$username]);

    if ($checkPending->fetch()) {
        die('That request is already pending.');
    }

    $stmt = $pdo->prepare("
        INSERT INTO pending_users (
            username,
            password_hash,
            display_name,
            title,
            description,
            profile_picture,
            email,
            request_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([
        $username,
        $hashedPassword,
        $displayName,
        'Pending Member',
        '',
        'images/empty_icon.webp',
        $email
    ]);

    header("Location: ../index.html?status=pending");
    exit;
} catch (PDOException $e) {
    die('Error processing request.');
}
?>