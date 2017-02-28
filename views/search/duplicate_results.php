<?php

/*
 * Display search results on the search.php page
 *
 * input: an object of ConstructSearchView class
 * @param
 *      $this->searchResults: array(array(string))
 * */

function duplicateResults($searchResult,$searchView)
{

        $singlePageUrlExtension = '&query=' . $searchView->getSearchBuilder()->getQuery();
        $selectedDatatypes = $searchView->getSearchBuilder()->getSelectedDatatypes();
        if ($selectedDatatypes != NULL) {
            $singlePageUrlExtension .= '&datatypes=' . implode(",", $selectedDatatypes);
        }
        ?>
        <ol class="duplicate-result" id="search-result_duplicate">
            <?php
            foreach ($searchResult as $item) {
                $keys = array_keys($item);
                $rowTitle = reduce_duplicate_in_title($item[$keys[0]]);
                $maxLen = 150;
                $rowTitleTooltip = strlen($rowTitle) > $maxLen ? $rowTitle : '';
                $rowTitleShort = $searchView->process_strong_highlight(strlen(strip_tags($rowTitle)) > $maxLen ? substr(strip_tags($rowTitle), 0, $maxLen) . '...' : $rowTitle);

                $linkUrl = $item['ref'];
                if ($searchView->getSearchBuilder()->getSearchType() != 'repository') {
                    $linkUrl = $item['ref'] . $singlePageUrlExtension;
                }

                ?>
                <li>
                    <p class="result-duplicate" data-html="true" title="<?php echo strip_tags($rowTitleTooltip) ?>" data-toggle="tooltip" data-placement="bottom">
                        <i class="glyphicon glyphicon-tint"></i>
                        <a id="<?php echo "result_".$item['es_id'];?>" href="<?php echo $linkUrl ?>">
                            <?php echo $rowTitleShort ?>
                        </a>
                        <!--repository label-->
                        <?php if ($searchView->getSearchBuilder()->getSearchType() == 'data'): ?>
                            <span class="result-reposity label label-repo">
                                <a href="search-repository.php?query=<?php echo $searchView->getSearchBuilder()->getQuery(); ?>&repository=<?php echo $item['source_ref']; ?>">
                                    <?php echo $item['source']; ?>
                                </a>
                            </span>
                        <?php endif; ?>
                    </p>
                </li>
                <?php
            }
            ?>
        </ol>
        <?php

}
?>


