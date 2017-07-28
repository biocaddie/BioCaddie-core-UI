<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';
require_once dirname(__FILE__) . '/../Utilities.php';

class NiddkcrRepository extends RepositoryBase
{

    public $repoShowName = 'NIDDKCR';
    public $wholeName = 'NIDDK&nbsp;Central&nbsp;Repository';
    public $id = '0018';
    public $source = "https://www.niddkrepository.org/";
   // public $searchFields = ['dataset.ID', 'dataset.title', 'dataset.types','dataset.creators','disease.name', 'treatment.name','taxonomicInformation.name'];
    public $facetsFields = ['treatment.name.raw','disease.name.raw'];
    public $facetsShowName = [
        'treatment.name.raw' => 'Treatment',
        'disease.name.raw' => 'Disease'
    ];
    public $index = 'niddkcr';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.types','treatment.name'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.ID' => 'ID',
        'dataset.types'=>'Type',
        'treatment.name'=>'Treatment'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Disease', 'Treatment'];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'disease.name',  'treatment.name'];

    public $source_main_page = "https://www.niddkrepository.org/";
    public $sort_field = '';
    public $description = 'The NIDDK Central Repository stores biosamples, genetic and other data collected in designated NIDDK-funded clinical studies. The purpose of the NIDDK Central Repository is to expand the usefulness of these studies by allowing a wider research community to access data and materials beyond the end of the study.';
    public function getDisplayItemView($rows)
    {

        $search_results = parent::getDisplayItemView($rows);

        for($i=0;$i<sizeof($search_results['datasetDistributions']);$i++){
            if($search_results['datasetDistributions'][$i][0]=='accessURL' and strpos($search_results['datasetDistributions'][$i][1],'<a class="external')>0){
                $search_results['datasetDistributions'][$i][1]=str_replace('https://clinicaltrials.gov/ct2/show/results/ClinicalTrials.gov','',$search_results['datasetDistributions'][$i][1]);
                $search_results['datasetDistributions'][$i][2]='';
            }
        }
        $landingpage=$search_results['title'][2];
        $items = explode('/',$landingpage);
        $newitems = [$items[0],$items[1],$items[2],$items[3],strtolower($items[5])];
        $newlandingpage=implode('/',$newitems);
        $search_results['title'][2]=$newlandingpage;
        //$search_results['title'][1]=$newlandingpage;
        $search_results['access'][0][1]=$newlandingpage;
        $search_results['access'][0][2]=$newlandingpage;
        for($i=0;$i<sizeof($search_results['datasetDistributions']);$i++){
            if($search_results['datasetDistributions'][$i][0]=='accessURL' and $search_results['datasetDistributions'][$i][1]==$landingpage){
                $search_results['datasetDistributions'][$i][1]=$newlandingpage;
                $search_results['datasetDistributions'][$i][2]=$newlandingpage;
            }
    }


        return $search_results;
    }
}
?>