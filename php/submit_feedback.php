<?php

$conn = new mysqli("localhost", "root", "", "team9062");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['rating']) || !isset($_POST['feedback'])) {
    die("Invalid form submission");
}

$rating = $_POST['rating'];
$message = $_POST['feedback'];

$sql = "INSERT INTO feedback (rating, message)
        VALUES ('$rating', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "Feedback submitted successfully, thank you!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

?>