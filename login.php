<?php
session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'mywebsite';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();
  if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    header("Location: dashboard.php");
    exit;
  } else {
    echo "Incorrect password.";
  }
} else {
  echo "User not found.";
}

$stmt->close();
$conn->close();
?>
