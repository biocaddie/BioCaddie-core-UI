<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitesdscsgRepository extends RepositoryBase {

    public $repoShowName = 'NSGM';
    public $wholeName = 'UCSD-Nature&nbsp;Signaling&nbsp;Gateway';
    public $id = '0062';
    public $source = "http://www.signaling-gateway.org/";

    public $facetsFields = ['dataset.types.raw','dataset.refinement.raw'];
    public $facetsShowName = [
        'dataset.types.raw'=>'Types',
        'dataset.refinement.raw'=>'Refinement'
    ];
    public $index = 'datacitesdscsg';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Creators','Released Date','Description'];
    public $searchRepoField = ['dataset.title', 'dataset.creators','dataset.dateReleased','attributes.description' ];


    public $source_main_page = "http://www.signaling-gateway.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'The UCSD Signaling Gateway Molecule Pages is a database providing essential information on the thousands of proteins involved in cell signaling.
     This database combines expert authored reviews with curated, highly-structured data (e.g. protein interactions) and automatic annotation from publicly available data sources (e.g. UniProt and Genbank).
      The information and data presented here are freely available to all users. The Signaling Gateway is hosted by the San Diego Supercomputer Center at the University of California, San Diego, and is funded by NIH/NIGMS Grant 1 R01 GM078005-01.
    ';

}

?>