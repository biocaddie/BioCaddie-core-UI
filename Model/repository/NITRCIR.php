<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class NitrcirRepository extends RepositoryBase {

    public $repoShowName = 'NITRCIR';
    public $wholeName = 'NITRC&nbsp;Neuroimaging&nbsp;Data&nbsp;Repository';
    public $id = '0033';
    public $source = "http://www.nitrc.org/ir/";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.types','dataset.creators','taxonomicInformation.name'];
    public $facetsFields = ['taxonomicInformation.name.raw'];
    public $facetsShowName = [
        'taxonomicInformation.name.raw'=>'Taxonomic Information',

    ];
    public $index = 'nitrcir';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID', 'dataset.types','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID'=>'ID',
        'dataset.types'=>'Types',
        'dataset.dateReleased'=>'Release Date'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Types','Released Date' ];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.types','dataset.dateReleased' ];


    public $source_main_page = "http://www.nitrc.org/ir/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The Neuroimaging Informatics Tools and Resources Clearinghouse (NITRC) facilitates finding and comparing neuroimaging resources for functional and structural neuroimaging analyses—including popular tools as well as those that once might have been hidden in another researcher\'s laboratory or some obscure corner of cyberspace. NITRC-IR currently contains 14 Projects, 6845 Subjects, and 8285 Imaging Sessions.';

}

?>