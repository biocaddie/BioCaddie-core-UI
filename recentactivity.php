<?php
require_once dirname(__FILE__) .'/config/config.php';

require_once dirname(__FILE__).'/Model/DBController.php';
require_once dirname(__FILE__).'/database/Search.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';

$pageTitle = "Recent Activity";

$history = [];
if (isset($_SESSION["history"])) {
    $history = $_SESSION["history"]['query'];
    $date = $_SESSION["history"]['date'];
}
?>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/recentactivity.js"];
?>

<?php include dirname(__FILE__) . '/views/header.php'; ?>
<?php include dirname(__FILE__) . '/views/recent.php'; ?>
<?php include dirname(__FILE__) . '/views/footer.php'; ?>
