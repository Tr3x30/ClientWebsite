<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    die('Unauthorized');
}

$user_id = $_SESSION['user_id'];
$display_name = $_POST['display_name'];
$email = $_POST['email'];
$title = $_POST['title'];
$description = $_POST['description'];
$new_password = $_POST['new_password'];

$sql = "UPDATE users SET display_name = ?, email = ?, title = ?, description = ? WHERE id = ?";
$params = [$display_name, $email, $title, $description, $user_id];

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

if (!empty($new_password)) {
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $pwStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $pwStmt->execute([$hash, $user_id]);
}

$_SESSION['display_name'] = $display_name;

header('Location: ../Member.php?success=1');
exit;