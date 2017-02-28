<?php
require_once '../Model/DBController.php';
require_once '../database/Search.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$objDBController = new DBController();
$dbconn = $objDBController->getConn();
$search = new Search();

$user_email = $_SESSION['email'];

if (!empty($_POST['query'])) {
    foreach ($_POST['query'] as $check) {

        $searchID = $check;

        if ($search->Is_valid_User($dbconn, $searchID, $user_email)) {
            $search->setSearchId($check);
            if ($search->deleteSearch($dbconn)) {
                echo "ok";
            }else{
                echo "Error occurred during the deletion of the saved searches";
            }
        }else{
            echo "No Permission";
        }
    }
}