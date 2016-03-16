<?php

require_once '../dbcontroller.php';
require_once '../database/Search.php';
require_once '../config/config.php';

$objDBController = new DBController();
$dbconn=$objDBController->getConn();
$search = new Search();

if(!empty($_POST['query'])) {

    foreach($_POST['query'] as $check) {

        $search->setDate(explode("|",$check)[2]);
        $search->setSearchTerm(explode("|",$check)[0]);
        $search->setSearchType(explode("|",$check)[1]);
        $search->setUemail($_SESSION['email']);


        if($search->saveSearch($dbconn)){
            echo "ok";
        }
    }
}

