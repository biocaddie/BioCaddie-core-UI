<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class BmrbRepository extends RepositoryBase {

    public $repoShowName = 'BMRB';
    public $wholeName = 'Biological&nbsp;Magnetic&nbsp;Resonance&nbsp;Data&nbsp;Bank';
    public $id = '0027';
    public $source = "http://www.bmrb.wisc.edu";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.creator','primaryPublications.authors','primaryPublications.title','primaryPublications.type','PrimaryPublication.ID'];
    public $facetsFields = ['primaryPublications.type.raw'];
    public $facetsShowName = [
        'primaryPublications.type.raw'=>'Primary Publications Type',
    ];
    public $index = 'bmrb';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID', 'dataset.creator','dataset.dateLastreleased'];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID'=>'ID',
        'dataset.creator'=>'Creator',
        'dataset.dateLastreleased'=>'Release Date'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title','ID', 'Date Created','Date Released' ];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.dateCreated','dataset.dateReleased' ];


    public $source_main_page = "http://thedata.org/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'A Repository for Data from NMR Spectroscopy on Proteins, Peptides, Nucleic Acids, and other Biomolecules';

}

?>