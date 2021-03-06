<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitebilsRepository extends RepositoryBase {

    public $repoShowName = 'BILS';
    public $wholeName = 'Bioinformatics&nbsp;Infrastructure&nbsp;for&nbsp;Life&nbsp;Sciences';
    public $id = '0048';
    public $source = "https://bils.se/";

    public $facetsFields = ['dataset.types.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.refinement.raw'=>'Refinement'
    ];
    public $index = 'datacitebils';
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


    public $source_main_page = "https://bils.se/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'BILS (Bioinformatics Infrastructure for Life Sciences) is a distributed national research infrastructure supported by the Swedish Research Council (Vetenskapsrådet) providing bioinformatics support to life science researchers in Sweden.';

}

?>