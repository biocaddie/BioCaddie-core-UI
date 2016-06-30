<?php

require_once dirname(__FILE__) .'/config/config.php';

require_once './dbcontroller.php';
require_once './database/Search.php';
require_once dirname(__FILE__) . '/trackactivity.php';

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