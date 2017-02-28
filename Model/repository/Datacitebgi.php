<?php
/**
 * Created by PhpStorm.
 * User: xchen2
 * Date: 2/29/16
 * Time: 4:20 PM
*/
require_once dirname(__FILE__) . '/../RepositoryBase.php';

class DatacitebgiRepository extends RepositoryBase {

    public $repoShowName = 'BGI';
    public $wholeName = 'Beijing&nbsp;Genomics&nbsp;Institute';
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
    public $description = 'BGI was founded in 1999 with the vision of using genomics to benefit mankind and has since become the largest genomic organization in the world. With a focus on research and applications in the healthcare, agriculture, conservation, and environmental fields, BGI has a proven track record of innovative, high profile research, which has generated over 1,949 publications, many in top-tier journals such as Nature and Science.';

}

?>