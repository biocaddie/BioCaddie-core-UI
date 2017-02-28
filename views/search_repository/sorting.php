<?php

function partialSorting($searchBuilder, $searchView) { 
    $repoId = $searchBuilder->getSelectedRepositories()[0];
    $sort = $searchBuilder->getSort();
    $sort_field = $searchBuilder->getSortFieldSingleRepo();
    ?>
    <div class="pull-right" style="margin-top: 8px">
        <span style="margin-right: 5px">Sorted By:</span>
        <div class="dropdown" style="display: inline-block">
            <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdown-sort" data-toggle="dropdown">
                <?php echo ucwords($searchBuilder->getSort()) ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                                <li>
                    <a href="<?php echo $searchView->getSortUrl('relevance') ?>" <?php echo  $sort == "relevance" ? 'class="active"' : '' ?>
                       title="Sort based on relevance to the search keyword.">Relevance</a>
                </li>

                <?php if(strlen($sort_field)>0):?>
                <li>
                    <a href="<?php echo $searchView->getSortUrl('date') ?>" <?php echo  $sort == "date" ? 'class="active"' : '' ?>
                       title="Order By Article Date.">Date</a>
                </li>
                <?php endif;?>

                <!-- <li>
                    <a href="<?php echo $searchView->getSortUrl('title') ?>" <?php echo  $sort == "title" ? 'class="active"' : '' ?>
                       title="Order By Article Title.">Title</a>
                </li>-->
                <!--<li>
                    <?php if ($repoId == '0002' || $repoId == '0003'): ?>
                        <a href="<?php echo $searchView->getSortUrl('citations') ?>" <?php echo  $sort == "citations" ? 'class="active"' : '' ?>
                           title="Order By Citation Count.">Citations</a>
                    <?php else: ?>
                        <li class="disabled"><a>Citation</a></li>
                    <?php endif ?>
                </li>-->
            </ul>
        </div>
    </div>
<?php } ?>