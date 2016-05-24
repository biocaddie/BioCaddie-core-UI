<?php
require_once '../dbcontroller.php';
require_once '../database/Search.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$objDBController = new DBController();
$dbconn=$objDBController->getConn();
$search = new Search();


if(!empty($_POST['query'])) {

    foreach($_POST['query'] as $check) {
        $search->setSearchId($check);
        if($search->deleteSearch($dbconn)){
            echo "ok";
        }
        //echo $check;
    }
}