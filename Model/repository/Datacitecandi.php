<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitecandiRepository extends RepositoryBase {

    public $repoShowName = 'CANDI';
    public $wholeName = 'CANDI&nbsp;Neuroimaging&nbsp;Access&nbsp;Point';
    public $id = '0058';
    public $source = "http://www.umassmed.edu/psychiatry/candi/";

    public $facetsFields = ['dataset.types','dataset.refinement'];
    public $facetsShowName = [
        'dataset.types'=>'Types',
        'dataset.refinement'=>'Refinement'
    ];
    public $index = 'datacitecandi';
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


    public $source_main_page = "http://www.umassmed.edu/psychiatry/candi/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The Child and Adolescent NeuroDevelopment Initiative (CANDI) is a research program in the Department of Psychiatry at the University of Massachusetts Medical School dedicated to neuroimaging and treatment studies of individuals with mood disorders,
    trauma, early onset schizophrenia and developmental disabilities including autism and fragile X
    ';

}

?>