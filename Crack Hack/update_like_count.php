<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = mysqli_connect($host, $username, $password, $dbname);

if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$postId = $_POST['post_id'];

// Update the like count in the database
$sql = "UPDATE posts SET like_count = like_count + 1 WHERE post_id = '$postId'";
$result = mysqli_query($conn, $sql);

if ($result) {
    // Get the updated like count
    $sql = "SELECT like_count FROM posts WHERE post_id = '$postId'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $likeCount = $row['like_count'];

    // Return the updated like count
    echo $likeCount;
} else {
    echo "Error updating like count: " . mysqli_error($conn);
}
