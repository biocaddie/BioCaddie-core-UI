<?php

require_once dirname(__FILE__) . '/config/config.php';
require_once dirname(__FILE__) . '/Model/TrackActivity.php';
?>
<?php include 'views/header.php'; ?>
    <style type="text/css">
        .popover {
            left: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }

        .popover.bottom > .arrow {
            left: 30px !important;
        }

        .input-group-addon {
            background-color: #58595b;
            color: #fff;
        }
    </style>


    <div class="container">

        <?php include 'views/index/SearchPanel.php'; ?>

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <?php include "views/index/Statistics.php"; ?>
            </div>
            <div class="col-lg-4 col-md-6">
                <?php include "views/index/Repositories.php"; ?>
            </div>
            <div class="col-lg-4 col-md-6">

                <?php include "views/index/NewFeatures.php"; ?>
            </div>
        </div>
    </div>

<?php include dirname(__FILE__) . "/views/index/PilotProjects.php"; ?>

<?php
/* ==== Page Specific Scripts ==== */
$scripts = [
    "./vendor/jquery/jquery.typewatch.js",
    "./js/page.scripts/index.js",
];
?>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>