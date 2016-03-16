<?php

function partialSorting($searchBuilder)
{
    ?>

    <div class="pull-right" >
        <span>Sorted By:</span>
        <div class="dropdown">
            <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdown-sort"
                    data-toggle="dropdown" style="margin-right:0">
                <?php echo ucwords($searchBuilder->getSort()) ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a href="<?php echo $searchBuilder->getUrlBySort('relevance') ?>"
                       title="Sort based on relevance to the search keyword.">Relevance</a></li>
                <li><a href="<?php echo $searchBuilder->getUrlBySort('date') ?>" title="Order By Article Date.">Date</a>
                </li>
            </ul>
        </div>
    </div>


    <?php
}

?>