<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitecxidbRepository extends RepositoryBase {

    public $repoShowName = 'CXIDB';
    public $wholeName = 'Coherent&nbsp;X-ray&nbsp;Imaging&nbsp;Data&nbsp;Bank';
    public $id = '0047';
    public $source = "http://cxidb.org/";

    public $facetsFields = ['dataset.types','dataset.refinement'];
    public $facetsShowName = [
        'dataset.types'=>'Types',
        'dataset.refinement'=>'Refinement'
    ];
    public $index = 'datacitecxidb';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Creators','Released Date','Description'];
    public $searchRepoField = ['dataset.title', 'dataset.creators','dataset.dateReleased','attributes.description' ];


    public $source_main_page = "http://cxidb.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'Enabling groundbreaking biomedical research via open access to high-quality simulation tools, accurate models, and the people behind them..
';

}

?>