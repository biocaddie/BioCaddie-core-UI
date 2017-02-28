<?php
$pageTitle = "Advanced Search";

require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__) . '/Model/SearchBuilder.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';
?>

<?php include dirname(__FILE__) . '/views/header.php';?>

<div class="container">
    <?php include dirname(__FILE__) . '/views/breadcrumb.php'; ?>
    <div class="row">
        <h3>DataMed Advanced Search Builder</h3>
    </div>

    <div  style="margin-top: 30px">
        <form action='./search.php' method='get' autocomplete='off' id="search-form">
            <?php include dirname(__FILE__) . '/views/advanced/builder.php'; ?>
        </form>
    </div>

</div>

<?php
/* Page Custom Scripts. */
$scripts = ["./vendor/jquery/jquery-ui-autocomplete.js",
    "./js/page.scripts/advanced.js"];
?>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>

