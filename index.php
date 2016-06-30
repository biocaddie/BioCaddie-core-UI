<?php

require_once dirname(__FILE__) .'/config/config.php';
require_once dirname(__FILE__) . '/trackactivity.php';
?>

<?php include dirname(__FILE__) . '/views/header.php'; ?>

<div class="container">

    <?php require_once dirname(__FILE__) . '/views/index/search_panel.php';?>

    <div class="row">
        <div class="col-lg-4 col-md-6">
            <?php require_once(dirname(__FILE__) . "/views/index/statistics.php"); ?>
        </div>
        <div class="col-lg-4 col-md-6">
            <?php require_once(dirname(__FILE__) . "/views/index/repositories.php"); ?>
        </div>
        <div class="col-lg-4 col-md-6">

            <?php require_once(dirname(__FILE__) . "/views/index/new-features.php"); ?>
        </div>
    </div>
</div>

<?php require_once(dirname(__FILE__) . "/views/index/pilot-projects.php"); ?>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/js.cookie.js",
    "https://code.jquery.com/ui/1.10.3/jquery-ui.js",
    "https://d3js.org/d3.v3.min.js",
    "https://www.google.com/jsapi",
    "./js/page.scripts/index.js"
    ];
?>


<?php include dirname(__FILE__) . '/views/footer.php'; ?>


