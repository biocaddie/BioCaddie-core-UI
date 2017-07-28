<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';
require_once dirname(__FILE__) . '/../Utilities.php';

class WormbaseRepository extends RepositoryBase {

    public $repoShowName = 'Wormbase';
    public $wholeName ='WormBase';
    public $id = '0072';
    public $source = "";

    //public $searchFields = ['dataset.ID', 'dataset.title','dataset.creators','dataset.keywords','dataset.type',
    //                        'attributes.description'];
    public $facetsFields = ['dataset.type'];
    public $facetsShowName = [
        'dataset.type' => 'Data Type'
    ];
    public $index = 'wormbase';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.isAbout','grant.name'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.isAbout' => 'Is about',
        'dataset.ID'=>'ID',
        'grant.name'=>'Grant Name'
    ];


    //search-repository page
    public $searchRepoHeader = ['Dataset Title', 'Dataset ID','Is About','Grant Name'];
    public $searchRepoField = [ 'dataset.title', 'dataset.ID','dataset.isAbout','grant.name'];

    public $source_main_page = 'http://www.wormbase.org/';
    public $sort_field = '';
    public $description = 'WormBase is an international consortium of biologists and computer scientists dedicated to providing the research community with accurate, current, accessible information concerning the genetics, genomics and biology of C. elegans and related nematodes. Founded in 2000, the WormBase Consortium is led by Paul Sternberg of CalTech, Paul Kersey of the EBI, Matt Berriman of the Wellcome Trust Sanger Institute, and Lincoln Stein of the Ontario Institute for Cancer Research.';

    public function getDisplayItemView($rows)
    {
        // Main Panel: Key/Value Array of Items
        // Array of Entities (Key/Value Pairs)
        // Each Entity Represents an Array of Properties (Key/Value Pairs)

        if(count($rows['material'])>0){
            $rows['material'] = $rows['material']['characteristics']['dimension'];
        }
        $search_results = get_display_item_common_view($this->id,$rows);

        return $search_results;
    }



}

?>