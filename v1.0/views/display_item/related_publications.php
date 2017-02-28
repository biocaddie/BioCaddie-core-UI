<?php
require_once dirname(__FILE__) . '/../../search/PubmedRelatedPublicationService.php';

function partialRelatedPublications($service) {
    $result = $service->getSearchResults();
    if (isset($result['dataItem'])) {
        $query = $result['dataItem']['title'];
    }elseif(isset($result['dataItem.title'])){
        $query = $result['dataItem.title'];
    }elseif(isset($result['dataset.title'])){
        $query = $result['dataset.title'];
    }elseif(isset($result['Dataset.briefTitle'])){
        $query = $result['Dataset.briefTitle'];
    }
    else {
        $query = $result['title'];
    }

    $query = trim(strip_tags($query));


    $pubmedPublication = new PubmedPublication();
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
                        <a href="<?php echo "http://www.ncbi.nlm.nih.gov/pubmed/" . $item->get_pmid(); ?>" target="_blank" class="list-group-item">
                            <p class="list-group-item-heading"><strong><?php echo substr($item->get_title(), 0, 72); ?>...</strong></p>
                            <p class="list-group-item-text text-right"><?php echo $item->get_source() . ", " . $item->get_pubDate(); ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php }

} ?>