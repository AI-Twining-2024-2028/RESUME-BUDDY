<?php
session_start();

$conn = new mysqli("localhost", "root", "", "login_resume_buddy_db");

if ($conn->connect_error) {
    die("DB error");
}

$user = trim($_POST['username']);
$pass = trim($_POST['password']);

$sql = "SELECT * FROM users 
WHERE username='$user' AND password='$pass'";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {

    $_SESSION['user'] = $user;

    echo "success";
} else {

    echo "fail";
}
