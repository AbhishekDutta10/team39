<?php
session_start();



// Connect to the database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "users";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Check if the form is submitted
if(isset($_POST['post-text']) && isset($_POST['location']) && isset($_POST['date-time'])) {
    $postText = $_POST['post-text'];
    $location = $_POST['location'];
    $dateTime = $_POST['date-time'];
    $userid = $_SESSION['user_id'];

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO posts (post_text, location, date_time, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $postText, $location, $dateTime, $userid);

    // Execute the statement and check if it's successful
    if ($stmt->execute()) {
        $_SESSION['post_success'] = true;
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Post</title>
    <style>
        #post-form {
        border: 1px solid black;
        padding: 10px;
        }

        input[type="text"], textarea  {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type=text]:focus, textarea:focus {
        border: 3px solid #555;
        }
        textarea{
          resize: none;
          height: 100px;
        }
        .container {
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        width: 100%;
      }
      input[type="file"] {
        position: absolute;
        z-index: -1;
        top: 10px;
        left: 8px;
        font-size: 17px;
        color: #b8b8b8;
      }
      .button-wrap {
        position: relative;
      }
      .button {
        display: inline-block;
        padding: 12px 18px;
        cursor: pointer;
        border-radius: 5px;
        background-color: #8ebf42;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
      }
    
        #post-button {
            width: 100%;
            background-color: #ff69b4;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
        }

        #post-button:hover {
            background-color: #ff1493;
        }

        
    </style>
</head>
<body>

    <form id="post-form" action="post.php" method="post">
      <textarea id="post-text" name="post-text" placeholder="Share Your Problem..."></textarea>
      <input type="text" id="location" name="location" placeholder="Enter location in which the incident happened...">
      <input type="hidden" id="date-time" name="date-time" value="<?php echo date('Y-m-d H:i:s');?>">
      <button type="submit" id="post-button">Post</button>
  </form>
<div class="container">
    <div class="button-wrap">
    <label class="button" for="upload">Upload File</label>
    <input id="upload" type="file">
    </div>
</div>
<div id="uploaded-image"></div>
</body>
</html>