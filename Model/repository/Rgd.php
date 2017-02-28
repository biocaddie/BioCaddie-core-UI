<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class RgdRepository extends RepositoryBase {

    public $repoShowName = 'RGD';
    public $wholeName = 'Rat&nbsp;Genome&nbsp;Database';
    public $id = '0037';
    public $source = "http://rgd.mcw.edu";
   // public $searchFields = ['dataset.ID','dataset.creators','dataset.description','BiologicalEntity.identifiers','BiologicalEntity.type','BiologicalEntity.name'];
    public $facetsFields = ['biologicalEntity.type.raw','biologicalEntity.name.raw'];
    public $facetsShowName = [
        'biologicalEntity.type.raw'=>'Biological Entity Type',
        'biologicalEntity.name.raw'=>'Biological Entity Name'
    ];
    public $index = 'rgd';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.ID', 'dataset.description', 'dataset.creators' ];
    public $searchPageHeader = [
        'dataset.ID'=>'ID',
        'dataset.description'=>'Description',
        'dataset.creators'=>'Creators'

    ];

    //search-repository page
    public $searchRepoHeader = ['ID', 'Description', 'Creators','Biological Entity' ];
    public $searchRepoField = ['dataset.ID', 'dataset.description', 'dataset.creators','biologicalEntity.type' ];


    public $link_field = 'dataset.title';
    public $source_main_page = "http://rgd.mcw.edu";
    public $sort_field = '';
    public $description = 'The Rat Genome Database (RGD) was established in 1999 and is the premier site for genetic, genomic, phenotype, and disease data generated from rat research. In addition, it provides easy access to corresponding human and mouse data for cross-species comparisons. RGD\'s comprehensive data and innovative software tools make it a valuable resource for researchers worldwide.';

}

?>