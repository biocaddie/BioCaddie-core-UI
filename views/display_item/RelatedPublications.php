<?php
require_once dirname(__FILE__) . '/../../Model/PubmedPublication.php';

function partialRelatedPublications($service) {
    $result = $service->getSearchResults();
    $pubmedPublication = new PubmedPublication();
    $query = $pubmedPublication->extractQuery($result);
    $itemArray = $pubmedPublication->getPublication($query, 5);

    if (sizeof($itemArray) > 0) {
        ?>
        <div id="related-publications" class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-chevron-up"></i> Related Publications</h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php foreach ($itemArray as $item): ?>
                        <a href="<?php echo "http://www.ncbi.nlm.nih.gov/pubmed/" . $item->getPmid(); ?>" target="_blank" class="list-group-item">
                            <p class="list-group-item-heading"><strong><?php echo substr($item->getTitle(), 0, 72); ?>...</strong></p>
                            <p class="list-group-item-text text-right"><?php echo $item->getSource() . ", " . $item->getPubDate(); ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php }

} ?>