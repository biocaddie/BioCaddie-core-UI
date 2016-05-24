<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 5/5/16
 * Time: 9:16 AM
 */
require_once '../config/config.php';
require_once '../dbcontroller.php';
require_once '../database/UserCollection.php';
require_once '../database/Collection.php';

$objDBController = new DBController();
$dbconn=$objDBController->getConn();

$collections = new UserCollection();
$collectionItems = new Collection();


if(!empty($_POST['query'])) {
    foreach($_POST['query'] as $check) {
        $collections->setCollectionId($check);
        if($collections->deleteCollection($dbconn)){
            echo "ok";
        }
        $collectionItems->setCollectionId($check);
        if($collectionItems->deleteCollectionItem($dbconn)){
            echo "ok";
        }
    }
}