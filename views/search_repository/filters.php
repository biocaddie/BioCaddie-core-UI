<?php

/*
 * Generate filters panel on search repository page
 * @input: array(array(string))
 *  {
 *      facet_name_1
 *      {
 *          facet_item_1,
 *          facet_item_2, ...
 *      },
 *      facet_name_2
 *      {
 *          ...
 *      }...
 * }
 *
 * */

function partialFilters($searchRepoView)
{
    $searchBuilder = $searchRepoView->getSearchBuilder();
    $selectedFilters = $searchBuilder->getSelectedFilters();

    if (sizeof($selectedFilters) > 0) {
        ?>
        <div style="margin: -10px 0 5px 10px;">
            <a class="hyperlink" role="button" href="<?php echo $searchRepoView->clearAllFiltersURL(); ?>">
                <i class="glyphicon glyphicon-remove-sign"></i>
                Clear All Filters
            </a>
        </div>
    <?php } ?>

    <div class="panel-group" id="accordion-filters" role="tablist" aria-multiselectable="true">

        <?php
        foreach ($searchRepoView->getRepositoryFacets() as $filters) {     // Each filter has a panel
            $key = $filters['key'];
            $normalizedKeyid = str_replace('.', '_', $key);
            ?>

            <div class="panel panel-success">
                <div class="panel-heading" role="tab" id="<?php echo 'heading' . $normalizedKeyid
                ?>">
                    <h4 class="panel-title">
                        <a role="button" data-target="#<?php echo 'filter' . $normalizedKeyid
                        ?>" data-toggle="collapse" aria-expanded="true"
                           aria-controls="<?php echo 'filter' . $normalizedKeyid
                           ?>">
                            <i class="fa fa-expand"></i> <?php echo $filters['display_name'] ?>
                        </a>
                    </h4>
                </div>

                <div id="<?php echo 'filter' . $normalizedKeyid
                ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <ul class="no-disk">
                            <?php foreach ($filters['terms'] as $filter_item) { ?>
                                <li>
                                    <a href="<?php echo $filter_item['url']; ?>">
                                        <?php if ($filter_item['selected'] == true): ?>
                                            <i class="fa fa-check-square"></i>
                                        <?php else: ?>
                                            <i class="fa fa-square-o"></i>
                                        <?php endif; ?>

                                        <?php if ($filter_item['display'] == true): ?>
                                            <span>
                                            <?php else: ?>
                                            <span style="color:gray">
                                             <?php endif; ?>
                                                 <?php echo ucfirst($filter_item['tag_display_name']); ?>
                                                 <?php echo '(' . number_format($filter_item['count']) . ')'; ?>
                                        </span>
                                    </a>
                                </li>
                                <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } ?>
    </div>
    <?php
}?>