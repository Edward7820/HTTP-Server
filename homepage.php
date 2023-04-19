<?php
    include "utils.php";
    session_start();

    if (! isset($_SESSION['username'])){
        echo '<script>alert("Please login first!")</script>';
        $newPage = "Location: login.php";
        header($newPage);
        exit();
    }

    if ($_POST["logout"]){
        echo '<script>You have logged out.</script>';
        $username = $_SESSION['username'];
        $session_time = time() - $_SESSION["starttime"];
        session_destroy();

        # get total session time
        # $conn = mysqli_connect('localhost','edward','how910530');
        # if (!$conn){
        #     die('Could not connect: ' . mysqli_connect_error());
        # }
        # $prev_session_time = get_prev_session_time($conn, $username);
        # $total_session_time = $session_time;            
        # if ($prev_session_time != NULL) {
        #     $total_session_time = $prev_session_time + $total_session_time;
        # }

        $acct_res = radius_acct_open();
        radius_add_server($acct_res,'localhost',1813,'testing123',3,3);
        radius_create_request($acct_res, RADIUS_ACCOUNTING_REQUEST);
        radius_put_attr($acct_res,RADIUS_USER_NAME,$username);
        radius_put_int($acct_res,RADIUS_ACCT_STATUS_TYPE,RADIUS_STOP);
        radius_put_int($acct_res,RADIUS_ACCT_SESSION_TIME, $session_time);
        radius_send_request($acct_res);

        $newPage = "Location: login.php";
        header($newPage);
        exit();
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh" content="10">

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
                $username = $_SESSION['username'];
                echo '<h3>' . $username . ' Hi! </h3>';

                # query the database for session time
                $conn = mysqli_connect('localhost','edward','how910530');
                if (!$conn){
                    die('Could not connect: ' . mysqli_connect_error());
                }
                $prev_session_time = get_prev_session_time($conn, $username);
                $current_session_time = time() - $_SESSION["starttime"];
                
                if ($prev_session_time != NULL) {
                    $total_session_time = $prev_session_time + $current_session_time;
                    echo 'Your total session time is ' . 
                    "$total_session_time" . '<br />';
                }
            }
        ?>
        <form action="<?php $_PHP_SELF ?>" method="POST">
            <button name="logout" value="true">logout</button>
        </form>

    </body>
</html>