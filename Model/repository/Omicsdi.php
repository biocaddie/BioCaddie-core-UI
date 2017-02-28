<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';
require_once dirname(__FILE__) . '/../Utilities.php';

class OmicsdiRepository extends RepositoryBase {

    public $repoShowName = 'OmicsDI';
    public $wholeName ='Omics&nbsp;Discovery&nbsp;Index';
    public $id = '0044';
    public $source = "";

    public $facetsFields = ['dataset.keywords'];
    public $facetsShowName = [
        'dataset.keywords' => 'Keywords'
    ];
    public $index = 'omicsdi';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title','dataset.ID','dataset.dateReleased', 'dataset.description'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.ID'=>'ID',
        'dataset.dateReleased'=>'Date Released',
        'dataset.description' => 'Description'
    ];


    //search-repository page
    public $searchRepoHeader = ['Dataset Title', 'Keywords','Date Released','Description'];
    public $searchRepoField = [ 'dataset.title', 'dataset.keywords','dataset.dateReleased','dataset.description'];

    public $source_main_page = 'http://www.omicsdi.org/';
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The Omics Discovery Index (OmicsDI) provides dataset discovery across a heterogeneous, distributed group of Transcriptomics, Genomics, Proteomics and Metabolomics data resources spanning eight repositories in three continents and six organisations, including both open and controlled access data resources. The resource provides a short description of every dataset: accession, description, sample/data protocols biological evidences, publication, etc. Based on these metadata, OmicsDI provides extensive search capabilities, as well as identification of related datasets by metadata and data content where possible. In particular, OmicsDI identifies groups of related, multi-omics datasets across repositories by shared identifiers.';




}

?>