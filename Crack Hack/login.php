<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        *{
            box-sizing: border-box;
            margin: 0px;
            padding: 0px;
        }
        body {
          background-image: url('ranger-4df6c1b6.png');
          background-repeat: no-repeat;
          background-attachment: fixed;
          background-size: 100% 100%;
        }
        /* form styling */
        form {
            width: 50%;
            margin: 100px auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: white;
        }

        a {
            text-decoration: none;
            color: #ff69b4;
            margin-top: 10px;
            display: block;
            text-align: right;
        }
        a:hover {
            text-decoration: underline;
            color: #ff1493;
        }


        #heading {
            text-align: center;
            font-size: 32px;
            color: #ff69b4;
            margin-bottom: 20px;
        }
  
        /* label styling */
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }
  
        /* input styling */
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
  
        /* submit button styling */
        input[type="submit"] {
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
        input[type="submit"]:hover {
            background-color: #ff1493;
        }

        /* Eye icon */
        .fa-eye {
            position: relative;
            margin-left: 93%;
            right: 4px;
            top: -37px;
            cursor: pointer;
            font-size: 18px;
            color: #ccc;
        }
        
    </style>
</head>
<body>
    <form action="" method="post">
        <h1 id="heading">Sign In</h1>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <i class="fa fa-eye" onclick="showPassword()" id='toggle'></i><br>
        <a href="registration.php">Create Account</a>
        <input type="submit" name="submit" value="Login">
    </form>

    <script>
        function showPassword() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                
            } else {
                passwordInput.type = "password";
            }
            document.getElementById("toggle").classList.toggle('fa-eye-slash');
        }
    </script>
</body>
</html>

<?php
session_start();

// Connect to the database
// $conn = mysqli_connect("host", "username", "password", "dbname");
$conn = mysqli_connect("localhost","root","","users");

// Check if the user has submitted the form
if (isset($_POST["submit"])) {

    // Get the email and password from the form
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Prepare a query to check if the user exists in the database
    $query = "SELECT * FROM registered_user WHERE Email='$email'";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if the query returned any results
    if (mysqli_num_rows($result) > 0) {

        // Get the user data from the query result
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user["Password"])) {
            // Start a session for the user
            $_SESSION["user_id"] = $user["Id"];
            $_SESSION["logged_in"] = true;
            $_SESSION["name"] = $user['Name'];
            $_SESSION["email"] = $user['Email'];
            $_SESSION["admin"] = false;

            if ($user['Email']==='admin@app.com'){
                $_SESSION["admin"] = true;

                header("Location: admin.php");
                exit();
            }
            else{

            // Redirect the user to the home page
            header("Location: index.php");
            exit();
            }
        } else {
            // Display an error message if the password is incorrect
            echo "<script>alert('Incorrect email or password')</script>";
        }
    } else {
        // Display an error message if the email is not found in the database
        echo "<script>alert('Incorrect email or password')</script>";
    }
}
?>
