<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitemorphobankRepository extends RepositoryBase {

    public $repoShowName = 'MorphoBank';
    public $wholeName = '';
    public $id = '0039';
    public $source = "http://www.morphobank.org/";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.creators','dataset.types','attributes.description'];
    public $facetsFields = ['dataset.types'];
    public $facetsShowName = [
        'dataset.types'=>'Types',

    ];
    public $index = 'datacitemorphobank';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Creators','Released Date' ];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.creators','dataset.dateReleased' ];

    public $source_main_page = "http://www.morphobank.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'MorphoBank is a web application providing an online database and workspace for evolutionary research, specifically systematics (the science of determining the evolutionary relationships among species). One can think of MorphoBank as two databases in one: one that permits researchers to upload images and affiliate data with those images (labels, species names, etc.) and a second database that allows researchers to upload morphological data and affiliate it with phylogenetic matrices.';

}

?>