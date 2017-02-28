<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitegndRepository extends RepositoryBase {

    public $repoShowName = 'GND';
    public $wholeName = 'German&nbsp;Neuroinformatics&nbsp;Node';
    public $id = '0041';
    public $source = "http://www.neuroinf.de/";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.creators','dataset.types','attributes.description'];
    public $facetsFields = ['dataset.types'];
    public $facetsShowName = [
        'dataset.types'=>'Types',

    ];
    public $index = 'datacitegnd';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Creators','Released Date' ];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.creators','dataset.dateReleased' ];


    public $source_main_page = "http://www.neuroinf.de/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The global scale of neuroinformatics offers unprecedented opportunities for scientific collaborations between and among experimental and theoretical neuroscientists. To fully harvest these possibilities, coordinated activities are required to improve key ingredients of neuroscience: data access, data storage, and data analysis, together with supporting activities for teaching and training.
';

}

?>