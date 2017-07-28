<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class CvrgRepository extends RepositoryBase
{

    public $repoShowName = 'CVRG';
    public $wholeName = 'CardioVascular&nbsp;Research&nbsp;Grid';
    public $id = '0011';
    public $source = "https://eddi.cvrgrid.org/handle/";

   // public $searchFields = ['dataset.ID','dataset.creators', 'dataset.description','dataset.title','dataset.type' ];
    public $facetsFields = ['dataset.types'];
    public $facetsShowName = [
        'dataset.types' => 'Dataset Type'
    ];
    public $index = 'cvrg';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.types', 'dataset.description'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.types' => 'Type',
        'dataset.description' => 'Description'

    ];


    //search-repository page
    public $searchRepoHeader = ['Title', 'ID','Date Released', 'Description'];
    public $searchRepoField = ['dataset.title', 'dataset.ID','dataset.dateReleased',  'dataset.description'];


    public $link_field = 'dataset.title';
    public $source_main_page = 'http://cvrgrid.org/';
    public $sort_field = 'dataset.dateReleased';
    public $description='The CardioVascular Research Grid (CVRG) project is creating an infrastructure for sharing cardiovascular data and data analysis tools. CVRG tools are developed using the Software as a Service model, allowing users to access tools through their browser, thus eliminating the need to install and maintain complex software.';




}

?>