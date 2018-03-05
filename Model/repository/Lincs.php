<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 12:40 PM
 */
require_once dirname(__FILE__) . '/../RepositoryBase.php';
class LincsRepository extends RepositoryBase {

    public $repoShowName = 'LINCS';
    public $id = '0004';
    public $wholeName = 'Library&nbsp;of&nbsp;Integrated&nbsp;Network-Based&nbsp;Cellular&nbsp;Signatures';
    public $source = "https://lincs.hms.harvard.edu/db/datasets/";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.creators','dataset.types',
    //                        'dimension.name','protein.name', 'cellLine.name', 'biologicalProcess.name','person.name',
    //                       'assay.name','antibody.name','internal.projectName','iPSC.name','primaryCell.name','molecularEntity.name','phosphoProtein.name'];

    public $facetsFields = ['cellLine.name.raw','dataset.types.raw','biologicalProcess.name.raw',"assay.name.raw"];
    public $facetsShowName = ['cellLine.name.raw' => 'Cell Line',
        'dataset.types.raw'=>'Data Type',
        'biologicalProcess.name.raw'=>'Biological Process',
        "assay.name.raw"=>"Assay"
    ];
    public $index = 'lincs';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title','dataset.types', 'assay.name','biologicalProcess.name','dataset.ID'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.types' => 'Data Type',
        'biologicalProcess.name' => 'Biological Process',
        'assay.name' => 'Assay',
        'dataset.ID'=>'ID',
    ];
    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Bioligical Process',  'Assay','DataType','Release date'];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'biologicalProcess.name', 'assay.name','dataset.types','dataset.dateReleased'];


    public $source_main_page = "http://www.lincsproject.org";
    public $sort_field = 'dataset.dateReleased';
    public $description='LINCS aims to create a network-based understanding of biology by cataloging changes in gene expression and other cellular processes that occur when cells are exposed to a variety of perturbing agents, and by using computational tools to integrate this diverse information into a comprehensive view of normal and disease states that can be applied for the development of new biomarkers and therapeutics.';



    public function getDisplayItemView($rows)
    {
        $search_results = parent::getDisplayItemView($rows);

        $search_results['protein'][0][2]='';
        $search_results['protein'][0][1]=str_replace('<br>',' ',$search_results['protein'][0][1]);
        return $search_results;
    }

}

