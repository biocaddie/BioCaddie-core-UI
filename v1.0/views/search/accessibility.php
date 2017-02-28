<?php

function partialAccessibility($searchBuilder) {
    //sort in number order
    $nums = array();
    $access = $searchBuilder->getAccess();

    $accessLabel = "all";

    if(isset($_GET['access'])){
        $accessLabel = $_GET['access'];
    }


    foreach ($access as $key => $row)
    {
        $nums[$key] = $row['rows'];
    }
    array_multisort($nums, SORT_DESC, $datatypes);
    if ($searchBuilder->getTotalRows() > 0) {
        ?>
        <div class="pull-left" >
            <span>Accessibility:</span>
            <div class="dropdown">
                <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdown-sort"
                        data-toggle="dropdown">
                    <?php echo ucwords($accessLabel); ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">

                    <?php
                    foreach ($access as $accessName => $details):?>
                        <?php if($details['rows']==0){
                            continue;
                        }?>
                        <li>
                            <a href="<?php echo $searchBuilder->getUrlByAccessibility($accessName);?>">
                                <?php echo ucwords($accessName); ?>
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