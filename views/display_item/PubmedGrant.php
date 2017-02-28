<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 12/21/15
 * Time: 3:24 PM
 */

require_once dirname(__FILE__) . '/../../Model/PubmedGrantService.php';

function partialGrant($service) {
    $PubmedGrantService = new PubmedGrantService();
    $pmid = $PubmedGrantService->extractPmid($service);
    $itemArray = $PubmedGrantService->getPubmedGrant($pmid);
    if (sizeof($itemArray) > 0) {
        ?>
        <div id="support-grant" class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-chevron-up"></i><a class="hyperlink" user="result-heading" href="grant_details.php?pmid=<?php echo $pmid;?>">Grant Support</a></h3>
            </div>
            <div class="panel-body">
                <ul class="no-disk">
                    <?php foreach ($itemArray as $item): ?>
                        <li class="list-group-item-text"><?php echo $item; ?></li><br>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php }
}

?>


