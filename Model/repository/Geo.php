<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class GeoRepository extends RepositoryBase {

    public $repoShowName = 'GEO';
    public $wholeName = 'Gene&nbsp;Expression&nbsp;Omnibus';
    public $id = '0003';
    //public $source = "http://www.ncbi.nlm.nih.gov/sites/GDSbrowser?acc=";
    public $source = "http://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=";
    //public $searchFields = ['dataset.ID','dataset.title', 'dataset.description', 'dataset.types','dataset.creators',
   //                         'taxonomicInformation.name','study.ID','identifiers.ID','primaryPublication.ID','instrument.name'];

    public $facetsFields = ['dataset.types.raw', "instrument.name.raw"];
    public $facetsShowName = ['dataset.types.raw'=> 'Data Types',
        "instrument.name.raw" => 'Instrument'];
    public $index = 'geo';
    public $type = 'dataset';

    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.description', 'dataset.types', 'instrument.name'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.description' => 'Description',
        'dataset.types' => 'Types',
        'dataset.ID'=>'ID',
        'instrument.name'=>'Instrument'];


    public $searchRepoHeader = ['Title', 'ID','Types','Description','Date Released'];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.types','dataset.description','dataset.dateReleased'];

    public $source_main_page = 'http://www.ncbi.nlm.nih.gov/geo/';
    public $sort_field = 'dataset.dateReleased';
    public $description='Gene Expression Omnibus is a public functional genomics data repository supporting MIAME-compliant submissions of array- and sequence-based data. Tools are provided to help users query and download experiments and curated gene expression profiles.';

    /*public function getDisplayItemView($rows)
    {
        $search_results = parent::getDisplayItemView($rows);
        unset($search_results['datasetDistributions']);
        return $search_results;
    }
*/
}

?>