<?php
session_start();
include 'connect_spencer.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die("Invalid form submission");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password_hash'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $user;
        }

        header("Location: ../index.html");
        exit;

    } else {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        $insert_stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $insert_stmt->bind_param("ss", $user, $hashed_pass);

        if ($insert_stmt->execute()) {
            $_SESSION['user_id'] = $insert_stmt->insert_id;
            $_SESSION['username'] = $user;
        }
        $insert_stmt->close();

        header("Location: ../index.html");
        exit;
    }
    $stmt->close();
}