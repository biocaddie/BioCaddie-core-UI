<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
 */
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class CILRepository extends RepositoryBase {

    public $repoShowName = 'CIL';
    public $wholeName = 'Cell&nbsp;Image&nbsp;Library';
    public $id = '0024';
    public $source = "";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.description','dataset.types','dataset.creators',
    //                        'BiologicalEntity.name','taxonomicInformation.name','activity.name','activity.keywords',
    //                        'biologicalProcess.name','cellline.name','cellularcomponent.name','dataAcquisition.name'];
    public $facetsFields = ['activity.name.raw','activity.keywords.raw'];
    public $facetsShowName = [
        'activity.keywords.raw'=>'Activity Keywords',
        'activity.name.raw'=>'Activity Name'
    ];
    public $index = 'cil';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title','dataset.ID', 'dataset.description', 'dataset.types'];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.types'=> 'Data Types',
        'dataset.ID'=>'ID',
        'dataset.description'=>'Description'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Data Types','Dimensions','Description','Date Released' ];
    public $searchRepoField = ['dataset.title','dataset.ID',  'dataset.types','dataset.dimensions','dataset.description','dataset.dateReleased'];

    public $source_main_page = "http://www.cellimagelibrary.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description='The Cell Image Library™ is a freely accessible, easy-to-search, public repository of reviewed and annotated images, videos, and animations of cells from a variety of organisms, showcasing cell architecture, intracellular functionalities, and both normal and abnormal processes. The purpose of this database is to advance research, education, and training, with the ultimate goal of improving human health.';

}

?>