<?php 
    if ($_POST["username"] and $_POST["password"]){
        session_start();

        $username = $_POST["username"];
        $password = $_POST["password"];

        #$conn = mysqli_connect('localhost','edward','how910530');
        #if (!$conn){
        #    die('Could not connect: ' . mysqli_connect_error());
        #}
        #mysqli_select_db($conn, 'radius');

        # check whether the password is correct
        $auth_res = radius_auth_open();
        $acct_res = radius_acct_open();
        radius_add_server($auth_res,'localhost',1812,'testing123',3,3);
        radius_add_server($acct_res,'localhost',1813,'testing123',3,3);
        radius_create_request($auth_res, RADIUS_ACCESS_REQUEST);
        radius_create_request($acct_res, RADIUS_ACCOUNTING_REQUEST);
        radius_put_attr($auth_res, RADIUS_USER_NAME, $username);
        radius_put_attr($auth_res, RADIUS_USER_PASSWORD, $password);
        radius_put_attr($acct_res, RADIUS_USER_NAME, $username);
        radius_put_attr($acct_res, RADIUS_ACCT_STATUS_TYPE, RADIUS_START);

        if (radius_send_request($auth_res) == RADIUS_ACCESS_ACCEPT){
            radius_send_request($acct_res);
            $_SESSION["username"] = $username;
            echo '<script>alert("You have logged in successfully")</script>';
            $newPage = "Location: homepage.php";
            header($newPage);
            exit();
        }
        else{
            echo '<script>alert("You can not log in.")</script>';
            exit();
        }
    }
?>
<html>
    <head>
        <meta charset="utf-8">

        <title>Login Page</title>

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
        <h1>Please type your username and password here to login</h1>

        <form action="<?php $_PHP_SELF ?>" method="POST">
            Username: <input type = "text" name = "username" />
            Password: <input type = "text" name = "password" />
            <input type = "submit" />
        </form>

        <div>
            <p>If you haven't regiter an account, please click the link below
            <a href="register.php">Register link</a>.
            </p>
        </div>

    </body>
</html>