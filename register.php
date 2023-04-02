<?php
    if ($_POST["username"] and $_POST["password"]){
        session_start();
        $_SESSION["username"] = $_POST["username"];

        $username = $_POST["username"];
        $password = $_POST["password"];

        $conn = mysql_connect();
        if (!$conn){
            die('Could not connect: ' . mysql_error());
        }
        mysql_select_db('accounts', $conn);

        # check that whether the username has already been registered
        $query = 'SELECT * FROM user WHERE username = ' . "$username";
        $query_ret = mysql_query($query, $conn);
        $user = mysql_fetch_array($query_ret, MYSQL_ASSOC);
        if ($user){
            # show error message
            echo '<script>alert("The username has been used. Please choose another username!")</script>';
            mysql_close($conn);
            exit();
        }
        else{
            # add a user account to the database
            $query = 'INSERT INTO user ' . 
            '(username, password) ' . 
            "VALUES ($username, $password)";
            mysql_query($query, $conn);
            mysql_close($conn);

            # redirect to the homepage
            echo '<script>alert("Registered successfully!")</script>';
            $newPage = $_SERVER["SERVER_NAME"] . "homepage.php";
            header("Location:$newPage");
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