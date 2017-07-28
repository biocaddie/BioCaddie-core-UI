<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DataciteccdcRepository extends RepositoryBase {

    public $repoShowName = 'CCDC';
    public $wholeName = 'The&nbsp;Cambridge&nbsp;Crystallographic&nbsp;Data&nbsp;Centre';
    public $id = '0071';
    public $source = "http://www.ccdc.cam.ac.uk/";

    public $facetsFields = ['dataset.types','dataset.refinement'];
    public $facetsShowName = [
        'dataset.types'=>'Types',
        'dataset.refinement'=>'Refinement'
    ];
    public $index = 'dataciteccdc';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID','Released Date','Creators'];
    public $searchRepoField = ['dataset.title', 'dataset.ID','dataset.dateReleased','dataset.creators' ];


    public $source_main_page = "http://www.ccdc.cam.ac.uk/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The Cambridge Crystallographic Data Centre (CCDC) is the home of small molecule crystallography data and is a leader in software for pharmaceutical discovery, materials development, research and education.';

}

?>