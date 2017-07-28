<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';
require_once dirname(__FILE__) . '/../Utilities.php';

class CiaRepository extends RepositoryBase {

    public $repoShowName = 'TCIA';
    public $wholeName ='The&nbsp;Cancer&nbsp;Imaging&nbsp;Archive';
    public $id = '0016';
    public $source = "";

    //public $searchFields = ['dataset.ID', 'dataset.title','dataset.creators','dataset.keywords','dataset.type',
    //                        'attributes.description'];
    public $facetsFields = ['anatomicalPart.name'];
    public $facetsShowName = [
        'anatomicalPart.name' => 'Anatomical Part'
    ];
    public $index = 'cia';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataAcquisition.name','dataset.ID','dataset.dateReleased'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataAcquisition.name' => 'Anatomical Part',
        'dataset.ID'=>'ID',
        'dataset.dateReleased'=>'Date Released'
    ];


    //search-repository page
    public $searchRepoHeader = ['Dataset Title', 'Dataset ID','Data Acquisition','Date Released'];
    public $searchRepoField = [ 'dataset.title', 'dataset.ID','dataAcquisition.name','dataset.dateReleased'];

    public $source_main_page = 'http://www.cancerimagingarchive.net/';
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The Cancer Imaging Archive (TCIA) is a large archive of medical images of cancer accessible for public download. All images are stored in DICOM file format. The images are organized as "Collections", typically patients related by a common disease (e.g. lung cancer), image modality (MRI, CT, etc) or research focus.';




}

?>