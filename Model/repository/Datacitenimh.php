<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitenimhRepository extends RepositoryBase {

    public $repoShowName = 'NIMH';
    public $wholeName = 'National&nbsp;Institute&nbsp;of&nbsp;Mental&nbsp;Health';
    public $id = '0056';
    public $source = "https://www.nimh.nih.gov/index.shtml";

    public $facetsFields = ['dataset.types','dataset.refinement'];
    public $facetsShowName = [
        'dataset.types'=>'Types',
        'dataset.refinement'=>'Refinement'
    ];
    public $index = 'datacitenimh';
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


    public $source_main_page = "https://www.nimh.nih.gov/index.shtml";
    public $sort_field = 'dataset.dateReleased';
    public $description ='The National Institute of Mental Health (NIMH) is the lead federal agency for research on mental disorders. NIMH is one of the 27 Institutes and Centers that make up the National Institutes of Health (NIH), the nation’s medical research agency. NIH is part of the U.S. Department of Health and Human Services (HHS).

';
}

?>