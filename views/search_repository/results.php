<?php

function partialResults($searchBuilder,$searchRepoView) {
    if ($searchBuilder->getSelectedTotalRows() > 0) {
        ?>
        <table class="table table-striped table-condensed search-repo">
            <thead>
                <tr>
                    <?php foreach ($searchRepoView->getRepoHeader() as $header): ?>
                        <th><?php echo $header; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>  
                <?php
                foreach ($searchRepoView->getSearchResults() as $row) {
                    $checkBoxShown = false;
                    ?>
                    <tr>
                        <?php foreach ($row as $field) { ?>
                            <td>
                                <?php
                                if ($checkBoxShown == false) {
                                    $checkBoxShown = true;
                                    $shareLink = explode("&", substr($field, strpos($field, "?") + 1, strpos($field, ">",20) - strpos($field, "?")-1));
                                    ?>
                                    <input name="share-check" type="checkbox" value="<?php echo 'share-item-' . $shareLink[0] . '&' . @$shareLink[1] ?>" />
                                    <?php
                                }
                                if (!is_array($field)) {
                                    echo $field;
                                } else {
                                    foreach ($field as $val):
                                        if (!is_array($val)) {
                                            echo $val . '<br>';
                                        } else {
                                            $i = 0;
                                            foreach ($val as $v) :
                                                if ($i < 5) {
                                                    echo $v . '<br>';
                                                }
                                                $i++;
                                            endforeach;
                                        }
                                    endforeach;
                                }
                                ?></td>
                            <?php
                        }
                        ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else {
        require_once dirname(__FILE__) . '/no_item_found.php';
    }
}
?>