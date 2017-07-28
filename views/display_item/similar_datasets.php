<?php
require_once dirname(__FILE__) . '/../../Model/SimilarDatasetsService.php';

function partialSimilarDatasets($service)
{
    $repoID = $service->getRepositoryId();
    $similarDatasetsService = new SimilarData;
    $similarDatasets = $similarDatasetsService -> getSimilarDataset($service->getItemId(), 6,$repoID);


    if (sizeof($similarDatasets) > 0) {
        ?>
        <div class="panel panel-danger" id="similar-datasets">
            <div class="panel-heading"><i class="fa fa-chevron-up"></i> <strong>Similar Datasets</strong></div>
            <div class="panel-body">
                <div class="table-responsive ">
                    <div class="list-group">
                        <?php foreach ($similarDatasets as $item):
                            if ($item->getDatasetTitle() == NULL) {
                            } else {
                                ?>
                                <a href="<?php echo "http://" . $_SERVER['SERVER_NAME'] . "/display-item.php?repository=" . $service->getRepositoryId() . "&idName=ID&id=" . $item->getDatasetID(); ?>"
                                   target="_blank" class="list-group-item">
                                    <p class="list-group-item-heading">
                                        <strong><?php echo substr($item->getDatasetTitle(), 0, 75); ?>...</strong></p>
                                </a>
                                <?php
                            }
                        endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }else{

    }
} ?>
