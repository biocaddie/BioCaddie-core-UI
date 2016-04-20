<?php

function partialResultsStatus($searchBuilder) { ?>

    <div class="pull-left" style="margin-right: 10px">
        Displaying <?php echo $searchBuilder->getRowsLimit() > $searchBuilder->getTotalRows() ? $searchBuilder->getTotalRows() : $searchBuilder->getRowsLimit() ?>
        of <?php echo $searchBuilder->getTotalRows() ?>
        results for <strong>"<?php echo $searchBuilder->getQuery(); ?>"</strong> in <strong>"<?php echo $searchBuilder->getCurrentRepositoryname(); ?>"</strong>
        <?php echo $searchBuilder->get_selected_filters_info();?>
    </div>

<div class="pull-right" style="margin-right: 10px">
        <?php include dirname(__FILE__) . '/../share.php'; ?>
    </div>
<?php } ?>