<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitembfRepository extends RepositoryBase {

    public $repoShowName = 'MBF';
    public $wholeName = 'MBF&nbsp;Bioscience';
    public $id = '0055';
    public $source = "http://www.mbfbioscience.com/";

    public $facetsFields = ['dataset.types.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.refinement.raw'=>'Refinement'
    ];
    public $index = 'datacitembf';
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


    public $source_main_page = "http://www.mbfbioscience.com/";
    public $sort_field = 'dataset.dateReleased';
    public $description ='We design quantitative imaging software for stereology, neuron reconstruction, and image analysis, integrated with the world’s leading microscope systems,
    to empower your research. Our development team and staff scientists are actively engaged with leading bioscience researchers,
    constantly working to refine our products based on your feedback and scientific advances in the field.

';
}

?>