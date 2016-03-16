<?php

function partialResults($searchBuilder) {
    if ($searchBuilder->getTotalRows() > 0) {
        $maxLen = 160;
        $index = 0;
        ?>
        <table class="table table-striped table-condensed search-repo">
            <thead>
                <tr>
                    <?php foreach ($searchBuilder->getSearchHeaders() as $header): ?>
                        <th><?php echo $header; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>  
                <?php
                foreach ($searchBuilder->getSearchResults() as $row) {
                    $checkBoxShown = false;
                    ?>
                    <tr>
                        <?php foreach ($row as $field) { ?>
                            <td>
                                <?php if ($checkBoxShown == false) { ?>
                                    <input name="share-check" type="checkbox" value="<?php echo $index ?>" />
                                    <?php
                                    $checkBoxShown = true;
                                }
                                ?>
                                <?php
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
                        $checkBoxShown = false;
                        ?>
                    </tr>
                    <?php
                    $index++;
                }
                ?>
            </tbody>
        </table>
    <?php } else { ?> 
        <div class="alert alert-warning">
            <strong>NO ITEMS FOUND!</strong> 
            <p>The following term was not found in bioCADDIE: <strong class="text-danger"><?php echo $searchBuilder->getQuery(); ?></strong></p>
        </div>
        <?php
    }
}
?>