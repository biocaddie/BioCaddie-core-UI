<?php
require_once dirname(__FILE__) . '/../../Model/SimilarDatasetsService/Pdb.php';
require_once dirname(__FILE__) . '/../../Model/SimilarDatasetsService/Lincs.php';
require_once dirname(__FILE__) . '/../../Model/SimilarDatasetsService/Gemma.php';
require_once dirname(__FILE__) . '/../../Model/SimilarDatasetsService.php';

function partialSimilarDatasets($service)
{
    $repoID = $service->getRepositoryId();

    switch ($repoID) {

        case "0002": // PDB
            $pdbSimilarDatasetsService = new PDBSimilarData;
            $similarDatasets = $pdbSimilarDatasetsService->getPDBDataset($service->getItemId(), 6);

	    break;

        case "0004": // LINCS
            $lincsSimilarDatasetsService = new LINCSSimilarData;
            $similarDatasets = $lincsSimilarDatasetsService -> getLincsDataset($service->getItemId(), 6);
	    break;

        case "0005": // Gemma
            $gemmaSimilarDatasetsService = new GemmaSimilarData;
            $similarDatasets = $gemmaSimilarDatasetsService -> getGemmaDataset($service->getItemId(), 6);
	    break;
        default:
            $similarDatasetsService = new SimilarData;
            $similarDatasets = $similarDatasetsService -> getSimilarDataset($service->getItemId(), 6,$repoID);
            break;

    }


    if (sizeof($similarDatasets) > 0) {
        ?>
        <div class="panel panel-danger" id="similar-datasets">
            <div class="panel-heading"><i class="fa fa-chevron-up"></i> <strong>Similar Datasets</strong></div>
            <div class="panel-body">
                <div class="table-responsive ">
                    <div class="list-group">
                        <?php foreach ($similarDatasets as $item): 
			 switch ($repoID) {
				case "0002":?>
                            <a href="<?php echo "http://www.rcsb.org/pdb/explore/explore.do?structureId=" . $item->get_pdbid(); ?>"
                               target="_blank" class="list-group-item">
                                <p class="list-group-item-heading">
                                    <strong><?php echo substr($item->get_structureTitle(), 0, 75); ?>...</strong></p>

                                <p class="list-group-item-text text-right"><?php echo $item->get_pdbid() . ", " . $item->get_experimentalTechnique(); ?></p>
                            </a>
                        <?php break;
				case "0004":?>
				<a href="<?php echo "http://datamed.biocaddie.org/dev/biocaddie-ui/display-item.php?repository=0004&idName=ID&id=" . $item->getLincsId(); ?>"
                               target="_blank" class="list-group-item">
                                <p class="list-group-item-heading">
                                    <strong><?php echo substr($item->getDataItemTitle(), 0, 75); ?>...</strong></p>

                                <p class="list-group-item-text text-right"><?php echo $item->getDataResourceName().", Cell Name: " . $item->getCellName(); ?></p>
                            </a>
			<?php break;
                 case "0005":?>
                     <a href="<?php echo "http://datamed.biocaddie.org/dev/biocaddie-ui/display-item.php?repository=0005&idName=ID&id=" . $item->getGemmaId(); ?>"
                        target="_blank" class="list-group-item">
                         <p class="list-group-item-heading">
                             <strong><?php echo substr($item->getDataItemTitle(), 0, 75); ?>...</strong></p>

                         <p class="list-group-item-text text-right"><?php echo $item->getDataItemTypes()[0]; ?></p>
                     </a>
                     <?php break;
             default:
		 if($item->getDatasetTitle() == NULL){
                     break;
                 }else {

		?>
                 <a href="<?php echo "http://".$_SERVER['SERVER_NAME']."/biocaddie-ui/display-item.php?repository=".$service->getRepositoryId()."&idName=ID&id=" . $item->getDatasetID(); ?>"
                    target="_blank" class="list-group-item">
                     <p class="list-group-item-heading">
                         <strong><?php echo substr($item->getDatasetTitle(), 0, 75); ?>...</strong></p>
                 </a>
                        <?php
                }
          break;}endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
} ?>
