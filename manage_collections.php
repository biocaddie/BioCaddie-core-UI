<?php

$pageTitle = "Manage Collections";

require_once dirname(__FILE__) .'/config/config.php';
require_once './Model/DBController.php';
require_once './database/Collection.php';
require_once './database/UserCollection.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';

if (!isset($_SESSION['name'])) {
    echo "<script> parent.self.location = \"login.php\";</script>";
} else {


$objDBController = new DBController();
$dbconn=$objDBController->getConn();

$collection = new Collection();
$userCollection = new UserCollection();
if(isset($_SESSION['email'])){
    $userCollection->setUserEmail($_SESSION['email']);
}
}
?>

<?php include dirname(__FILE__) . '/views/header.php'; ?>

<?php
    if(isset($_GET['name'])){
        $userCollection->setCollectionName($_GET['name']);
        $collectionID = $userCollection->searchCollectionId($dbconn);
        $collection->setCollectionId($collectionID[0]['collection_id']);
        $result = $collection->queryCollectionItem($dbconn);

        include dirname(__FILE__) . '/views/collections/manage_collection_items.php';

    }else{
        $result = $userCollection->showCollections($dbconn);
        include dirname(__FILE__) . '/views/collections/manage_collections.php';
    }
?>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/manage_collections.js"];
?>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>