<?php
    session_start();

    if (isset($_SESSION['username'])){
        echo 'welcome <br />';
    } else{
        echo '<script>alert("Please login first!")</script>';
        $newPage = $_SERVER["SERVER_NAME"] . "login.php";
        header("Location:$newPage");
        exit();
    }
?>
<html>
    <head>
        <meta charset="utf-8">

        <title>Homepage</title>

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
        <h1>Homepage</h1>

    </body>
</html>