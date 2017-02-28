<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitezenodoRepository extends RepositoryBase {

    public $repoShowName = 'Zenodo';
    public $wholeName = '';
    public $id = '0043';
    public $source = "https://zenodo.org/";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.creators','dataset.types','attributes.description'];
    public $facetsFields = ['dataset.types'];
    public $facetsShowName = [
        'dataset.types'=>'Types',

    ];
    public $index = 'datacitezenodo';
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
    public $searchRepoHeader = ['Title', 'ID', 'Creators','Released Date' ];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.creators','dataset.dateReleased' ];


    public $source_main_page = "https://zenodo.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'Built and developed by researchers, to ensure that everyone can join in Open Science.

The OpenAIRE project, in the vanguard of the open access and open data movements in Europe was commissioned by the EC to support their nascent Open Data policy by providing a catch-all repository for EC funded research. CERN, an OpenAIRE partner and pioneer in open source, open access and open data, provided this capability and Zenodo was launched in May 2013.

In support of its research programme CERN has developed tools for Big Data management and extended Digital Library capabilities for Open Data. Through Zenodo these Big Science tools could be effectively shared with the long­-tail of research.';

}

?>