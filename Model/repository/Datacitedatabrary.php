<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitedatabraryRepository extends RepositoryBase {

    public $repoShowName = 'Databrary';
    public $wholeName = 'Databrary';
    public $id = '0060';
    public $source = "http://databrary.org/";

    public $facetsFields = ['dataset.types.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.refinement.raw'=>'Refinement'
    ];
    public $index = 'datacitedatabrary';
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


    public $source_main_page = "http://databrary.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'Databrary is a video data library for developmental science. Share videos, audio files, and related metadata. Discover more, faster.
    ';

}

?>