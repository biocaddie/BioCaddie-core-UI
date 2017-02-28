<?php


require_once dirname(__FILE__) .'/config/config.php';

require_once dirname(__FILE__) . '/Model/SearchBuilder.php';
require_once dirname(__FILE__) . '/Model/AutoCorrection.php';
require_once dirname(__FILE__) . '/Model/ConstructSearchRepoView.php';
require_once dirname(__FILE__) . '/views/search_repository/filters.php';
require_once dirname(__FILE__) . '/views/feedback.php';

require_once dirname(__FILE__) . '/views/search_panel.php';
require_once dirname(__FILE__) . '/views/search/repositories.php';
require_once dirname(__FILE__) . '/views/search/pagination.php';
require_once dirname(__FILE__) . '/views/search/result_status.php';
require_once dirname(__FILE__) . '/views/feedback.php';
require_once dirname(__FILE__) . '/views/share.php';
require_once dirname(__FILE__) . '/views/search/switch_view.php';

require_once dirname(__FILE__) . '/views/search_repository/breadcrumb.php'; // Should be combined with the on under search folder
require_once dirname(__FILE__) . '/views/search_repository/filters.php';
require_once dirname(__FILE__) . '/views/search_repository/sorting.php';
require_once dirname(__FILE__) . '/views/search_repository/results.php';
require_once dirname(__FILE__) . '/views/search_repository/pilot_projects.php';

$searchBuilder = new SearchBuilder();
$searchBuilder->searchSingleRepo();
$searchRepoView = new ConstructSearchRepoView($searchBuilder);


$searchBuilderAll = new SearchBuilder();
$searchBuilderAll->searchAllRepo();
$searchRepoFilterView = new ConstructSearchRepoView($searchBuilderAll);

?>


<?php include dirname(__FILE__) . '/views/header.php'; ?>

<div class="container">
    <?php /* Search Panel */ ?>
    <?php echo partialSearchPanel($searchBuilder); ?>

    <!--breadcrumb-->
    <?php echo breadcrumb($searchBuilder); ?>

    <div class="row">
        <?php /* ###### Filter Panel ###### */ ?>
        <div class="col-sm-4 col-md-3">
            <?php partialRepositories($searchRepoFilterView); ?>
           <?php partialFilters($searchRepoView);
            partialFeedback();
           ?>
        </div>

        <?php /* ###### Search Result Panel ###### */ ?>
        <div class="col-sm-8 col-md-9">

            <?php /* ==== Pagination Panel ==== */ ?>
            <?php if ($searchBuilder->getSelectedTotalRows() > 0): ?>
                <?php partialResultsStatus($searchBuilder,$searchRepoView); ?>
                <?php partialPilotProjects($searchBuilder); ?>
                <div class="clearfix"></div>
                <?php partialPagination($searchBuilder,$searchRepoView); ?>
                <div class="pull-right" style="margin: 10px 0 0 5px;">
                    <?php partialShare($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); ?>
                </div>
                <?php partialSwitch($searchRepoView); ?>
                <?php partialSorting($searchBuilder, $searchRepoView); ?>
            <?php endif; ?>

            <div class="clearfix"></div>
            <?php /* ==== Search Result List ==== */ ?>

            <?php partialResults($searchBuilder,$searchRepoView); ?>


        </div>
    </div>
</div>

<?php
/* Page Custom Scripts. */
$scripts = ["./js/page.scripts/searchrepo.js"];
?>

<?php include dirname(__FILE__) . '/views/footer.php'; ?>

