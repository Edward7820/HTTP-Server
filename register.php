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
        mysqli_select_db($conn, 'radius');

        # check that whether the username has already been registered
        $query = 'SELECT * FROM radcheck WHERE username = ' . "'$username'";
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
            $query = 'INSERT INTO radcheck (username,attribute,op,value) ' .
            "VALUES ('$username', 'Cleartext-Password',':=','$password')";
            mysqli_query($conn, $query);
            error_log("[Info] mysql query: " . $query);

            $query = 'INSERT INTO radcheck (username,attribute,op,value) ' .
            "VALUES ('$username', 'Max-Daily-Session',':=','300')";
            mysqli_query($conn, $query);
            error_log("[Info] mysql query: " . $query);

            $acct_res = radius_acct_open();
            radius_add_server($acct_res,'localhost',1813,'testing123',3,3);
            radius_create_request($acct_res, RADIUS_ACCOUNTING_REQUEST);
            radius_put_attr($acct_res,RADIUS_USER_NAME,$username);
            radius_put_int($acct_res, RADIUS_ACCT_STATUS_TYPE, RADIUS_START);
            radius_put_int($acct_res, RADIUS_ACCT_SESSION_TIME, 0);
            radius_send_request($acct_res);

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