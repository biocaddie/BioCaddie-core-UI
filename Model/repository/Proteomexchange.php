<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
 */
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class ProteomexchangeRepository extends RepositoryBase
{

    public $repoShowName = 'ProteomeXchange';
    public $wholeName = 'ProteomeXchange';
    public $id = '0022';
    public $source = "http://www.proteomexchange.org/";
    //public $searchFields = ['dataset.title', 'dataset.ID', 'dataset.keywords','instrument.name','primaryPublication.title','person.fullName','taxonomicInformation.name'];
    public $facetsFields = ['dataset.keywords.raw', 'taxonomicInformation.name.raw'];
    public $facetsShowName = [
        'dataset.keywords.raw' => 'Keywords',
        'taxonomicInformation.name.raw' => 'Taxonomic Information'

    ];
    public $index = 'proteomexchange';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID', 'instrument.name', 'dataset.dateReleased'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.ID' => 'ID',
        'instrument.name' => 'Instrument',
        'dataset.dateReleased' => 'Date Released'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Keywords', 'Publication', 'Organism','Date Released'];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.keywords', 'primaryPublication.title', 'taxonomicInformation.name','dataset.dateReleased'];


    public $source_main_page = "http://www.proteomexchange.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description='The ProteomeXchange consortium has been set up to provide a single point of submission of MS proteomics data to the main existing proteomics repositories, and to encourage the data exchange between them for optimal data dissemination.';

}

?>