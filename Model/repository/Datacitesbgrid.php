<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitesbgridRepository extends RepositoryBase {

    public $repoShowName = 'SBGrid';
    public $wholeName = '';
    public $id = '0045';
    public $source = "https://sbgrid.org/";

    public $facetsFields = ['dataset.types.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.refinement.raw'=>'Refinement'
    ];
    public $index = 'datacitesbgrid';
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


    public $source_main_page = "https://sbgrid.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'We support publication of X-ray diffraction, MicroED, LLSM datasets, as well as structural models. All visitors can access our Laboratory and Institutional Collections. All structural biologists are invited to deposit datasets.';

}

?>