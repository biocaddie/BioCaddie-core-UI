<?php

function partialSorting($searchBuilder) { ?>
    <div class="pull-right" style="margin-top: 8px">
        <span style="margin-right: 5px">Sorted By:</span>
        <div class="dropdown" style="display: inline-block">
            <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdown-sort" data-toggle="dropdown">
                <?php echo ucwords($searchBuilder->getSort()) ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="<?php echo $searchBuilder->getUrlBySort('relevance') ?>" title="Sort based on relevance to the search keyword.">Relevance</a>
                </li>
                <li class="hidden">
                    <a href="<?php echo $searchBuilder->getUrlBySort('date') ?>" title="Order By Article Date.">Date</a>
                </li>
                <?php if ($searchBuilder->getCurrentRepository() == '0002' || $searchBuilder->getCurrentRepository() == '0003'): ?>
                    <li>
                        <a href="<?php echo $searchBuilder->getUrlBySort('citation') ?>" title="Order By Number of Citations.">Citation</a>
                    </li>
                <?php else: ?>
                    <li class="disabled"><a>Citation</a></li>
                    <?php endif ?>

            </ul>
        </div>
    </div>
<?php } ?>