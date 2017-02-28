<?php

require_once '../Model/DBController.php';
require_once '../database/Search.php';
require_once '../config/config.php';

$objDBController = new DBController();
$dbconn=$objDBController->getConn();
$search = new Search();

$user_email = "";
if(isset($_SESSION['email'])){
    $user_email = $_SESSION['email'];
}


if(!empty($_POST['query'])) {
    foreach($_POST['query'] as $check) {

        if ($search->If_User_Exist($dbconn, $user_email)) {
            $search->setDate(explode("|",$check)[2]);
            $search->setSearchTerm(explode("|",$check)[0]);
            $search->setSearchType(explode("|",$check)[1]);
            $search->setUemail($_SESSION['email']);


            if($search->saveSearch($dbconn)){
                echo "ok";
            }else{
                echo "Error occurred during the saving of searches";
            }
        }
    }
}

