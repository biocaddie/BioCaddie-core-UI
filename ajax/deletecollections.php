<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 5/5/16
 * Time: 9:16 AM
 */
require_once '../config/config.php';
require_once '../Model/DBController.php';
require_once '../database/UserCollection.php';
require_once '../database/Collection.php';

$objDBController = new DBController();
$dbconn=$objDBController->getConn();

$collections = new UserCollection();
$collectionItems = new Collection();

$user_email = $_SESSION['email'];

if(!empty($_POST['query'])) {
    foreach($_POST['query'] as $check) {
        $collectionID = $check;

        // Valid user's identity
        if($collections->Is_valid_User($dbconn,$collectionID, $user_email)){

            // delete all collection items in the collection
            $collectionItems->setCollectionId($check);
            if($collectionItems->deleteAllCollectionItem($dbconn)){
                echo "ok";
            }else{
                echo "Error occurred during the deletion of all collection items";
            }

            // delete collection
            $collections->setCollectionId($check);
            if($collections->deleteCollection($dbconn)){
                echo "ok";
            }else{
                echo "Error occurred during the deletion of the collection";
            }

        }else{
            echo "No Permission";
        }
    }
}