<?php

function partialResults($searchBuilder) {
    if ($searchBuilder->getTotalRows() > 0) {
        $singlePageUrlExtension = '&query=' . $searchBuilder->getQuery();
        $selectedDatatypes = $searchBuilder->getSelectedDatatypes();
        if ($selectedDatatypes != NULL) {
            $singlePageUrlExtension .= '&datatypes=' . implode(",", $selectedDatatypes);
        }

        ?>
        <ol class="search-result" id="search-result">
            <?php
            $index = 0;
            foreach ($searchBuilder->getSearchResults() as $item) {
                $keys = array_keys($item);
                $rowTitle = reduce_dupilicate_in_title($item[$keys[0]]);
                $maxLen = 160;
                $rowTitleShort = strlen($rowTitle) > $maxLen ? substr($rowTitle, 0, $maxLen) . '...' : $rowTitle;
                $rowTitleTooltip = strlen($rowTitle) > $maxLen ? $rowTitle : '';

                if ($searchBuilder->getSearchType() != 'repository') {
                    $linkUrl = $item['ref'] . $singlePageUrlExtension;
                } else {
                    $linkUrl = $item['ref'];

                }
                ?>
                <li>
                    <p class="result-heading" data-html="true" title="<?php echo $rowTitleTooltip ?>" data-toggle="tooltip" data-placement="bottom">
                        <input name="share-check" type="checkbox" value="<?php echo $index ?>" />
                        <?php if ($searchBuilder->getSearchType() != 'repository') {?>
                           <a href="<?php echo $linkUrl ?>">
                        <?php } else{?>
                           <a href="<?php echo $linkUrl ?>" target="_blank">
                        <?php }?>
                            <?php echo $rowTitleShort ?>
                        </a>
                        <?php if ($searchBuilder->getSearchType() == 'data'): ?>
                            <span class="result-reposity label label-repo">
                                <a href="search-repository.php?query=<?php echo $searchBuilder->getQuery(); ?>&repository=<?php echo $item['source_ref']; ?>">
                                    <?php echo $item['source']; ?>
                                </a>
                            </span>
                        <?php endif; ?>
                    </p>

                    <?php
                    foreach (array_slice($keys, 1, sizeof($keys) - 5) as $key) {
                        $fieldTitleShort = strlen(trim($item[$key])) > $maxLen ? substr(trim($item[$key]), 0, $maxLen) . '...' : trim($item[$key]);
                        $fieldDisplayValue = strlen(trim($item[$key])) > 350 ? substr(trim($item[$key]), 0, 350) . '... (More In Details)' : trim($item[$key]);
                        $fieldTitleTooltip = strlen(trim($item[$key])) > $maxLen ? trim($fieldDisplayValue) : '';
                        ?>
                        <p class="result-field">
                            <em><?php echo trim($key) ?>:</em>
                            <span data-html="true" title="<?php echo $fieldTitleTooltip ?>" data-toggle="tooltip" data-placement="bottom">
                                <?php echo $fieldTitleShort ?>
                            </span>
                        </p>
                    <?php } ?>
                </li>
                <?php
                $index++;
            }
            ?>
        </ol>
        <?php
    } else {
        ?>
        <div class="alert alert-warning">
            <strong>NO ITEMS FOUND!</strong>
            <p>The following term was not found in bioCADDIE:
                <strong class="text-danger"><?php echo $searchBuilder->getQuery(); ?></strong>
            </p>
        </div>
        <?php
    }
}
?>
