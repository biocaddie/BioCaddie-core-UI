<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class ImmportRepository extends RepositoryBase {

    public $repoShowName = 'ImmPort';
    public $wholeName = 'ImmPort';
    public $id = '0061';
    public $source = "http://www.immport.org";

    public $facetsFields = ['dataset.keywords.raw','dataset.refinement'];
    public $facetsShowName = [
        'dataset.keywords.raw'=>'Keywords',
        'dataset.refinement'=>'Refinement'
    ];
    public $index = 'immport';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.description' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.description'=>'Description'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Creators','ID','Released Date','Description'];
    public $searchRepoField = ['dataset.title', 'creators.name','dataset.ID','dataset.dateReleased','dataset.description' ];


    public $source_main_page = "http://www.immport.org";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'ImmPort is funded by the NIH, NIAID and DAIT in support of the NIH mission to share data with the public. Data shared through ImmPort has been provided by NIH-funded programs, other research organizations and individual scientists ensuring these discoveries will be the foundation of future research.
    ';
    public function getDisplayItemView($rows)
    {
        $search_results = parent::getDisplayItemView($rows);
        /*for($i=0;$i<sizeof($search_results['datasetDistributions']);$i++){
            if($search_results['datasetDistributions'][$i][0]=='accessURL'){
                $search_results['access'][0]=['landingPage',$search_results['datasetDistributions'][$i][1],$search_results['datasetDistributions'][$i][2]];
                $search_results['title'][2]=$search_results['datasetDistributions'][$i][2];
            }
        }*/
        for($i=0;$i<sizeof($search_results['license']);$i++){
            if($search_results['license'][$i][0]=='extraProperties'){
                if($search_results['license'][$i][1]=="Array"){
                    $search_results['license'][$i][1]=$rows['license'][0]["extraProperties"][0]['category'];
                    $search_results['license'][$i][2]=$rows['license'][0]["extraProperties"][0]['value'];
                }
            }
        }

        return $search_results;
    }



}

?>