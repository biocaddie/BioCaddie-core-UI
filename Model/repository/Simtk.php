<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class SimtkRepository extends RepositoryBase {

    public $repoShowName = 'SimTK';
    public $wholeName = '';
    public $id = '0046';
    public $source = "https://simtk.org/";

    public $facetsFields = ['datatype.platform.raw','dataset.keyword.raw'];
    public $facetsShowName = [
        'datatype.platform.raw'=>'Platform',
        'dataset.keyword.raw'=>'Keyword'
    ];
    public $index = 'simtk';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.description' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.description'=>'Description'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID','Description'];
    public $searchRepoField = ['dataset.title', 'dataset.ID','dataset.description' ];


    public $source_main_page = "https://simtk.org/";
    public $sort_field = '';
    public $description = 'SimTK is a free project-hosting platform for the biomedical computation community that: Enables you to easily share your software, data, and models;
    Tracks the impact of the resources you share;
    Provides the infrastructure so you can support and grow a community around your projects;
    Connects you and your project to thousands of researchers working at the intersection of biology, medicine, and computations.';

}

?>