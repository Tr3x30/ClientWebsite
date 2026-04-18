<?php
require_once 'connect.php';

$stmt = $pdo->prepare("
    SELECT id, username, display_name, title, description, profile_picture, email
    FROM users
    WHERE active = 1 AND is_approved = 1
    ORDER BY is_admin DESC, display_name ASC
");
$stmt->execute();

header('Content-Type: application/json');
echo json_encode($stmt->fetchAll());
?>