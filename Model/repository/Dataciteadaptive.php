<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DataciteadaptiveRepository extends RepositoryBase {

    public $repoShowName = 'Adaptive Biotechnologies';
    public $wholeName = 'Adaptive&nbsp;Biotechnologies';
    public $id = '0051';
    public $source = "http://www.adaptivebiotech.com/";

    public $facetsFields = ['dataset.types.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.refinement.raw'=>'Refinement'
    ];
    public $index = 'dataciteadaptive';
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


    public $source_main_page = "http://www.adaptivebiotech.com/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'Adaptive is at the forefront of immune-based discoveries, combining high-throughput sequencing and expert bioinformatics to profile T-cell and B-cell receptors. We bring the accuracy and sensitivity of our immunosequencing platform into laboratories around the world to drive groundbreaking research in cancer and other immune-mediated diseases. Adaptive also translates immunosequencing discoveries into clinical diagnostics and therapeutic development to improve patient care.';

}

?>