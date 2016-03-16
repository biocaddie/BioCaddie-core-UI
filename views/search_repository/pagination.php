<?php

function partialPagination($searchBuilder) {
    $min = max(1, $searchBuilder->getOffset() - 5);
    $min = min($min, ceil($searchBuilder->getTotalRows() / $searchBuilder->getRowsLimit()) - 10);
    $min = max($min, 1);
    $max = min(ceil($searchBuilder->getTotalRows() / $searchBuilder->getRowsLimit()), $searchBuilder->getOffset() + 5);
    $max = max(10, $max);
    ?>
    <div class="pull-left">
        <ul id="search-repository-pagination" class="pagination">
            <li>
                <a href="<?php echo $searchBuilder->getUrlByOffset(1) ?>" aria-label="Previous">
                    <span aria-hidden="true">First</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $searchBuilder->getUrlByOffset(get_previsoue($searchBuilder->getOffset())); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php
            for ($i = $min; $i <= min($max, ceil($searchBuilder->getTotalRows() / $searchBuilder->getRowsLimit())); $i++) {
                $activeFlag = $searchBuilder->getOffset() == $i ? 'class="active"' : '';
                ?>
                <li <?php echo $activeFlag; ?>>
                    <a href=" <?php echo $searchBuilder->getUrlByOffset($i); ?>"><?php echo $i ?></a>
                </li>
            <?php } ?>
            <li>
                <a href="<?php echo $searchBuilder->getUrlByOffset(get_next($searchBuilder->getOffset(), $searchBuilder->getTotalRows(), $searchBuilder->getRowsLimit())) ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $searchBuilder->getUrlByOffset(ceil($searchBuilder->getTotalRows() / $searchBuilder->getRowsLimit())) ?>" aria-label="Next">
                    <span aria-hidden="true">Last</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="pull-right" style="margin: 5px 0 0 0;">
        <?php partialShare($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); ?>
    </div>
    <?php
}

function get_previsoue($offset) {
    //get previous offset index
    if ($offset > 1) {
        $offset = $offset - 1;
    }
    return $offset;
}

function get_next($offset, $num, $N) {
    // get next offset index
    if ($offset < $num / $N) {
        $offset = $offset + 1;
    }
    return $offset;
}

function show_current_record_number($offset, $num, $N) {
    //show the record number in the current page
    if ($offset < $num / $N) {
        return ((($offset - 1) * $N) + 1) . "-" . ($offset) * $N;
    } else {
        return ((($offset - 1) * $N) + 1) . "-" . $num;
    }
}
?>