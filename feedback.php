<?php
require_once  'config/config.php';
require_once  'Model/TrackActivity.php';
include 'views/header.php'; ?>


<div class="container" style="margin-top: 100px">
    <div class="col-md-3 col-lg-offset-1">
        <div class="panel panel-default" style="text-align: center;height: 300px;padding-top: 100px">
            <div class="panel-body">
                <i class="fa fa-envelope fa-4x"></i><br>
                <a href="./about.php" class="hyperlink"> <i>Contact Us</i></a>

            </div>
        </div>


    </div>

    <div class="col-md-3">
        <div class="panel panel-default" style="text-align: center;height: 300px;padding-top: 100px">
            <div class="panel-body">
                <i class="fa fa-sticky-note fa-4x"></i><br>
                <a href="./questionnaire.php" class="hyperlink"><i> Questionnaire</i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-default" style="text-align: center;height: 300px;padding-top: 100px">
            <div class="panel-body">
                <i class="fa fa-github fa-4x"></i><br>
                <a href="https://github.com/biocaddie/prototype_issues/issues" target="_blank" class="hyperlink"><i> Report an issue on GitHub</i>
                </a>
            </div>
        </div>
    </div>
</div>

<?php  '/views/footer.php'; ?>
