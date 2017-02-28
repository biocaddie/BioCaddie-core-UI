<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
 */
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class openFMRIRepository extends RepositoryBase {

    public $repoShowName = 'openfMRI';
    public $wholeName = 'Open&nbsp;sharing&nbsp;of&nbsp;Functional&nbsp;Magnetic&nbsp;Resonance&nbsp;Imaging';
    public $id = '0019';
    public $source = "https://openfmri.org/dataset/";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.description','dataAcquisition.name','taxonomicInformation.name'];
    public $facetsFields = ['taxonomicInformation.name'];
    public $facetsShowName = [
        'taxonomicInformation.name'=>'Taxonomic Information',

    ];
    public $index = 'openfmri';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.description', 'dataset.ID' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.description'=>'Description',
        'dataset.ID'=>'ID'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Description','Data Acquisition','Date Released' ];
    public $searchRepoField = ['dataset.title', 'dataset.description','dataAcquisition.name','dataset.dateReleased' ];


    public $source_main_page = "https://openfmri.org";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'OpenfMRI.org is a project dedicated to the free and open sharing of functional magnetic resonance imaging (fMRI) datasets, including raw data. The focus of the database is on task fMRI data.';


}

?>