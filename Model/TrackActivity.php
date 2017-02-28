<?php

/* Track user's activity*/
require_once 'DBController.php';
require_once  'WriteMysqlLog.php';
date_default_timezone_set('America/Chicago');

$log_date= date("Y-m-d");
$message=null;
$user_email=isset($_SESSION['email'])?$_SESSION['email']:null;
$objDBController = new DBController();
$dbconn=$objDBController->getConn();
$referral=null;
if(isset($_SERVER["HTTP_REFERER"])){
    $referral=$_SERVER["HTTP_REFERER"];
}

write_mysql_log($dbconn,$log_date,$message,$user_email,session_id(),$referral);