<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class PeptideatlasRepository extends RepositoryBase {

    public $repoShowName = 'PeptideAtlas';
    public $wholeName = '';
    public $id = '0014';
    public $source = "http://www.peptideatlas.org/";
   // public $searchFields = ['dataset.ID','dataset.title','dataset.description','dataset.types','dataset.creators',
   //                         'instrument.name','taxonomicinformation.name','taxonomicinformation.strain','activity.description','primaryPublications.ID'];
    public $facetsFields = ['dataset.types','instrument.name'];
    public $facetsShowName = [
        'dataset.types'=>'Dataset Types',
        'instrument.name'=>'Instrument'

    ];
    public $index = 'peptideatlas';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.description','instrument.name'];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.description'=>'Description',
        'instrument.name'=>'Instrument'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Instrument' ,'Description', 'Activity'];
    public $searchRepoField = ['dataset.title', 'instrument.name','dataset.description', 'activity.description' ];


    public $source_main_page = "http://www.peptideatlas.org/";
    public $sort_field = '';
    public $description= 'PeptideAtlas is a multi-organism, publicly accessible compendium of peptides identified in a large set of tandem mass spectrometry proteomics experiments. Mass spectrometer output files are collected for human, mouse, yeast, and several other organisms, and searched using the latest search engines and protein sequences.';




}

?>