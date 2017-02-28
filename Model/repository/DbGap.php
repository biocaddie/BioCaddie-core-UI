<?php

require_once dirname(__FILE__) . '/../RepositoryBase.php';

/* The database of Genotypes and Phenotypes (dbGaP) was developed to archive 
 * and distribute the results of studies that have investigated the 
 * interaction of genotype and phenotype. */
class DbGapRepository extends RepositoryBase {

    public $repoShowName = 'dbGaP';
    public $wholeName = 'The&nbsp;database&nbsp;of&nbsp;Genotypes&nbsp;and&nbsp;Phenotypes ';
    public $id = '0001';
    public $source = "http://www.ncbi.nlm.nih.gov/projects/gap/cgi-bin/study.cgi?study_id=";
    //public $searchFields = ['IDName','title', 'category', 'disease', 'MESHterm','desc','phenID','topic'];
    public $facetsFields = ['disease.name.raw', 'study.types.raw'];
    public $facetsShowName = ['disease.name.raw' => 'Disease', 'study.types.raw' => 'Study Types'];

    public $index = 'dbgap';
    public $type = 'dataset';

    public $searchPageField = ['dataset.title', 'dataset.description', 'study.types'];
    public $searchPageHeader = [
        'dataset.title' => 'Title',
        'dataset.description' => 'Description',
        'study.types' => 'Study Types'
        ];
    public $searchRepoHeader = ['Title', 'Disease', 'Study Types','Study Group'];
    public $searchRepoField = ['dataset.title', 'disease.name','study.types', 'studyGroup.name'];


    public $source_main_page = 'http://www.ncbi.nlm.nih.gov/gap';
    public $sort_field = '';
    public $description='The database of Genotypes and Phenotypes (dbGaP) archives and distributes the results of studies that have investigated the interaction of genotype and phenotype.';

}

?>