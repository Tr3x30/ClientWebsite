<?php

$conn = new mysqli("localhost", "root", "team9062");

if ($conn->connect_error) {
    die("Connection failed". $conn->connect_error);
}

$rating = $_POST['rating'];
$message = $_POST['feedback'];

$sql =  "INSERT INTO feedback (rating, message)
         VALUES ('$rating', '$message')";

if ($conn->query($sql) === TRUE) {
    echo "Feedback sumitted successfully, thank you!";
}
else {
    echo "Error " . $conn->error;
}

$conn->close();

?>