
<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    // Redirect the user to the login page
    header("Location: login.php");
    exit();
}

// Access the session variable
$name = $_SESSION["name"];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
    <style>
        body {
          background-image: url('sea-edge-79ab30e2.png');
          background-repeat: no-repeat;
          background-attachment: fixed;
          background-size: 100% 100%;
        }
        .logout-button {
            background-color: #ff0000; /* red */
            color: #fff; /* white */
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
        }
        .top-right-btns{
          position: absolute;
          top: 20px;
          right: 20px;
          display: flex;
          flex-direction: column;
          margin-bottom: 10px;

         }  
        .post-btn {
          background-color: blue;
          color: #fff;
          padding: 10px 20px;
          border-radius: 5px;
          text-align: center;
          margin-bottom: 10px;
          text-decoration: none;
          transition: background-color 0.2s ease;
      }

    .post-btn:hover, .logout-button:hover  {
        background-color: #0077be;
    }
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
        }
        .remove-btn {
            background-color: #d9534f;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            text-align: center;
            margin-left: 10px;
            cursor: pointer;
        }
        .post-btn {
        background-color: blue;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }


    .post-btn:hover {
        background-color: #0077be;
    }
    .post-box {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 2px 2px 2px #ccc;
        margin-bottom: 20px;
    }

    .post-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .post-user-name {
        font-weight: bold;
        margin-right: 10px;
    }

    .post-content {
        margin-bottom: 10px;
    }

    .post-footer {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .post-location, .post-date {
        margin-right: 10px;
    }

    .like-section {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .like-btn {
        background-color: #3b5998;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }

    .like-btn:hover {
        background-color: #444;
    }

    .like-count {
        margin-left: 10px;
    }

    .comments-section {
        margin-top: 20px;
    }

    .comments-section h4 {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .comment-box {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }

    .comment-user {
        font-weight: bold;
    }
    textarea  {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea:focus {
        border: 3px solid #555;
        }
        textarea{
          resize: none;
          height: 100px;
        }
        .admin-reply-section{
            background-color: white; padding: 10px; border-radius: 5px;
        }

    </style>
</head>
<body>
    <h1>Welcome, <?php echo $name; ?>!</h1>
    <div class="top-right-btns">
    <a href="post.php" class="post-btn">Create Post</a>
    <form action="logout.php" method="post">
        <input type="submit" value="Logout" name="logout" class="logout-button">
    </form>
    </div>
    <?php
    if(isset($_SESSION['post_success'])) {
      echo "<div class='success-message'>Post added successfully! <span class='remove-btn' onclick='this.parentNode.style.display = \"none\"'>x</span></div>";
      unset($_SESSION['post_success']);
    }
    ?>
    <div class="posts-container">
    <?php
    $conn = mysqli_connect("localhost","root","","users");
    
    //check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //Fetching data from the database
    $sql = "SELECT * FROM posts";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        $post_id = $row['post_id'];
        $user_id = $row['user_id'];
        $adminReply=$row['admin_reply'];
        $sql2 = "SELECT Name, Email FROM registered_user WHERE Id='$user_id'";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        $user_name = $row2['Name'];
        $user_email = $row2['Email'];
    ?>
   
  <div class="post-box">
    <div class="post-header">
        <p class="post-user-name"> <?php echo $user_name; ?></p>
        <p class="post-user-email"> <?php echo $user_email; ?></p>
    </div>
      <div class="post-content">
          <p> <?php echo $row['post_text']; ?></p>
      </div>
      <div class="admin-reply-section"
        <?php 
        if($adminReply == 'submitted to news paper'){
        echo "style='background-color: yellow;'";
        }
        elseif ($adminReply == 'resolved'){
            echo "style='background-color: #00FF00;'";
        }
        elseif ($adminReply == 'no action taken'){
            echo "style='background-color: red;'";
        }
        elseif ($adminReply == ''){
            echo "style= 'display:none;'";
        }
        ?>
       >
            <h4>Admin Reply:</h4>
            <p><?php echo $adminReply; ?></p>
    </div>





      <div class="post-footer">
          <p class="post-location"> <?php echo $row['location']; ?></p>
          <p class="post-date"> <?php echo $row['date_time']; ?></p>
      </div>
      <div class="like-section">
          <button class="like-btn" data-post-id="<?php echo $post_id; ?>">Like</button>
          <p class="like-count" data-post-id="<?php echo $post_id; ?>"> <?php echo $row['like_count']; ?></p>
      </div>
      <div class="comments-section">
          <h4>Comments</h4>
          <?php
              $comments_query = "SELECT * FROM comments WHERE post_id = '$post_id'";
              $comments_result = mysqli_query($conn, $comments_query);
              while($comments = mysqli_fetch_assoc($comments_result)) {
                  echo "<div class='comment-box'>";
                  echo "<p class='comment-user'>" . $comments['user_name'] . "</p>";
                  echo "<p class='comment-text'>" . $comments['comment_text'] . "</p>";
                  echo "</div>";
              }
          ?>
          <form method="post" action="add_comment.php">
              <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
              <textarea name="comment_text" placeholder="Write your comment here"></textarea>
              <input type="submit" value="Comment" class='like-btn' >
          </form>
      </div>
    </div>
    <?php } ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
      // Add an event listener to the like button
      $('.like-btn').on('click', function() {
    var postId = $(this).data('post-id');
    $.ajax({
        url: 'update_like_count.php',
        type: 'post',
        data: { post_id: postId },
        success: function(response) {
            // Update the like count on the homepage
            $('.like-count[data-post-id=' + postId + ']').text(response);
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
});
    </script>


</body>
</html>