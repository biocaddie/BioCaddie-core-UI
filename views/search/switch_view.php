<?php

function partialSwitch($searchBuilder) {
    ?>
    <?php if (isset($_GET['repository']) && strlen($_GET['repository']) == 4) { ?>
        <div class="pull-right" style="margin-left: 10px">
            <span>Switch View:</span>
            <span data-toggle="tooltip" data-placement="top" title="Summary View">
                <span class="fa fa-bars switchview activeView" id="btn-summary"></span>
            </span>
            <span data-toggle="tooltip" data-placement="top" title="Detail View">
                <a href="<?php echo $searchBuilder->getUrlByRepository($_GET['repository']); ?>">
                    <span class="fa fa-table switchview" id="btn-detail"
                          onclick="switchView('<?php echo $_GET['repository'] ?>')"></span>
                </a>
            </span>
        </div>
    <?php }
} ?>
