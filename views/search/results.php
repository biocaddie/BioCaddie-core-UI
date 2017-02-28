<?php

/*
 * Display search results on the search.php page
 *
 * input: an object of ConstructSearchView class
 * @param
 *      $this->searchResults: array(array(string))
 * */

function partialResults($searchView)
{
    $searchResult = $searchView->getSearchResults();

    /*
     * This logic part should be moved to ConstructSearchView in the future
     * */
    if ($searchView->getSearchBuilder()->getSelectedTotalRows() > 0) {
        $singlePageUrlExtension = '&query=' . $searchView->getSearchBuilder()->getQuery();
        $selectedDatatypes = $searchView->getSearchBuilder()->getSelectedDatatypes();
        if ($selectedDatatypes != NULL) {
            $singlePageUrlExtension .= '&datatypes=' . implode(",", $selectedDatatypes);
        }

        ?>
        <ol class="search-result" id="search-result">
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
                    <p class="result-heading" data-html="true" title="<?php echo strip_tags($rowTitleTooltip) ?>" data-toggle="tooltip" data-placement="bottom">
                        <!--checkbox-->
                        <input name="share-check" type="checkbox" value="<?php echo $item['ref_raw'] ?>"/>
                        <!--title-->
                        <a href="<?php echo $linkUrl ?>">
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

                        <!--fields-->
                    <?php
                    foreach (array_slice($keys, 1, sizeof($keys) - 5) as $key) {
                        $fieldDisplayValue = strlen(trim($item[$key])) > 350 ? substr(trim($item[$key]), 0, 350) . '... (More In Details)' : trim($item[$key]);
                        $fieldTitleTooltip = strlen(trim($item[$key])) > $maxLen ? trim($fieldDisplayValue) : '';
                        $fieldTitleShort = $searchView->process_strong_highlight(strlen(trim($item[$key])) > $maxLen ? substr(trim($item[$key]), 0, $maxLen) . '...' : trim($item[$key]));
                        ?>
                        <p class="result-field">
                            <em><?php echo trim($key) ?>:</em>
                            <span title="" data-original-title="<?php echo strip_tags($fieldTitleTooltip) ?>" data-toggle="tooltip" data-placement="right">
                                <?php echo $fieldTitleShort ?>
                            </span>
                        </p>
                    <?php } ?>

                </li>
                <?php
            }
            ?>
        </ol>
        <?php
    } else {
        require_once dirname(__FILE__) . '/../no_item_found.php';
    }
}
?>


