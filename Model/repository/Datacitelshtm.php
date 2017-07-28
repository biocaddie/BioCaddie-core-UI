<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitelshtmRepository extends RepositoryBase {

    public $repoShowName = 'LSHTM';
    public $wholeName = 'London&nbsp;School&nbsp;of&nbsp;Hygiene&nbsp;and&nbsp;Tropical&nbsp;Medicine';
    public $id = '0059';
    public $source = "http://www.lshtm.ac.uk/";

    public $facetsFields = ['dataset.types.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.refinement.raw'=>'Refinement'
    ];
    public $index = 'datacitelshtm';
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


    public $source_main_page = "http://www.lshtm.ac.uk/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'LSHTM Data Compass is a curated digital repository of research datasets produced by the London School of Hygiene & Tropical Medicine and its collaborators.
    It addresses the School\'s mission to improve health and health equity in the UK and worldwide and maximise the benefit and impact of its research by ensuring the underlying data can be safeguarded,
    shared and cited.
    ';

}

?>