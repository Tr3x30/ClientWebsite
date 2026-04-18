<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
    die('Access denied.');
}

$id = (int)($_POST['id'] ?? 0);

if ($id === (int)$_SESSION['user_id']) {
    die('Error: You cannot delete your own admin account.');
}

if ($id <= 0) {
    die('Invalid user ID.');
}

try {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: admin.php");
    exit;
} catch (PDOException $e) {
    die('Delete failed: ' . $e->getMessage());
}
?>