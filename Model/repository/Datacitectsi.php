<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitectsiRepository extends RepositoryBase {

    public $repoShowName = 'UCSF-CTSI';
    public $wholeName = 'UCSF&nbsp;Clinical&nbsp;&&nbsp;Translational&nbsp;Science&nbsp;Institute';
    public $id = '0053';
    public $source = "https://ctsi.ucsf.edu/";

    public $facetsFields = ['dataset.types.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.refinement.raw'=>'Refinement'
    ];
    public $index = 'datacitectsi';
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


    public $source_main_page = "https://ctsi.ucsf.edu/";
    public $sort_field = 'dataset.dateReleased';
    public $description ='The Clinical & Translational Science Institute (CTSI) facilitates clinical and translational research to improve patient and community health.
                            We do this by providing infrastructure, services and training to enable research to be conducted more efficiently, effectively and in new ways.

';
}

?>