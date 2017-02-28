<?php
function partialRepositories($searchBuilder) {
    $nums = array();
    $repositores = $searchBuilder->getRepositoriesList();

    //var_dump($repositores);

    foreach ($repositores as $key => $row)
    {
        $nums[$key] = $row['rows'];
        //for put the clinical trials to the last
        if($key=="ClinicalTrials"){
            $nums[$key] = -1*$row['rows'];
        }
    }
    array_multisort($nums, SORT_DESC, $repositores);
    $repoN = 0;
    $threshold = 10;
    if ($searchBuilder->getTotalRows() > 0) {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Repositories</strong>
                <?php if($searchBuilder->isRepositorySelected()):?>
                    <a class="hyperlink pull-right" role="button" href="search.php?query=<?php echo $searchBuilder->getQuery() ?>&searchtype=<?php echo $searchBuilder->getSearchType() ?>">

                        <i class="glyphicon glyphicon-remove-sign "></i>
                        Clear All
                    </a>
                <?php endif;?>
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
                                <?php if($repositoryName=="ClinicalTrials"):?>
                                        <div style="color:gray">
                                <?php endif;?>
                                <?php
                                if ($details['selected'] == true): ?>
                                    <i class="fa fa-check-square" value="<?php echo $searchBuilder->getUrlByRepository($details['id'])?>"></i>
                                <?php else: ?>
                                    <i class="fa fa-square-o"></i>
                                <?php endif; ?>
                            </a>
                            <span data-toggle="tooltip" data-placement="right" title=<?php echo $details['whole']?> > <?php echo $repositoryName; ?></span>
                            <?php echo '(' . number_format($details['rows']) .')';?>
                            <?php $repoN = $repoN + 1; ?>
                            <?php if($repositoryName=="ClinicalTrials"):?>
                                    </div>
                            <?php endif;?>
                        </li>

                    <?php endforeach; ?>

                      <?php if ($repoN >= $threshold): ?>
                           </ul>
                           </li>
                      <?php endif; ?>

                </ul>
            </div>
        </div>
        <?php
    }
}


?>