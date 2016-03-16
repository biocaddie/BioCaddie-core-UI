<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 12/21/15
 * Time: 3:24 PM
 */

require_once dirname(__FILE__) . '/../../search/PubmedGrantService.php';

function partialGrant($service) {
    $result = $service->getSearchResults();
    $query = $service->getQueryString();
    if (isset($result['citation.PMID'])) {
        $pmid= substr($result['citation.PMID'],5);
    } elseif(isset($result['publication.pmid'])) {
        $pmid = substr($result['publication.pmid'], 5);
    }
    elseif(isset($result['pmid'])) {
        $pmid = substr($result['pmid'], 5);
    }
    else
    {
        return ;
    }
    $PubmedGrantService = new PubmedGrantService();
    $itemArray = $PubmedGrantService->getPubmedGrant($pmid);

    if (sizeof($itemArray) > 0) {
        ?>
        <div id="support-grant" class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-chevron-up"></i><a class="hyperlink" user="result-heading" href="grant_details.php?query=<?php echo $query;?>&pmid=<?php echo $pmid;?>">Grant Support</a></h3>
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


