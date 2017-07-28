<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitebgiRepository extends RepositoryBase {

    public $repoShowName = 'GigaDB';
    public $wholeName = 'GigaScience database';
    public $id = '0038';
    public $source = "http://www.genomics.cn/en/";
   // public $searchFields = ['dataset.ID','dataset.title','dataset.creators','dataset.types','attributes.description'];
    public $facetsFields = ['dataset.types'];
    public $facetsShowName = [
        'dataset.types'=>'Types',

    ];
    public $index = 'datacitebgi';
    public $type = 'dataset';
    //search page
    public $searchPageField = ['dataset.title', 'dataset.ID','dataset.dateReleased','attributes.description' ];
    public $searchPageHeader = [
        'dataset.title'=>'Title',
        'dataset.ID' =>'ID',
        'dataset.dateReleased'=>'Release Date',
        'attributes.description'=>'Description'

    ];

    //search-repository page
    public $searchRepoHeader = ['Title', 'ID', 'Creators','Released Date' ];
    public $searchRepoField = ['dataset.title', 'dataset.ID', 'dataset.creators','dataset.dateReleased' ];

    public $source_main_page = "http://www.genomics.cn/en/";
    public $sort_field = 'dataset.dateReleased';
    public $description = 'GigaDB primarily serves as a repository to host data and tools associated with articles in GigaScience; however, it also includes a subset of datasets that are not associated with GigaScience articles; primarily from our funding partners BGI and CNGB. GigaDB defines a dataset as a group of files (e.g., sequencing data, analyses, imaging files, software programs) that are related to and support an article or study.';

}

?>