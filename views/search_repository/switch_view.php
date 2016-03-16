<?php

function partialSwitch($searchBuilder) { ?>
    <div class="pull-right" style="margin: 7px 10px 0 10px;">
        <span>Switch View:</span>
        <span data-toggle="tooltip" data-placement="top" title="Switch to summary view">
            <a href="<?php echo $searchBuilder->getbackUrlByRepository($_GET['repository']); ?>">
                <span class="fa fa-bars switchview " id="btn-summary"></span>
            </a>
        </span>
        <span data-toggle="tooltip" data-placement="top" title="Switch to detail view">
            <span class="fa fa-table switchview activeView" id="btn-detail"
                  onclick="switchView('<?php echo $_GET['repository'] ?>')"></span>
        </span>
    </div>
<?php } ?>