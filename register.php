<?php
$conn = new mysqli("localhost", "root", "", "login_resume_buddy_db");

$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];

$check = "SELECT * FROM users 
WHERE username='$user' OR email='$email'";

$result = $conn->query($check);

if ($result->num_rows > 0) {
    echo "exists";
} else {
    $sql = "INSERT INTO users(username,email,password)
VALUES('$user','$email','$pass')";
    echo ($conn->query($sql)) ? "success" : "error";
}
