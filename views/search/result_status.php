<?php

/*
 * Display result status and number of results per page on the search.php page
 * input: an object of SearchBuilder class
 *
 * */

function partialResultsStatus($searchBuilder, $searchView)
{
    $rowNum = array("5", "10", "20", "50", "100");
    $rows_per_page = $searchBuilder->getRowsPerPage();
    $selected_total_rows = $searchBuilder->getSelectedTotalRows();
    ?>
    <div class="pull-left" style="margin-right: 10px">
        <span>Displaying <?php echo $rows_per_page >  $selected_total_rows ? $selected_total_rows : $rows_per_page ?></span>
        <span>of <?php echo number_format($selected_total_rows) ?></span>
        <span>results for "<strong><?php echo $searchBuilder->getQuery(); ?>"</strong></span>
    </div>


    <!-- Select number of results per page-->
    <div class="pull-left">
        <div class="dropdown">
            <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdown-sort"
                    data-toggle="dropdown">
                <?php echo $rows_per_page ?> Per Page
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php  foreach ($rowNum as $num) { ?>
                    <li>
                        <a href="<?php echo $searchView->getRowsPerPageUrl($num, $searchBuilder) ?>" <?php echo $searchView->getRowsPerPageStyle($num, $searchBuilder) ?>><?php echo $num; ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>