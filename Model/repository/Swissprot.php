<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

class SwissprotRepository extends RepositoryBase {

    public $repoShowName = 'UniProt:Swiss-Prot';
    public $wholeName ='UniProt:Swiss-Prot';
    public $id = '0028';
    public $source = "http://www.uniprot.org/";

    //public $searchFields = ['dataset.ID', 'dataset.title', 'dataset.keywords', 'dataset.description','dataset.types','datset.creators',
    //                        'biologicalEntity.name','primaryPublication.ID','primaryPublication.authorsList',
   //                         'taxonomicInformation.ID','taxonomicInformation.name','taxonomicInformation.strain'];
    public $facetsFields = ['dataset.keywords.raw','biologicalEntity.name.raw'];
    public $facetsShowName = [
        'dataset.keywords.raw' => 'Keyword',
        'biologicalEntity.name.raw'=>'Biological Entity'
    ];
    public $index = 'swissprot';
    public $type = 'dataset';

    //search page
    public $searchPageField  = ['dataset.title', 'dataset.ID', 'dataset.description'];
    public $searchPageHeader  = [
        'dataset.title' => 'Title',
        'dataset.ID' => 'ID',
        'dataset.description'=>'Description',
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID','Date Released','Keywords','Description'];
    public $searchRepoField = [ 'dataset.title', 'dataset.ID','dataset.dateReleased','dataset.keywords','dataset.description'];

    public $source_main_page = 'http://datadryad.org/';
    public $sort_field = 'dataset.dateReleased';
    public $description='UniProtKB/Swiss-Prot is a manually annotated, non-redundant protein sequence database. It combines information extracted from scientific literature and biocurator-evaluated computational analysis. The aim of UniProtKB/Swiss-Prot is to provide all known relevant information about a particular protein. Annotation is regularly reviewed to keep up with current scientific findings. The manual annotation of an entry involves detailed analysis of the protein sequence and of the scientific literature.';

}

?>