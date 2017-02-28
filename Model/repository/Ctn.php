<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class CtnRepository extends RepositoryBase
{

    public $repoShowName = 'CTN';
    public $wholeName = 'Clinical&nbsp;Trials&nbsp;Network';
    public $id = '0015';
    public $source = "";

   // public $searchFields = ['dataset.ID', 'dataset.title', 'dataset.description', 'dataset.keywords', 'organism.name', 'dataset.creators'];
    public $facetsFields = ['dataset.keywords.raw'];
    public $facetsShowName = [
        'dataset.keywords.raw' => 'Keywords',
    ];
    public $index = 'ctn';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.keywords', 'dataset.description'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.keywords' => 'Keywords',
        'dataset.description' => 'Description',
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Date Released',  'Description'];
    public $searchRepoField = ['dataset.title', 'dataset.dateReleased',  'dataset.description'];


    public $source_main_page = 'http://ctndisseminationlibrary.org/';
    public $sort_field = 'dataset.dateReleased';
    public $description = 'A repository of data from completed CTN clinical trials to be distributed to investigators in order to promote new research, encourage further analyses, and disseminate information to the community. Secondary analyses produced from data sharing multiply the scientific contribution of the original research.';

    public function getDisplayItemView($rows)
    {

        $search_results = parent::getDisplayItemView($rows);
        for($i=0;$i<sizeof($search_results['datasetDistributions']);$i++){
            if($search_results['datasetDistributions'][$i][0]=='accessURL'){
                $landingpage = $search_results['datasetDistributions'][$i][1];
                $search_results['title'][2]=$landingpage;
                $search_results['access'][0][1]=$landingpage;
                $search_results['access'][0][2]=$landingpage;
                break;
            }
    }
        unset($search_results['datasetDistributions']);
        return $search_results;
    }
}


?>