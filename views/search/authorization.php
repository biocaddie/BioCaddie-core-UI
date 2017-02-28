<?php
/**
 * Created by PhpStorm.
 * User: rliu1
 * Date: 6/3/16
 * Time: 3:17 PM
 */

function partialAuthorization($searchView) {
    $auth = $searchView->getAuthorizationFilter();
    ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong> Authorization</strong>
        </div>
        <div class="panel-body">
            <ul class="no-disk">
                <?php
                if ($searchView->getSearchBuilder()->getTotalRows() > 0) {
                    $i=0;
                    foreach($auth as $key=>$row){
                        ?>
                        <li>
                            <a id="<?php echo "auth_".$i?>" target="_parent" href="<?php echo $searchView->getUrlByAuth($key);?>">
                                <!--checkbox-->
                                <?php if ($row['selected'] == true): ?>
                                    <i class="fa fa-check-square"></i>
                                <?php else: ?>
                                    <i class="fa fa-square-o"></i>
                                <?php endif; ?>

                                <!--label-->
                                <?php echo ucfirst($key); ?>

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