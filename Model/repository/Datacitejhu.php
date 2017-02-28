<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitejhuRepository extends RepositoryBase {

    public $repoShowName = 'JHUDMS';
    public $wholeName = 'Johns&nbsp;Hopkins&nbsp;University&nbsp;Data&nbsp;Archive';
    public $id = '0057';
    public $source = "https://dmp.data.jhu.edu/";

    public $facetsFields = ['dataset.types','dataset.refinement'];
    public $facetsShowName = [
        'dataset.types'=>'Types',
        'dataset.refinement'=>'Refinement'
    ];
    public $index = 'datacitejhu';
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


    public $source_main_page = "https://dmp.data.jhu.edu/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'Johns Hopkins University Data Management Services provides archiving services for the Johns Hopkins research community through the JHU Data Archive. While some academic disciplines have established research data repositories, many fields of research do not have easily available options for archiving and sharing data. Our archiving services give researchers the opportunity to share their data outside of original collaborations and beyond the life of a researcJHUDA logo2h project.
Characteristics of the JHU Data Archive:
Data from any research discipline and with any file format
Each dataset given a permanent citation and DOI, facilitating both attribution for authors and linkage to research publications
Preservation of research data through regular file integrity checks and retention of multiple copies';

}

?>