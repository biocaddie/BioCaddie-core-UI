<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';
require_once dirname(__FILE__) . '/../Utilities.php';

class GtexldaccRepository extends RepositoryBase {

    public $repoShowName = 'GTex';
    public $wholeName ='Genotype-Tissue&nbsp;Expression';
    public $id = '0073';
    public $source = "";

    //public $searchFields = ['dataset.ID', 'dataset.title','dataset.creators','dataset.keywords','dataset.type',
    //                        'attributes.description'];
    public $facetsFields = ['dataset.distributions.raw'];
    public $facetsShowName = [
        'dataset.distributions.raw' => 'Data Types'
    ];
    public $index = 'gtexldacc';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.description','grant.name'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.dataReleased' => 'Released Date',
        'dataset.ID'=>'ID',
        'dataset.description'=>'Description',
        'grant.name'=>'Grant'
    ];


    //search-repository page
    public $searchRepoHeader = ['Dataset Title', 'Dataset ID','Description','Grant'];
    public $searchRepoField = [ 'dataset.title', 'dataset.ID','dataset.description','grant.name'];

    public $source_main_page = 'https://gtexportal.org';
    public $sort_field = 'dataset.dataReleased';
    public $description = 'The Genotype-Tissue Expression (GTEx) project aims to provide to the scientific community a resource with which to study human gene expression and regulation and its relationship to genetic variation.';
    public function getDisplayItemView($rows)
    {
        // Main Panel: Key/Value Array of Items
        // Array of Entities (Key/Value Pairs)
        // Each Entity Represents an Array of Properties (Key/Value Pairs)

        //if(count($rows['material'])>0){
        //    $rows['material'] = $rows['material']['characteristics']['dimension'];
        //}
        $search_results = get_display_item_common_view($this->id,$rows);

        return $search_results;
    }



}

?>