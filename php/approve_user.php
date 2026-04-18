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

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT * FROM pending_users WHERE id = ? LIMIT 1");
    $stmt->execute([$pendingId]);
    $pending = $stmt->fetch();

    if (!$pending) {
        throw new Exception('Pending user not found.');
    }

    $insert = $pdo->prepare("
        INSERT INTO users (
            username,
            password_hash,
            display_name,
            title,
            description,
            profile_picture,
            email,
            is_admin,
            is_approved,
            active
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 0, 1, 1)
    ");

    $insert->execute([
        $pending['username'],
        $pending['password_hash'],
        $pending['display_name'],
        $pending['title'],
        $pending['description'],
        $pending['profile_picture'],
        $pending['email']
    ]);

    $delete = $pdo->prepare("DELETE FROM pending_users WHERE id = ?");
    $delete->execute([$pendingId]);

    $pdo->commit();

    header("Location: admin.php");
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die('Approval failed: ' . $e->getMessage());
}
?>