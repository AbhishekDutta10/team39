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

$sql = "SELECT p.post_id, u.Name, u.Email, p.post_text, p.location, p.date_time, p.like_count, p.admin_reply FROM posts p
        JOIN registered_user u ON p.user_id = u.Id
        ORDER BY p.like_count DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <style>
    .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }
    .styled-table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
    }
    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
    }
    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
    }
    .admin-reply-form {
        display: flex;
        align-items: center;
        }

    .admin-reply-select {
        padding: 10px;
        margin-right: 10px;
        font-size: 16px;
        border-radius: 4px;
        border: none;
    }

    .admin-reply-btn {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        font-size: 16px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }

    .admin-reply-btn:hover {
        background-color: #3e8e41;
    }
</style>
</head>
<body>
    <div class="admin-container">
        <table class='styled-table'>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Post Text</th>
                <th>Location</th>
                <th>Date/Time</th>
                <th>Like Count</th>
                <th>Admin Reply</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['Name']; ?></td>
                <td><?php echo $row['Email']; ?></td>
                <td><?php echo $row['post_text']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td><?php echo $row['date_time']; ?></td>
                <td><?php echo $row['like_count']; ?></td>
                <td>
                    <form method="post" action="admin_reply.php" class="admin-reply-form">
                        <input type="hidden" name="post_id" value="<?php echo $row['post_id']; ?>">
                        <select name="admin_reply" class="admin-reply-select">
                            <option value=""></option>
                            <option value="submitted to news paper">Submitted to news paper</option>
                            <option value="resolved">Resolved</option>
                            <option value="no action taken">No action taken</option>
                        </select>
                        <input type="submit" value="Reply" class="admin-reply-btn">
                    </form>
                    <?php echo $row['admin_reply']; ?>
                </td>
            </tr>
        <?php } ?>
        </table>
    </div>

</body>
</html>
