<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitepeerjRepository extends RepositoryBase {

    public $repoShowName = 'PeerJ';
    public $wholeName = '';
    public $id = '0042';
    public $source = "https://peerj.com/";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.creators','dataset.types','attributes.description'];
    public $facetsFields = ['dataset.types'];
    public $facetsShowName = [
        'dataset.types'=>'Types',

    ];
    public $index = 'datacitepeerj';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased','dataset.creators'];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date',
        'dataset.creators'=>'Creators'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Creators','Released Date' ];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.creators','dataset.dateReleased' ];

    public $source_main_page = "https://peerj.com/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'PeerJ publishes the world\'s scientific knowledge through open access licensing. 2,751 peer-reviewed articles and 3,261 preprints since 2013.';

}

?>