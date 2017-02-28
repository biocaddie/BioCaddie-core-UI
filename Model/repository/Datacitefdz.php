<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitefdzRepository extends RepositoryBase {

    public $repoShowName = 'FDZ-DZA';
    public $wholeName = 'Research&nbsp;Data&nbsp;Centre-DZA';
    public $id = '0054';
    public $source = "https://www.dza.de/en/fdz.html";

    public $facetsFields = ['dataset.types','dataset.refinement'];
    public $facetsShowName = [
        'dataset.types'=>'Types',
        'dataset.refinement'=>'Refinement'
    ];
    public $index = 'datacitefdz';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date'
    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID','Released Date','Creators'];
    public $searchRepoField = ['dataset.title', 'dataset.ID','dataset.dateReleased','dataset.creators' ];


    public $source_main_page = "https://www.dza.de/en/fdz.html";
    public $sort_field = 'dataset.dateReleased';
    public $description ='The FDZ-DZA (Forschungsdatenzentrum DZA) is a facility of the German Centre of Gerontology (Deutsches Zentrum für Altersfragen, DZA)
                            and has received accreditation as research data center DZA by the German Data Forum (RatSWD).
                            Its main task is to make data of the German Ageing Survey DEAS and the German Survey on Volunteering (FWS) accessible to researchers by providing user-friendly Scientific Use Files (SUF),
                            documentation of the contents and instruments as well support for scholars using the data.

';
}

?>