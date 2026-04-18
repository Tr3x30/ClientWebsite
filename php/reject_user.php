<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
    die('Access denied.');
}

$pendingId = (int)($_POST['pending_id'] ?? 0);

if ($pendingId <= 0) {
    die('Invalid pending user ID.');
}

$stmt = $pdo->prepare("DELETE FROM pending_users WHERE id = ?");
$stmt->execute([$pendingId]);

header("Location: admin.php");
exit;
?>