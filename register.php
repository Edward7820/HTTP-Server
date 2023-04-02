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
        $query = 'INSERT INTO user ' . 
        '(username, password) ' . 
        "VALUES ($username, $password)";
        mysql_query($query, $conn);
        mysql_close($conn);
        
        $newPage = $_SERVER["SERVER_NAME"] . "homepage.php";
        header("Location:$newPage");
        exit();
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