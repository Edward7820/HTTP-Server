<?php
    session_start();

    if (! isset($_SESSION['username'])){
        echo '<script>alert("Please login first!")</script>';
        $newPage = "Location: login.php";
        header($newPage);
        exit();
    }

    if ($_POST["logout"]){
        echo '<script>You have logged out.</script>';
        session_destroy();

        $acct_res = radius_acct_open();
        radius_add_server($acct_res,'localhost',1813,'testing123',3,3);
        radius_create_request($acct_res, RADIUS_ACCOUNTING_REQUEST);
        radius_put_attr($acct_res,RADIUS_USER_NAME,$_SESSION['username']);
        radius_put_attr($acct_res,RADIUS_ACCT_STATUS_TYPE,RADIUS_STOP);
        radius_send_request($acct_res);

        $newPage = "Location: login.php";
        header($newPage);
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
        <?php 
            if (isset($_SESSION['username'])){
                echo '<h3>' . $_SESSION['username'] . ' Hi! </h3>';
            }
        ?>
        <form action="<?php $_PHP_SELF ?>" method="POST">
            <button name="logout" value="true">logout</button>
        </form>

    </body>
</html>