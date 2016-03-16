<?php

/*
 * write_mysql_log($message, $db)
 *
 * Parameters:
 * $massage: message to be logged
 * $db: Object that represents the connection to the MySQL server
 *
 * */

function write_mysql_log($dbconn, $log_date, $message = null, $user_email,$session_id,$referral) {
    // Check IP address
    if (($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
        $remote_addr = "REMOTE_ADDR_UNKNOWN";
    }

    // Check IP address
    if (($request_uri = $_SERVER['REQUEST_URI']) == '') {
        $request_uri = "REQUEST_URI_UNKNOWN";
    }

    // Escape values
    // Construct query
    $sql = "INSERT INTO log(log_date,remote_addr,referral,request_uri,message,user_email,session_id) VALUES (:log_date,:remote_addr,:referral,:request_uri,:message,:user_email,:session_id)";
    try {
        $query = $dbconn->prepare($sql);
        $query->execute(array(':log_date' => $log_date, ':remote_addr' => $remote_addr, 'referral'=>$referral, ':request_uri' => $request_uri, ':message' => $message, ':user_email' => $user_email,':session_id'=>$session_id));
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>