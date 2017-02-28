<?php
/*
 * Display repository filter panel on the search.php page
 *
 * input: an object of ConstructSearchView class
 * @param
 *      $this->repositoryFilter: array(array(string))
 * */
function partialRepositories($searchView)
{
    $page_name = (basename($_SERVER['PHP_SELF']));
    $repositories = $searchView->getRepositoryFilter();
    $repoN = 0;
    $threshold = 10;

    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Repositories</strong>
            <!--Clear All Label-->
            <?php if ($page_name == "search-filter.php" && $searchView->isRepositorySelected()): ?>
                <a class="hyperlink pull-right" role="button" target="_parent" href="<?php echo $searchView->getUrlWithQuery('search.php'); ?>" >
                    <i class="glyphicon glyphicon-remove-sign "></i>
                    Clear All

                </a>
            <?php endif; ?>
            <!--end of Clear All Label-->

        </div>

        <div class="panel-body">
            <ul class="no-disk" id="repositoryList">
                <?php
                if ($searchView->getSearchBuilder()->getTotalRows() > 0) {
                    $i=0;
                    foreach ($repositories as $key => $row) {

                        ?>
                        <?php if ($repoN == $threshold): ?>
                            <li class="container_hide">
                            <input type="checkbox" id="check_id">
                            <label for="check_id" onclick="parent.iframeLoaded()"></label>
                            <ul>
                        <?php endif; ?>
                        <li>
                            <!--checkbox-->
                            <a id="<?php echo "repository_".$i?>" target=_parent href="<?php echo $searchView->getUrlBySelectedRepository($row['id']); ?>">
                                <?php $repoN = $repoN + 1; ?>
                                <?php if ($key == "ClinicalTrials"): ?> <!--block clinicaltrials-->
                                <div style="color:gray">
                                    <?php endif; ?>
                                    <?php if ($row['selected'] == true): ?>
                                        <i class="fa fa-check-square"></i>
                                    <?php else: ?>
                                        <i class="fa fa-square-o"></i>
                                    <?php endif; ?>
                            </a>

                            <!--label-->
                            <span data-toggle="tooltip" data-placement="right"
                                  title=<?php echo $row['wholeName'] ?>> <?php echo $key; ?></span>

                            <!--number of results-->
                            <?php echo '(' . number_format($row['rows']) . ')'; ?>
                        </li>

                        <?php
                    }// end of foreach?>
                    <?php if ($repoN >= $threshold): ?>
                        </ul>
                        </li>
                    <?php endif; ?>
                <?php  $i++;} ?>
            </ul>
        </div>
    </div>
    <?php
}


?>