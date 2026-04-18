<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
    die('Access denied.');
}

$id = (int)($_POST['id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$displayName = trim($_POST['display_name'] ?? '');
$title = trim($_POST['title'] ?? '');
$email = trim($_POST['email'] ?? '');
$profilePicture = trim($_POST['profile_picture'] ?? '');
$description = trim($_POST['description'] ?? '');
$password = $_POST['password'] ?? '';
$isAdmin = isset($_POST['is_admin']) ? (int)$_POST['is_admin'] : 0;
$active = isset($_POST['active']) ? (int)$_POST['active'] : 1;

if ($id <= 0 || $username === '' || $displayName === '') {
    die('Invalid user update.');
}

if ($profilePicture === '') {
    $profilePicture = 'images/empty_icon.webp';
}

if ($title === '') {
    $title = 'Team Member';
}

if ($isAdmin !== 1) {
    $isAdmin = 0;
}

if ($active !== 1) {
    $active = 0;
}

try {
    if ($password !== '') {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            UPDATE users
            SET username = ?, display_name = ?, title = ?, email = ?, profile_picture = ?, description = ?, password_hash = ?, is_admin = ?, active = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $username,
            $displayName,
            $title,
            $email,
            $profilePicture,
            $description,
            $passwordHash,
            $isAdmin,
            $active,
            $id
        ]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE users
            SET username = ?, display_name = ?, title = ?, email = ?, profile_picture = ?, description = ?, is_admin = ?, active = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $username,
            $displayName,
            $title,
            $email,
            $profilePicture,
            $description,
            $isAdmin,
            $active,
            $id
        ]);
    }

    header("Location: admin.php");
    exit;
} catch (PDOException $e) {
    die('Update failed: ' . $e->getMessage());
}
?>