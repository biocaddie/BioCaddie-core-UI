<?php

$pageTitle = "Manage Saved Searches";

require_once dirname(__FILE__) .'/config/config.php';

require_once dirname(__FILE__).'/Model/DBController.php';
require_once dirname(__FILE__).'/database/Search.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';

$objDBController = new DBController();
$dbconn=$objDBController->getConn();

$search = new Search();
if(isset($_SESSION['email'])){
    $search->setUemail($_SESSION['email']);
}

$result = $search->showSearch($dbconn);
?>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/savedsearch.js"];
?>

<?php include dirname(__FILE__) . '/views/header.php'; ?>
<?php include dirname(__FILE__) . '/views/savedsearch.php'; ?>
<?php include dirname(__FILE__) . '/views/footer.php'; ?>