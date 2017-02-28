<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class TcgaRepository extends RepositoryBase
{

    public $repoShowName = 'GDC';
    public $wholeName = 'Genomic&nbsp;Data&nbsp;Commons';
    public $id = '0026';
    public $index = 'tcga';
    public $type = 'dataset';

    public $source = "https://gdc-api.nci.nih.gov/files";
   // public $searchFields = ["dataset.ID", 'dataset.types', 'dataType.method', 'dataType.information', 'datasetDistributions.format'];

    public $facetsFields = ['dataType.method.raw', 'dataset.types.raw'];
    public $facetsShowName = [
        'dataType.method.raw' => 'Method',
        'dataset.types.raw' => 'Datasets Type'];


    public $searchPageField = [ 'dataset.ID','dataset.types','dataType.method','dataType.information'];
    public $searchPageHeader = [
        'dataset.types' => 'Types',
        'dataset.ID' => 'ID',
        'dataType.method' => 'Method',
        'dataType.information' => 'Information'
    ];

    public $searchRepoHeader = [ 'ID','Types','Date Created'];//,'Citation count'
    public $searchRepoField = [ 'dataset.ID','dataset.types', 'dataset.dateCreated'];//'citation.count'


    public $source_main_page = 'https://gdc.cancer.gov/';
    public $sort_field = 'dataset.dateCreated';
    public $description = 'The NCI\'s Genomic Data Commons (GDC) provides the cancer research community with a unified data repository that enables data sharing across cancer genomic studies in support of precision medicine.';

    public function getDisplayItemView($rows)
    {

        $search_results = parent::getDisplayItemView($rows);
        unset($search_results['dataRepository']);
        for($i=0;$i<sizeof($search_results['organization']);$i++){
            if($search_results['organization'][$i][0]==='homePage'){
                $search_results['organization'][$i][2] = $search_results['organization'][$i][1];
            }
        }
        return $search_results;
    }

}

?>
