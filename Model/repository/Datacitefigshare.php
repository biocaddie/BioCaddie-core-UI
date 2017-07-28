<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitefigshareRepository extends RepositoryBase {

    public $repoShowName = 'Figshare';
    public $wholeName = '';
    public $id = '0070';
    public $source = "http://www.figshare.com";
   // public $searchFields = ['dataset.ID','dataset.title','dataset.creators','dataset.types','attributes.description'];
    public $facetsFields = ['dataset.types'];
    public $facetsShowName = [
        'dataset.types'=>'Types',

    ];
    public $index = 'datacitefigshare';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased','attributes.description' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date',
        'attributes.description'=>'Description'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Description','Released Date' ];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'attributes.description','dataset.dateReleased' ];

    public $source_main_page = "http://www.figshare.com";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'figshare is a repository where users can make all of their research outputs available in a citable, shareable and discoverable manner';

}

?>