<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DataciteukdaRepository extends RepositoryBase {

    public $repoShowName = 'UKDA';
    public $wholeName = 'UK&nbsp;Data&nbsp;Archive';
    public $id = '0050';
    public $source = "http://www.data-archive.ac.uk/";

    public $facetsFields = ['dataset.types','dataset.refinement'];
    public $facetsShowName = [
        'dataset.types'=>'Types',
        'dataset.refinement'=>'Refinement'
    ];
    public $index = 'dataciteukda';
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


    public $source_main_page = "http://www.data-archive.ac.uk/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'We acquire, curate and provide access to the UK\'s largest collection of social and economic data.';

}

?>