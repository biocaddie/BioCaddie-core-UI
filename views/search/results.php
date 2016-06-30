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
            foreach ($searchBuilder->getSearchResults() as $item) {
                $keys = array_keys($item);
                $rowTitle = reduce_dupilicate_in_title($item[$keys[0]]);
                $maxLen = 150;
                $rowTitleTooltip = strlen($rowTitle) > $maxLen ? $rowTitle : '';
                $rowTitleShort = process_strong_highlight(strlen(strip_tags($rowTitle)) > $maxLen ? substr(strip_tags($rowTitle), 0, $maxLen) . '...' : $rowTitle);
                if ($searchBuilder->getSearchType() != 'repository') {
                    $linkUrl = $item['ref'] . $singlePageUrlExtension;
                } else {
                    $linkUrl = $item['ref'];
                }
                ?>
                <li>
                    <p  class="result-heading" data-html="true" title="<?php echo $rowTitleTooltip ?>" data-toggle="tooltip" data-placement="bottom">
                        <input name="share-check" type="checkbox" value="<?php echo $item['ref_raw'] ?>" />
                        <?php if ($searchBuilder->getSearchType() != 'repository') { ?>
                            <a href="<?php echo $linkUrl ?>">
                                <?php echo $rowTitleShort ?>
                            </a>
                        <?php } else { ?>
                            <a href="<?php echo $linkUrl ?>" target="_blank">
                                <?php echo $rowTitleShort ?>
                            </a>
                        <?php } ?>

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
                        $fieldDisplayValue = strlen(trim($item[$key])) > 350 ? substr(trim($item[$key]), 0, 350) . '... (More In Details)' : trim($item[$key]);
                        $fieldTitleTooltip = strlen(trim($item[$key])) > $maxLen ? trim($fieldDisplayValue) : '';
                        $fieldTitleShort = process_strong_highlight(strlen(trim($item[$key])) > $maxLen ? substr(trim($item[$key]), 0, $maxLen) . '...' : trim($item[$key]));
                        ?>
                        <p class="result-field">
                            <em><?php echo trim($key) ?>:</em>
                            <span data-html="true" title="<?php echo $fieldTitleTooltip ?>" data-toggle="tooltip" data-placement="bottom">
                                <?php echo $fieldTitleShort ?>
                            </span>
                        </p>
                    <?php } ?>
                </li>
            <?php } ?>
        </ol>
        <?php
    } else {
        ?>
        <div class="alert alert-warning">
            <strong>NO ITEMS FOUND!</strong>
            <p>The following term was not found in bioCADDIE:
                <strong class="text-danger"><?php echo $searchBuilder->getQuery(); ?></strong> with <strong class="text-danger"><?php echo $_GET['access'] ?></strong> accessibility.
            </p>
        </div>
        <?php
    }
}

function process_strong_highlight($field) {
    if (strpos($field, '<strong>') !== false) {
        $start = substr_count($field, '<strong>');
        $end = substr_count($field, '</strong>');
        if ($start > $end) {
            $last = 11 - strpos(substr($field, -11, 11), "</");
            $field = substr($field, 0, strlen($field) - $last);
            $field = $field . '</strong>';
        }
    }
    return $field;
}
?>


