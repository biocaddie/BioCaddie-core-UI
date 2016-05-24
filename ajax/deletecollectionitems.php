<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 5/5/16
 * Time: 9:16 AM
 */
require_once '../config/config.php';
require_once '../dbcontroller.php';
require_once '../database/Collection.php';

$objDBController = new DBController();
$dbconn=$objDBController->getConn();

$collectionItems = new Collection();


if(!empty($_POST['query'])) {
    foreach($_POST['query'] as $check) {
        $collectionItems->setCollectionItemId($check);
        if($collectionItems->deleteCollectionItem($dbconn)){
            echo "ok";
        }
    }
}