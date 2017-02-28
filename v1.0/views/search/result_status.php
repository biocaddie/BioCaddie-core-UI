<?php

function partialResultsStatus($searchBuilder) {
    ?>
    <div class="pull-left" style="margin-right: 10px">
        <span>Displaying <?php echo $searchBuilder->getRowsPerPage() > $searchBuilder->getTotalRows() ? $searchBuilder->getTotalRows() : $searchBuilder->getRowsPerPage() ?></span>
        <span>of <?php echo number_format($searchBuilder->getTotalRows()) ?></span>
        <span>results for "<strong><?php echo $searchBuilder->getQuery(); ?>"</strong></span>
    </div>

    <div class="pull-left" >
        <div class="dropdown">
            <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdown-sort" data-toggle="dropdown">
                <?php echo $searchBuilder->getRowsPerPage() ?> Per Page
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="<?php echo getRowsPerPageUrl(5, $searchBuilder) ?>" <?php echo getRowsPerPageStyle(5, $searchBuilder) ?>>5</a></li>
                <li><a href="<?php echo getRowsPerPageUrl(10, $searchBuilder) ?>" <?php echo getRowsPerPageStyle(10, $searchBuilder) ?>>10</a></li>
                <li><a href="<?php echo getRowsPerPageUrl(20, $searchBuilder) ?>" <?php echo getRowsPerPageStyle(20, $searchBuilder) ?>>20</a></li>
                <li><a href="<?php echo getRowsPerPageUrl(50, $searchBuilder) ?>" <?php echo getRowsPerPageStyle(50, $searchBuilder) ?>>50</a></li>
                <li><a href="<?php echo getRowsPerPageUrl(100, $searchBuilder) ?>" <?php echo getRowsPerPageStyle(100, $searchBuilder) ?>>100</a></li>
                <li><a href="<?php echo getRowsPerPageUrl(200, $searchBuilder) ?>" <?php echo getRowsPerPageStyle(200, $searchBuilder) ?>>200</a></li>
                </li>
            </ul>
        </div>
    </div>
    <?php
}

function getRowsPerPageUrl($row, $searchBuilder) {
    return $row == $searchBuilder->getRowsPerPage() ? '' : $searchBuilder->getUrlByRowsPerPage($row);
}

function getRowsPerPageStyle($row, $searchBuilder) {
    if ($row == $searchBuilder->getRowsPerPage()) {
        return 'style="background-color: #D8D7D7;"';
    }
    return '';
}
?>