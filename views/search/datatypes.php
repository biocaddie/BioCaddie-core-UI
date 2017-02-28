<?php
/*
 * Display data types filter panel on the search.php page
 *
 * input: an object of ConstructSearchView class
 * @param
 *      $this->dataTypeFilter: array(array(string))
 * */

function partialDatatypes($searchView)
{
    $datatypes = $searchView->getDataTypeFilter();
    ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong> Data Types</strong>
        </div>
        <div class="panel-body">
            <ul class="no-disk">
                <?php
                if ($searchView->getSearchBuilder()->getTotalRows() > 0) {
                    $i=0;
                    foreach($datatypes as $key=>$row){
                        ?>
                        <li>
                            <a id="<?php echo "datatype_".$i?>" target="_parent" href="<?php echo $searchView->getUrlByDatatype($key);?>">
                            <!--checkbox-->
                            <?php if ($row['selected'] == true): ?>
                                <i class="fa fa-check-square"></i>
                            <?php else: ?>
                                <i class="fa fa-square-o"></i>
                            <?php endif; ?>

                            <!--label-->
                            <?php echo $key; ?>

                            <!--number of results-->
                            <?php echo '(' . number_format($row['rows']) . ')'; ?>
                            </a>
                        </li>
                        <?php
                        $i++;
                    } // end of foreach
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
}
?>