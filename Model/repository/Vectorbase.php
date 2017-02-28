<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class VectorbaseRepository extends RepositoryBase {

    public $repoShowName = 'VectorBase';
    public $wholeName = 'VectorBase';
    public $id = '0040';
    public $source = "https://www.vectorbase.org/";
    //public $searchFields = ['dataset.title','dataset.description','dataset.isAbout','dataset.type','dataDistribution.format'];
    public $facetsFields = ['dataset.isAbout.raw'];
    public $facetsShowName = [
        'dataset.isAbout.raw'=>'Is About',

    ];
    public $index = 'vectorbase';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.description', 'dataset.dateReleased' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.description'=>'Description',
        'dataset.dateReleased'=>'Release Date'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Description', 'Is About','Type' ];
    public $searchRepoField = ['dataset.title', 'dataset.description', 'dataset.isAbout','dataset.type' ];


    public $source_main_page = "https://www.vectorbase.org/";
    public $sort_field = '';
    public $description = 'VectorBase is a National Institute of Allergy and Infectious Diseases (NIAID) Bioinformatics Resource Center (BRC) providing genomic, phenotypic and population-centric data to the scientific community for invertebrate vectors of human pathogens.';

}

?>