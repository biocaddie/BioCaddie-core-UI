<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 6/3/16
 * Time: 3:17 PM
 */

function partialAuthorization($searchBuilder) {
    //sort in number order
    $nums = array();
    $auth = $searchBuilder->getAuth();

    $authLabel = "all";

    if(isset($_GET['auth'])){
        $authLabel = $_GET['auth'];
    }

    foreach ($auth as $key => $row)
    {
        $nums[$key] = $row['rows'];
    }
    array_multisort($nums, SORT_DESC, $datatypes);
    if ($searchBuilder->getTotalRows() > 0) {
        ?>
        <div class="pull-left" >
            <span>Authorization: </span>
            <div class="dropdown">
                <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdown-sort"
                        data-toggle="dropdown">
                    <?php echo ucwords($authLabel); ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">

                    <?php
                    foreach ($auth as $authName => $details):?>
                        <?php if($details['rows']==0){
                            continue;
                        }?>
                        <li>
                            <a href="<?php echo $searchBuilder->getUrlByAuth($authName);?>">
                                <?php echo ucwords($authName); ?>
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