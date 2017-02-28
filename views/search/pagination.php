<?php //include dirname(__FILE__) . '/../share.php'; ?>
<?php

/*
 * Display pagination on the search.php page
 *
 * input: an object of SearchBuilder class
 * @param
 *
 * */

function partialPagination($searchBuilder, $searchView)
{
    $min = max(1, $searchBuilder->getOffset() - 5);
    $min = min($min, ceil($searchBuilder->getSelectedTotalRows() / $searchBuilder->getRowsPerPage()) - 10);
    $min = max($min, 1);
    $max = min(ceil($searchBuilder->getSelectedTotalRows() / $searchBuilder->getRowsPerPage()), $searchBuilder->getOffset() + 5);
    $max = max(10, $max);
    ?>
    <div class="pull-left">
        <ul id="search-pagination" class="pagination">
            <li>
                <a href="<?php echo $searchView->getUrlByOffset(1) ?>" aria-label="Previous">
                    <span aria-hidden="true">First</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $searchView->getUrlByOffset($searchView->get_previsoue($searchBuilder->getOffset())); ?>"
                   aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php
            for ($i = $min; $i <= min($max, ceil($searchBuilder->getSelectedTotalRows() / $searchBuilder->getRowsPerPage())); $i++) {
                $activeFlag = $searchBuilder->getOffset() == $i ? 'class="active"' : '';
                ?>
                <li <?php echo $activeFlag; ?>>
                    <a href=" <?php echo $searchView->getUrlByOffset($i); ?>"><?php echo $i ?></a>
                </li>
            <?php } ?>
            <li>
                <a href="<?php echo $searchView->getUrlByOffset($searchView->get_next($searchBuilder->getOffset(), $searchBuilder->getSelectedTotalRows(), $searchBuilder->getRowsPerPage())) ?>"
                   aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $searchView->getUrlByOffset(ceil($searchBuilder->getSelectedTotalRows() / $searchBuilder->getRowsPerPage())) ?>"
                   aria-label="Next">
                    <span aria-hidden="true">Last</span>
                </a>
            </li>
        </ul>

    </div>

    <?php
}

?>