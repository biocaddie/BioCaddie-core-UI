<?php

/*
 * Switch between summary view and detail view
 * */

function partialSwitch($searchView)
{
    $page_name = basename($_SERVER['PHP_SELF']);
    ?>
    <?php if (isset($_GET['repository']) && strlen($_GET['repository']) == 4) {
    if ($page_name == 'search.php') { ?>
        <div class="pull-right" style="margin-left: 10px">
            <span>Switch View:</span>
            <span data-toggle="tooltip" data-placement="top" title="Switch to summary View">
                <span class="fa fa-bars switchview activeView" id="btn-summary"></span>
            </span>
            <span data-toggle="tooltip" data-placement="top" title="Switch to detail View">
                <a href="<?php echo $searchView->switchView($_GET['repository']); ?>">
                    <span class="fa fa-table switchview" id="btn-detail"
                          onclick="switchView('<?php echo $_GET['repository'] ?>')"></span>
                </a>
            </span>
        </div>
    <?php } else {
        ?>
        <div class="pull-right" style="margin: 7px 10px 0 10px;">
            <span>Switch View:</span>
        <span data-toggle="tooltip" data-placement="top" title="Switch to summary view">
            <a href="<?php echo $searchView->switchView($_GET['repository']); ?>">
                <span class="fa fa-bars switchview " id="btn-summary"></span>
            </a>
        </span>
        <span data-toggle="tooltip" data-placement="top" title="Switch to detail view">
            <span class="fa fa-table switchview activeView" id="btn-detail"
                  onclick="switchView('<?php echo $_GET['repository'] ?>')"></span>
        </span>
        </div>
        <?php
    }
}
} ?>
