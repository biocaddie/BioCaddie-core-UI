<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class EpigenomicsRepository extends RepositoryBase {

    public $repoShowName = 'Epigenomics';
    public $wholeName = 'Roadmap&nbsp;Epigenomics&nbsp;Project';
    public $id = '0032';
    public $source = "http://www.roadmapepigenomics.org";
    //public $searchFields = ['dataset.ID','dataset.title','dataset.description','dataset.types','AnatomicalPart.name'];
    public $facetsFields = ['anatomicalPart.name.raw','dataset.type.raw'];
    public $facetsShowName = [
        'anatomicalPart.name.raw'=>'Anatomical Part',
        'dataset.type.raw'=>'Dataset Type'
    ];
    public $index = 'epigenomics';
    public $type = 'dataset';

    //search page
    public $searchPageField = ['dataset.title', 'dataset.types', 'anatomicalPart.name','dataset.ID'];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.type'=>'Type',
        'person.name'=>'Person',
        'anatomicalPart.name'=>'Anatomical Part',
        'dataset.ID'=>'ID'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'Types','Anatomical Part','Description'];
    public $searchRepoField = ['dataset.title','dataset.type','anatomicalPart.name', 'dataset.description'];

    public $source_main_page = "http://thedata.org/";
    public $sort_field = '';
    public $description = 'The NIH Roadmap Epigenomics Mapping Consortium was launched with the goal of producing a public resource of human epigenomic data to catalyze basic biology and disease-oriented research. The Consortium leverages experimental pipelines built around next-generation sequencing technologies to map DNA methylation, histone modifications, chromatin accessibility and small RNA transcripts in stem cells and primary ex vivo tissues selected to represent the normal counterparts of tissues and organ systems frequently involved in human disease. ';

}

?>