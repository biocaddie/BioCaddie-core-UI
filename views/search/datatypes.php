<?php

function partialDatatypes($searchBuilder) {
    //sort in number order
    $nums = array();
    $datatypes = $searchBuilder->getDatatypes();

    foreach ($datatypes as $key => $row)
    {
        $nums[$key] = $row['rows'];
        //for put the clinical trials to the last
        if($key=="Clinical Trials"){
            $nums[$key] = -1*$row['rows'];
        }
    }
    array_multisort($nums, SORT_DESC, $datatypes);
    if ($searchBuilder->getTotalRows() > 0) {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong> Data Types</strong>
            </div>
            <div class="panel-body">
                <ul class="no-disk">
                    <?php
                        foreach ($datatypes as $datatypeName => $details):?>
                            <?php if($details['rows']==0){
                                  continue;
                            }?>
                        <li>
                            <a href="<?php echo $searchBuilder->getUrlByDatatype($datatypeName);?>">

                                <?php if($datatypeName=="Clinical Trials"):?>
                                  <div style="color:gray">
                                <?php endif;?>
                               <?php if ($details['selected'] == true): ?>
                                    <i class="fa fa-check-square"></i>
                                <?php else: ?>
                                    <i class="fa fa-square-o"></i>
                                <?php endif; ?>
                                <?php echo $datatypeName; ?>
                                <?php echo '(' . $details['rows'] . ')'; ?>
                                <?php if($datatypeName=="Clinical Trials"):?>
                                    </div>
                                <?php endif;?>
                            </a> 
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }
}
?>