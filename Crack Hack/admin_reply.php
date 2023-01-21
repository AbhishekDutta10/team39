<?php
session_start();
if(!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
    header("Location: login.php");
    exit;
  }

$host = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$postId = $_POST['post_id'];
$adminReply = $_POST['admin_reply'];

// Update the admin reply in the database
$sql = "UPDATE posts SET admin_reply = '$adminReply' WHERE post_id = '$postId'";
$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: admin.php");
} else {
    echo "Error updating admin reply: " . mysqli_error($conn);
}
?>