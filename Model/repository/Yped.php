<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
 */
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class YpedRepository extends RepositoryBase {

    public $repoShowName = 'YPED';
    public $wholeName = 'Yale&nbsp;Protein&nbsp;Expression&nbsp;Database';
    public $id = '0023';
    public $source = "";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.description','dataset.types','dataset.creators',
    //                        'material.roles','material.description','dataAcquisition.name','primaryPublications.ID',
    //                       'taxonomicinformation.name'];

    public $facetsFields = ['dataAcquisition.name.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataAcquisition.name.raw'=>'Data Acquisition',
        'dataset.refinement.raw'=>'Dataset Refinement'
    ];
    public $index = 'yped';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.description', 'primaryPublications.ID'];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.description'=>'Description',
        'primaryPublications.ID'=>'Primary Publications'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Description', 'Data Acquisition','Date Released'];
    public $searchRepoField = ['dataset.title', 'dataset.description', 'dataAcquisition.name','dataset.dateReleased'];


    public $source_main_page = "http://yped.med.yale.edu/repository/";
    public $sort_field = 'dataset.dateReleased';
    public $description='The Yale Protein Expression Database (YPED) is an open source system for storage, retrieval, and integrated analysis of large amounts of data from high throughput proteomic technologies. YPED currently handles LCMS, MudPIT, ICAT, iTRAQ, SILAC, 2D Gel and DIGE, Label Free Quantitation (Progenesis), Label Free Quantitation (Skyline), MRM analysis and SWATH This repository contains data sets which have been released for public viewing and downloading by the responsible Primary Investigators.';

    public function getDisplayItemView($rows)
    {
        $search_results = parent::getDisplayItemView($rows);
        for($i=0;$i<sizeof($search_results['dataset']);$i++) {
            if ($search_results['dataset'][$i][0] == 'ID') {
                $search_results['dataset'][$i][1] = '';
                $search_results['dataset'][$i][2] = '';
                break;
            }
        }
        return $search_results;
    }

}

?>