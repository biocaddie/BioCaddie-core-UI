<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';
require_once dirname(__FILE__) . '/../Utilities.php';

class MetabolomicsRepository extends RepositoryBase {

    public $repoShowName = 'Metabolomics';
    public $wholeName ='Metabolomics&nbsp;Workbench';
    public $id = '0074';
    public $source = "";

    //public $searchFields = ['dataset.ID', 'dataset.title','dataset.creators','dataset.keywords','dataset.type',
    //                        'attributes.description'];
    public $facetsFields = ['study.types.raw'];
    public $facetsShowName = [
        'study.types.raw' => 'Study Types'
    ];
    public $index = 'metabolomics';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.ID','study.name','grant.identifier','Grant.name'];
    public $searchPageHeader = [
        'study.name' => 'Study Name',
        'grant.identifier' => 'Grant ID',
        'dataset.ID'=>'ID',
        'Grant.name'=>'Grant'
    ];


    //search-repository page
    public $searchRepoHeader = [ 'Dataset ID','Study Name','Study Types','Study RelatedIdentifiers'];
    public $searchRepoField = [  'dataset.ID','study.name','study.types','study.relatedIdentifiers'];

    public $source_main_page = 'http://www.metabolomicsworkbench.org/';
    public $sort_field = 'dataset.dataReleased';
    public $description = 'The Metabolomics Workbench serves as a national and international repository for metabolomics data and metadata and provides analysis tools and access to metabolite standards, protocols, tutorials, training, and more.';
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