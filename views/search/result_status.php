<?php

function partialResultsStatus($searchBuilder) {
    ?>
    <div class="pull-left" style="margin-right: 10px">
        <span>Displaying <?php echo $searchBuilder->getRowsLimit() > $searchBuilder->getTotalRows() ? $searchBuilder->getTotalRows() : $searchBuilder->getRowsLimit() ?></span>
        <span>of <?php echo $searchBuilder->getTotalRows() ?></span>
        <span>results for "<strong><?php echo $searchBuilder->getQuery(); ?>"</strong></span>
    </div>
    <?php
}
?>