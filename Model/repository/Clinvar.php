<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class ClinvarRepository extends RepositoryBase {

    public $repoShowName = 'ClinVar';
    public $wholeName = '';
    public $id = '0029';
    public $source = "https://www.ncbi.nlm.nih.gov/clinvar/";
    //public $searchFields = ['dataset.title','dataset.type','AlternateIdentifiers.ID','TaxonomyInformation.ID','TaxonomyInformation.Species'];
    public $facetsFields = ['taxonomicInformation.species'];
    public $facetsShowName = [
        'taxonomicInformation.species'=>'Taxonomic Information',

    ];
    public $index = 'clinvar';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.type','taxonomicInformation.species' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.type'=>'Type',
        'taxonomicInformation.species'=>'Species'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'type', 'Taxonomic Information','Alternate Identifiers'];
    public $searchRepoField = ['dataset.title', 'dataset.type', 'taxonomicInformation.species','alternateIdentifiers.ID'];

    public $source_main_page = "http://thedata.org/";
    public $sort_field = '';
    public $description = 'ClinVar aggregates information about genomic variation and its relationship to human health.';

}

?>