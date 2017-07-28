<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class NatureDataRepository extends RepositoryBase {

    public $repoShowName = 'Scientific Data';
    public $wholeName = 'Scientific Data';
    public $id = '0065';
    public $source = "http://www.nature.com/sdata/";

    public $facetsFields = ['dataset.types.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Dataset Types',
    ];
    public $index = 'naturedata';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Date Released',

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID','Date','Analysis Description'];
    public $searchRepoField = ['dataset.title', 'dataset.ID','dataset.dateReleased','dataAnalysis.description' ];


    public $source_main_page = "http://www.nature.com/sdata/";
    public $sort_field = 'dataset.dateReleased';
    public $description ='Scientific Data is a peer-reviewed, open-access journal for descriptions of 
    scientifically valuable datasets, and research that advances the sharing and reuse of scientific data.';



    public function getDisplayItemView($rows)
    {

        $search_results = parent::getDisplayItemView($rows);

        $input = $search_results['taxonomicInformation'];
        $input = array_map("unserialize", array_unique(array_map("serialize", $input)));
        $search_results['taxonomicInformation']=$input;
        $input = $search_results['anatomicalPart'];
        $input = array_map("unserialize", array_unique(array_map("serialize", $input)));
        $search_results['anatomicalPart']=$input;

        return $search_results;
    }
}

?>