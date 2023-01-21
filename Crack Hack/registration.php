<?php
if(isset($_POST["submit"])){
    //get form data and sanitize
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $gender = filter_var($_POST["gender"], FILTER_SANITIZE_STRING);
  
    //validate form data
    if(empty($name) || empty($password) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo '<script>alert("Please fill in all the required fields")</script>';
        exit();
    }
    elseif (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long')</script>";
    }
    elseif (!preg_match("#[A-Z]+#", $password)) {
        echo "<script>alert('Password must contain at least one uppercase letter')</script>";
    }
    elseif (!preg_match("#[a-z]+#", $password)) {
        echo "<script>alert('Password must contain at least one lowercase letter')</script>";
    }
    elseif (!preg_match("#[0-9]+#", $password)) {
        echo "<script>alert('Password must contain at least one number')</script>";
    }
    elseif (!preg_match('@[^\w]@', $password)) {
        echo "<script>alert('Password must contain at least one special character')</script>";
    }
    else{
  
        //check if user already exists

        $conn = mysqli_connect("localhost","root","","users");
    
        //check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT * FROM registered_user WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('A user with this username or email already exists')</script>";
            header("Location: login.php");
            exit();
        }
    
        //hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
        //insert data into users table
        $sql = "INSERT INTO registered_user (Name, Password, Email, Gender) VALUES ('$name', '$hashed_password', '$email', '$gender')";
    
        if (mysqli_query($conn, $sql)) {
            
            echo '<script>alert("New record created successfully")</script>';
            header("Location: login.php");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <title>Registration</title>
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
            margin: 90px auto;
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
        input[type="text"], input[type="password"], input[type="email"] {
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

        /* gender field styling */
        select#gender {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 18px;
        }
  
        /* submit button hover effect */
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
    <h1 id="heading">Sign Up</h1>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name"><br>
        <label for="gender">Gender:</label>
        <select id="gender" name="gender">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <i class="fa fa-eye" onclick="showPassword()" id='toggle'></i><br>
        <a href="login.php">Already have account? Login</a>
        <input type="submit" name="submit" value="Register">
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
