<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DataverseRepository extends RepositoryBase {

    public $repoShowName = 'Dataverse';
    public $wholeName = 'Dataverse&nbsp;Network&nbsp;Project';
    public $id = '0012';
    public $source = "http://thedata.org/";
    //public $searchFields = ['dataset.ID','publication.description','dataset.title','dataset.description','person.name'];
    public $facetsFields = ['person.name.raw'];
    public $facetsShowName = [
        'person.name.raw'=>'Person',

    ];
    public $index = 'dataverse';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.description', 'person.name','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.description'=>'Description',
        'person.name'=>'Person',
        'dataset.dateReleased'=>'Release Date'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Description', 'Person','Released Date' ];
    public $searchRepoField = ['dataset.title', 'dataset.description', 'person.name','dataset.dateReleased' ];


    public $source_main_page = "http://thedata.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'A Dataverse repository is the software installation, which then hosts multiple dataverses. Each dataverse contains datasets, and each dataset contains descriptive metadata and data files (including documentation and code that accompany the data). As an organizing method, dataverses may also contain other dataverses.';

    public function getDisplayItemView($rows)
    {

        $search_results = parent::getDisplayItemView($rows);
        $search_results['title'][2]=$rows['dataset']['downloadURL'];
        return $search_results;
    }





}

?>