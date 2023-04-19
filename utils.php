<?php
    function get_prev_session_time($conn, $username){
        mysqli_select_db($conn, 'radius');
        $query = 'SELECT acctsessiontime FROM radacct WHERE username = ' . "'$username'";
        $query_ret = mysqli_query($conn, $query);
        error_log("[Info] mysql query: " . $query);
        $prev_data = mysqli_fetch_array($query_ret, MYSQLI_ASSOC);
        if ($prev_data) {
            $prev_session_time = (int) $prev_data['acctsessiontime'];
            return $prev_session_time;
        }
        else{
            return NULL;
        }
    }
?>