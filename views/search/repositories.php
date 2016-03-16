<?php
function partialRepositories($searchBuilder) {
    $nums = array();
    $repositores = $searchBuilder->getRepositoriesList();
    //var_dump($repositores);
    foreach ($repositores as $key => $row)
    {
        $nums[$key] = $row['rows'];
    }
    array_multisort($nums, SORT_DESC, $repositores);
    $repoN = 0;
    $threshold = 10;
    if ($searchBuilder->getTotalRows() > 0) {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><span class="glyphicon glyphicon-chevron-up"></span> Repositories</strong>
            </div>
            <div class="panel-body">
                <ul class="no-disk" id="repositoryList">
                    <?php foreach ($repositores as $repositoryName => $details):?>

                       <?php if ($repoN == $threshold): ?>
                           <li class="container_hide">
                           <input type="checkbox" id="check_id">
                           <label for="check_id"></label>
                           <ul>
                            <?php endif; ?>

                        <li>
                            <a href="<?php echo $searchBuilder->getUrlBySelectedRepository($details['id']) ?>">
                                <?php
                                if ($details['selected'] == true): ?>
                                    <i class="fa fa-check-square" value="<?php echo $searchBuilder->getUrlByRepository($details['id'])?>"></i>
                                <?php else: ?>
                                    <i class="fa fa-square-o"></i>
                                <?php endif; ?>
                            </a>
                            <span data-toggle="tooltip" data-placement="right" title=<?php echo $details['whole']?> > <?php echo $repositoryName; ?></span>
                            <?php echo '(' . $details['rows'] .')';?>
                            <?php $repoN = $repoN + 1; ?>
                        </li>
                    <?php endforeach; ?>

                      <?php if ($repoN >= $threshold): ?>
                           </ul>
                           </li>
                      <?php endif; ?>
                    <!--<li>
                         <a href="<?php echo $searchBuilder->getUrlWithQyery() ?>">-->

                        <!--   <?php if($searchBuilder->isRepositorySelected()):?>
                       <a href="search.php?query=<?php echo $searchBuilder->getQuery() ?>&searchtype=<?php echo $searchBuilder->getSearchType() ?>">
                              <i class="fa fa-square-o"></i> Select All
                        </a>
                                <?php else:?>
                                <i class="fa fa-check-square-o"></i> Select All
                            <?php endif;?>


                    </li>-->
                </ul>
            </div>
        </div>
        <?php
    }
}


?>