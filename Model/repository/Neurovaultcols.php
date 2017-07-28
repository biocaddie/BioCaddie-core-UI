<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class NeurovaultcolsRepository extends RepositoryBase {

    public $repoShowName = 'NeuroVault:Cols';
    public $wholeName = 'NeuroVault&nbsp;Cols';
    public $id = '0035';
    public $source = "http://neurovault.org/";
    //public $searchFields = ['dataset.ID','dataset.creators','dataset.title','dataset.description','dataset.types','taxonomicInformation.name','dataType.method'];
    public $facetsFields = ['dataType.method.raw'];
    public $facetsShowName = [
        'dataType.method.raw'=>'Method',

    ];
    public $index = 'neurovaultcols';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title','dataset.ID', 'dataset.description', 'dataType.method','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID'=>'ID',
        'dataset.description'=>'Description',
        'dataType.method'=>'Method',
        'dataset.dateReleased'=>'Release Date'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Description', 'Method','Released Date' ];
    public $searchRepoField = ['dataset.title', 'dataset.description', 'dataType.method','dataset.dateReleased' ];


    public $source_main_page = "http://neurovault.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'A place where researchers can publicly store and share unthresholded statistical maps, parcellations, and atlases produced by MRI and PET studies.';

}

?>