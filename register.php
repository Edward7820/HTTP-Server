<?php
    if ($_POST["username"] and $_POST["password"]){
        session_start();

        $username = $_POST["username"];
        $password = $_POST["password"];
        error_log("[Info] Register username: " . $username . "; password: " . $password);

        $conn = mysqli_connect('localhost','edward','how910530');
        if (!$conn){
            die('Could not connect: ' . mysqli_connect_error());
        }
        mysqli_select_db($conn, 'accounts');

        # check that whether the username has already been registered
        $query = 'SELECT * FROM user WHERE username = ' . "'$username'";
        $query_ret = mysqli_query($conn, $query);
        error_log("[Info] mysql query: " . $query);
        $user = mysqli_fetch_array($query_ret, MYSQLI_ASSOC);
        if ($user){
            # show error message
            echo '<script>alert("The username has been used. Please choose another username!")</script>';
            mysqli_close($conn);
            exit();
        }
        else{
            # add a user account to the database
            $query = 'INSERT INTO user ' . 
            '(username, password) ' . 
            "VALUES ('$username', '$password')";
            mysqli_query($conn, $query);
            error_log("[Info] mysql query: " . $query);
            mysqli_close($conn);

            # handle session
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["starttime"] = time();

            # redirect to the homepage
            echo '<script>alert("Registered successfully!")</script>';
            $newPage = "Location: homepage.php";
            header($newPage);
            exit();
        }        
    }
?>
<html>
    <head>
        <meta charset="utf-8">

        <title>Register Page</title>

        <style>
        html{
            font-family: sans-serif;
        }

        body {
            width: 900px;
            margin: 0 auto;
            background-color: #ccc;
        }

        </style>

    </head>

    <body>
        <h1>Please register your account here</h1>

        <form action="<?php $_PHP_SELF ?>" method="POST">
            Username: <input type = "text" name = "username" />
            Password: <input type = "text" name = "password" />
            <input type = "submit" />
        </form>  
    </body>
</html>