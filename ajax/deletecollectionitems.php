<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 5/5/16
 * Time: 9:16 AM
 */
require_once '../config/config.php';
require_once '../Model/DBController.php';
require_once '../database/Collection.php';

$objDBController = new DBController();
$dbconn=$objDBController->getConn();

$collectionItems = new Collection();
$user_email =$_SESSION['email'];

if(!empty($_POST['query'])) {
    foreach($_POST['query'] as $check) {
        $collectionItems->setCollectionItemId($check);
        if($collectionItems -> Is_valid_User($dbconn,$check,$user_email)){
            if($collectionItems->deleteCollectionItem($dbconn)){
                echo "ok";
            }else{
                echo "Error occurred during the deletion";
            }
        }else{
            echo "No permission";
        }

    }
}