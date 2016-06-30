<?php



function partialRepositories($searchBuilder) {
    $nums = array();
    $repositores = $searchBuilder->getRepositories();
    foreach ($repositores as $key => $row)
    {
        $nums[$key] = $row['rows'];
    }
    array_multisort($nums, SORT_DESC, $repositores);
    $repoN = 0;
    $threshold = 10;

    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><span class="glyphicon glyphicon-chevron-up"></span> Repositories</strong>
        </div>
        <div class="panel-body">
            <ul class="no-disk">
                <?php foreach ($repositores as $repositoryName => $details): ?>
                    <?php if($details['rows']==0){
                       continue;
                    } ?>
                    <?php if ($repoN == $threshold): ?>
                       <li class="container_hide">
                       <input type="checkbox" id="check_id">
                       <label for="check_id"></label>
                       <ul>
                    <?php endif; ?>
                    <li>

                        <!--<a href="<?php echo $searchBuilder->getUrlByRepository($details['id']); ?>">-->
                        <a href="search.php?query=<?php echo $searchBuilder->getQuery()?>&searchtype=<?php echo $searchBuilder->getSearchType()?><?php echo cancel_repository_if_unselect($details['id'],$searchBuilder->getcurrentRepository()); ?>">
                            <?php if ($details['selected'] == true): ?>
                               <!-- <i class="fa fa-check-circle-o"></i>-->
                                <i class="fa fa-check-square"></i>
                            <?php else: ?>
                                <i class="fa fa-square-o"></i>
                            <?php endif; ?>
                        </a>

                        <span data-toggle="tooltip" data-placement="right" title=<?php echo $details['whole']?> > <?php echo $repositoryName; ?></span>
                        <?php echo '(' . number_format($details['rows']) . ')'; ?>

                        <?php if ($details['selected'] == true): ?>
                            <!--   <span style="color:blue">| Detail View</span>-->
                        <?php endif; ?>

                        <?php $repoN = $repoN + 1; ?>
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

function cancel_repository_if_unselect($id,$newid){
    if($id==$newid) {
        return '';
    }
    else{
        return '&repository='.$id.','.$newid;
    }

}
?>
